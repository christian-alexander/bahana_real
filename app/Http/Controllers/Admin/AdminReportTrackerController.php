<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\EmployeeDetails;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\Tracker;

class AdminReportTrackerController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Laporan Tracking';
        $this->pageIcon = 'ti-pie-chart';
        $this->middleware(function ($request, $next) {
            if (!in_array('reports', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }
    public function index()
    {
        $this->employees = User::allEmployees();
        $this->todayStart = Carbon::now()->format('d-m-Y');
        $this->todayEnd = Carbon::now()->format('d-m-Y');
        return view('admin.reports.tracker.index', $this->data);
        // return 'asd';
    }
    public function find(request $request)
    {
        // carbon::now() harus di +7
        // karena asumsi jam server adalah +7
        // CREATED AT DI INSERT MANUAL

        $this->employees = User::allEmployees();
        $this->employeeSelected = $request->employee_id;
        if (isset($request->startDate) && !empty($request->startDate)){ 
            $this->todayStart = Carbon::parse($request->startDate)->format('d-m-Y');
            $start_date = Carbon::parse($request->startDate)->format('Y-m-d');
        }else{
            $this->todayEnd = Carbon::now()->addHours(7)->format('d-m-Y');
            $start_date = Carbon::now()->addHours(7)->format('Y-m-d');
        }
        if (isset($request->endDate) && !empty($request->endDate)){ 
            $this->todayEnd = Carbon::parse($request->endDate)->format('d-m-Y');
            $end_date = Carbon::parse($request->endDate)->format('Y-m-d');
        }else{
            $this->todayEnd = Carbon::now()->addHours(7)->format('d-m-Y');
            $end_date = Carbon::now()->addHours(7)->format('Y-m-d');
        }

        
        $this->todayEnd = Carbon::parse($request->endDate)->format('d-m-Y');

        // $start_date = Carbon::parse($request->startDate)->format('Y-m-d');
        // $end_date = Carbon::parse($request->endDate)->format('Y-m-d');
        // $request->employee_id = user_id
        $getUser = User::find($request->employee_id);
        $tracker = [];
        // get tracking data
        // dd($start_date);
        $trackers = Tracker::where('user_id', $getUser->id)
            ->where('company_id', $getUser->company_id)
            // ->whereBetween('created_at', [$start_date, $end_date])
            ->where(function ($q) use ($start_date,$end_date) {
                // $q->whereDate('created_at','>=',$start_date)
                // ->whereDate('created_at','<=',$end_date);
                // $q->whereDate(\DB::raw('DATE_ADD(created_at, INTERVAL 7 HOUR)'),'>=', $start_date)
                // ->whereDate(\DB::raw('DATE_ADD(created_at, INTERVAL 7 HOUR)'),'<=', $end_date);
                $q->whereDate('created_at_after_timezone','>=', $start_date)
                ->whereDate('created_at_after_timezone','<=', $end_date);
            })
            ->select('latitude as lat', 'longitude as lng','created_at','created_at_after_timezone','catatan','custom_note', 'is_manual')
            ->orderBy('created_at')
            ->get()->toArray();
        // if (count($trackers)==0) {
        //     $tracker = [[
        //         0=>[
        //             "lat" => (double)"-7.256215314321651",
        //             "lng" => (double)"112.75201350262476",
        //         ],
        //         "type"=> null,
        //         "created_at" => Carbon::parse($start_date)->format("d/m/Y H:i:s"),
        //         "created_at_after_timezone" => Carbon::parse($start_date)->format("d/m/Y H:i:s")
        //     ]
        //     ];
        // }
     	foreach($trackers as $key => &$val){
          $tracker[$key][0]['lat'] = (double)$val['lat'];
          $tracker[$key][0]['lng'] = (double)$val['lng'];
          $tracker[$key]['type'] = $val['catatan'].' '.!empty($val['custom_note'])?$val['custom_note']:'';
          $tracker[$key]['catatan'] = $val['catatan'];
          $tracker[$key]['custom_note'] = $val['custom_note'];
          $tracker[$key]['is_manual'] = $val['is_manual'];
          $tracker[$key]['created_at'] = Carbon::parse($val['created_at_after_timezone'])->format("d/m/Y H:i:s");
          $tracker[$key]['created_at_after_timezone'] = Carbon::parse($val['created_at_after_timezone'])->format("d/m/Y H:i:s");
        }
        // $this->tracker = json_encode($tracker);
     	
        $attendances = DB::table('attendances')->where('user_id', $getUser->id)
            ->where('company_id', $getUser->company_id)
            ->where(function ($q) use ($start_date,$end_date) {
                $q->whereDate('clock_in_after_timezone','>=', $start_date)
                ->whereDate('clock_out_after_timezone','<=', $end_date);
            })
            ->select('clock_in_latitude','clock_in_longitude','clock_out_latitude','clock_out_longitude','clock_in_after_timezone','clock_out_after_timezone')
            ->get()->toArray();
        if (count($attendances)==0) {
            $attendances = DB::table('attendances')->where('user_id', $getUser->id)
                ->where('company_id', $getUser->company_id)
                ->where(function ($q) use ($start_date,$end_date) {
                    $q->whereDate('clock_in_after_timezone', $start_date);
                })
                ->select('clock_in_latitude','clock_in_longitude','clock_out_latitude','clock_out_longitude','clock_in_after_timezone','clock_out_after_timezone')
                ->get()->toArray();
        }
        $curr_index = count($tracker);
        foreach($attendances as $key => $val){
            // clock in
            $clock_in_latitude = -7.256215314321651;
            $clock_in_longitude = 112.75201350262476;
            if (isset($val->clock_in_latitude) && !empty($val->clock_in_latitude)){ 
                $clock_in_latitude = (double)$val->clock_in_latitude;
            }
            if (isset($val->clock_in_longitude) && !empty($val->clock_in_longitude)){ 
                $clock_in_longitude = (double)$val->clock_in_longitude;
            }
            $tracker[$curr_index][0]['lat'] = (double)$clock_in_latitude;
            $tracker[$curr_index][0]['lng'] = (double)$clock_in_longitude;
            $tracker[$curr_index]['type'] = "clock_in";
            $tracker[$curr_index]['created_at'] = Carbon::parse($val->clock_in_after_timezone)->format('d/m/Y H:i:s');
            $tracker[$curr_index]['created_at_after_timezone'] = Carbon::parse($val->clock_in_after_timezone)->format('d/m/Y H:i:s');

            $curr_index++;

            if (isset($val->clock_out_after_timezone) && !empty($val->clock_out_after_timezone)){  
                $clock_out_latitude = -7.256215314321651;
                $clock_out_longitude = 112.75201350262476;
                if (isset($val->clock_out_latitude) && !empty($val->clock_out_latitude)){ 
                    $clock_out_latitude = (double)$val->clock_out_latitude;
                }
                if (isset($val->clock_out_longitude) && !empty($val->clock_out_longitude)){ 
                    $clock_out_longitude = (double)$val->clock_out_longitude;
                }
                // clock out
                $tracker[$curr_index][0]['lat'] = (double)$clock_out_latitude;
                $tracker[$curr_index][0]['lng'] = (double)$clock_out_longitude;
                $tracker[$curr_index]['type'] = "clock_out";
                $tracker[$curr_index]['created_at'] = Carbon::parse($val->clock_out_after_timezone)->format('d/m/Y H:i:s');
                $tracker[$curr_index]['created_at_after_timezone'] = Carbon::parse($val->clock_out_after_timezone)->format('d/m/Y H:i:s');
    
                $curr_index++;
            }

        }
        $tracker_sort = $this->array_sort_by_column($tracker, 'created_at');
        $arr_tracker=[];
        $arr_label=[];
        foreach ($tracker_sort as $val) {
            array_push($arr_tracker,$val[0]);
            array_push($arr_label,$val['created_at']);
        }
        $this->tracker_sort = json_encode($tracker_sort);
        $this->arr_tracker = json_encode($arr_tracker);
        $this->arr_label = json_encode($arr_label);
        return view('admin.reports.tracker.index', $this->data);
    }

    function array_sort_by_column(&$array, $column, $direction = SORT_ASC) {
        $reference_array = array();
    
        foreach($array as $key => $row) {
            $reference_array[$key] = $row[$column];
        }
    
        array_multisort($reference_array, $direction, $array);
        return $array;
    }
  
    // code dibawah ditak digunakan dimana2
    public function findIframe(request $request)
    {
        $this->employees = User::allEmployees();
        $this->employeeSelected = $request->employee_id;
        $this->todayStart = Carbon::parse($request->startDate)->format('d-m-Y');
        $this->todayEnd = Carbon::parse($request->endDate)->format('d-m-Y');

        $start_date = Carbon::parse($request->startDate)->format('Y-m-d');
        $end_date = Carbon::parse($request->endDate)->format('Y-m-d');
        // $request->employee_id = user_id
        $getUser = User::find($request->employee_id);
        // get tracking data
        // dd($start_date);
        $tracker = Tracker::where('user_id', $getUser->id)
            ->where('company_id', $getUser->company_id)
            ->whereBetween('created_at_after_timezone', [$start_date, $end_date])
            ->select('latitude as lat', 'longitude as lng')
            ->get()->toArray();
     	foreach($tracker as &$val){
          $val['lat'] = (double)$val['lat'];
          $val['lng'] = (double)$val['lng'];
        }
        $this->tracker = json_encode($tracker);
        $label = Tracker::where('user_id', $getUser->id)
            ->where('company_id', $getUser->company_id)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->select('created_at')
            ->get()->toArray();
     	foreach($label as &$val){
          $val['created_at'] = date("d/m/Y H:i", strtotime($val['created_at']));
        }
        $this->label = json_encode($label);
     	//dd($this->label);
        return view('admin.reports.tracker.indexIframe', $this->data);
    }
}
