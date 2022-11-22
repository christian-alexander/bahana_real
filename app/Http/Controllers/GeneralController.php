<?php

namespace App\Http\Controllers;

use App\GeneralSetting;
use App\GlobalSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\LaporanKerusakan;
use App\Office;
use App\User;
use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\Tracker;

class GeneralController extends Controller
{
    public function findIframe(request $request)
    {
        $employees = User::allEmployees();
        $employeeSelected = $request->employee_id;

        // carbon::now() harus di +7
        // karena asumsi jam server adalah +7
        // CREATED AT DI INSERT MANUAL

        if (isset($request->startDate) && !empty($request->startDate)){ 
            $todayStart = Carbon::parse($request->startDate)->format('d-m-Y');
            $start_date = Carbon::parse($request->startDate)->format('Y-m-d');
        }else{
            $todayStart = Carbon::now()->addHours(7)->format('d-m-Y');
            $start_date = Carbon::now()->addHours(7)->format('Y-m-d');
        }
        if (isset($request->endDate) && !empty($request->endDate)){ 
            $todayEnd = Carbon::parse($request->endDate)->format('d-m-Y');
            $end_date = Carbon::parse($request->endDate)->format('Y-m-d');
        }else{
            $todayEnd = Carbon::now()->addHours(7)->format('d-m-Y');
            $end_date = Carbon::now()->addHours(7)->format('Y-m-d');
        }

        
        $todayEnd = Carbon::parse($request->endDate)->format('d-m-Y');

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
            ->select('latitude as lat', 'longitude as lng','created_at','created_at_after_timezone','catatan','custom_note', 'is_manual','type')
            ->orderBy('created_at','desc')
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
          $tracker[$key]['is_manual'] = $val['is_manual'];
          $tracker[$key]['type_lacak'] = str_replace('_',' ',$val['type']);
          $tracker[$key]['type'] = $val['catatan'].' '.!empty($val['custom_note'])?$val['custom_note']:'';
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
            $tracker[$curr_index]['type_lacak'] = "gps";
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
                $tracker[$curr_index]['type_lacak'] = "gps";
                $tracker[$curr_index]['created_at'] = Carbon::parse($val->clock_out_after_timezone)->format('d/m/Y H:i:s');
                $tracker[$curr_index]['created_at_after_timezone'] = Carbon::parse($val->clock_out_after_timezone)->format('d/m/Y H:i:s');

                $curr_index++;
            }
        }
        $tracker_sort = $this->array_sort_by_column($tracker, 'created_at',SORT_DESC);
        $arr_tracker=[];
        $arr_label=[];
        foreach ($tracker_sort as $val) {
            array_push($arr_tracker,$val[0]);
            array_push($arr_label,$val['created_at']);
        }
        $tracker_sort = json_encode($tracker_sort);
        $arr_tracker = json_encode($arr_tracker);
        $arr_label = json_encode($arr_label);
        $global = GlobalSetting::first();
        return view('admin.reports.tracker.indexIframe', [
            "employees"=>$employees,
            "employeeSelected"=>$employeeSelected,
            "todayStart"=>$todayStart,
            "todayEnd"=>$todayEnd,
            "tracker"=>$tracker,
            "global"=>$global,
            "tracker_sort"=>$tracker_sort,
            "arr_tracker"=>$arr_tracker,
            "arr_label"=>$arr_label,
        ]);
    }
    public function findIframeFilter(request $request)
    {
        $employees = User::allEmployees();
        $employeeSelected = $request->employee_id;

        // carbon::now() harus di +7
        // karena asumsi jam server adalah +7
        // CREATED AT DI INSERT MANUAL

        if (isset($request->startDate) && !empty($request->startDate)){ 
            $todayStart = Carbon::parse($request->startDate)->format('d-m-Y');
            $start_date = Carbon::parse($request->startDate)->format('Y-m-d');
        }else{
            $todayEnd = Carbon::now()->addHours(7)->format('d-m-Y');
            $start_date = Carbon::now()->addHours(7)->format('Y-m-d');
        }
        if (isset($request->endDate) && !empty($request->endDate)){ 
            $todayEnd = Carbon::parse($request->endDate)->format('d-m-Y');
            $end_date = Carbon::parse($request->endDate)->format('Y-m-d');
        }else{
            $todayEnd = Carbon::now()->addHours(7)->format('d-m-Y');
            $end_date = Carbon::now()->addHours(7)->format('Y-m-d');
        }

        
        $todayEnd = Carbon::parse($request->endDate)->format('d-m-Y');

        // $start_date = Carbon::parse($request->startDate)->format('Y-m-d');
        // $end_date = Carbon::parse($request->endDate)->format('Y-m-d');
        // $request->employee_id = user_id
        $getUser = User::find($request->employee_id);
        $tracker = [];
        // get tracking data
        // dd($start_date);
        $trackers = Tracker::where('user_id', $getUser->id)
            ->where('company_id', $getUser->company_id)
            ->where('type', 'lacak_gps')
            // ->whereBetween('created_at', [$start_date, $end_date])
            ->where(function ($q) use ($start_date,$end_date) {
                // $q->whereDate('created_at','>=',$start_date)
                // ->whereDate('created_at','<=',$end_date);
                // $q->whereDate(\DB::raw('DATE_ADD(created_at, INTERVAL 7 HOUR)'),'>=', $start_date)
                // ->whereDate(\DB::raw('DATE_ADD(created_at, INTERVAL 7 HOUR)'),'<=', $end_date);
                $q->whereDate('created_at_after_timezone','>=', $start_date)
                ->whereDate('created_at_after_timezone','<=', $end_date);
            })
            ->select('latitude as lat', 'longitude as lng','created_at','created_at_after_timezone','catatan','custom_note', 'is_manual', 'type')
            ->orderBy('created_at','desc')
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
          $tracker[$key]['is_manual'] = $val['is_manual'];
          $tracker[$key]['type'] = $val['catatan'].' '.!empty($val['custom_note'])?$val['custom_note']:'';
          $tracker[$key]['type_lacak'] = str_replace('_',' ',$val['type']);
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
            // $tracker[$curr_index][0]['lat'] = (double)$clock_in_latitude;
            // $tracker[$curr_index][0]['lng'] = (double)$clock_in_longitude;
            // $tracker[$curr_index]['type'] = "clock_in";
            // $tracker[$curr_index]['created_at'] = Carbon::parse($val->clock_in_after_timezone)->format('d/m/Y H:i:s');
            // $tracker[$curr_index]['created_at_after_timezone'] = Carbon::parse($val->clock_in_after_timezone)->format('d/m/Y H:i:s');

            // $curr_index++;

            // if (isset($val->clock_out_after_timezone) && !empty($val->clock_out_after_timezone)){
            //     $clock_out_latitude = -7.256215314321651;
            //     $clock_out_longitude = 112.75201350262476;
            //     if (isset($val->clock_out_latitude) && !empty($val->clock_out_latitude)){ 
            //         $clock_out_latitude = (double)$val->clock_out_latitude;
            //     }
            //     if (isset($val->clock_out_longitude) && !empty($val->clock_out_longitude)){ 
            //         $clock_out_longitude = (double)$val->clock_out_longitude;
            //     }
            //     // clock out
            //     $tracker[$curr_index][0]['lat'] = (double)$clock_out_latitude;
            //     $tracker[$curr_index][0]['lng'] = (double)$clock_out_longitude;
            //     $tracker[$curr_index]['type'] = "clock_out";
            //     $tracker[$curr_index]['created_at'] = Carbon::parse($val->clock_out_after_timezone)->format('d/m/Y H:i:s');
            //     $tracker[$curr_index]['created_at_after_timezone'] = Carbon::parse($val->clock_out_after_timezone)->format('d/m/Y H:i:s');

            //     $curr_index++;
            // }
        }
        $tracker_sort = $this->array_sort_by_column($tracker, 'created_at',SORT_DESC);
        $arr_tracker=[];
        $arr_label=[];
        foreach ($tracker_sort as $val) {
            array_push($arr_tracker,$val[0]);
            array_push($arr_label,$val['created_at']);
        }
        $tracker_sort = json_encode($tracker_sort);
        $arr_tracker = json_encode($arr_tracker);
        $arr_label = json_encode($arr_label);
        $global = GlobalSetting::first();
        return view('admin.reports.tracker.indexIframeFilter', [
            "employees"=>$employees,
            "employeeSelected"=>$employeeSelected,
            "todayStart"=>$todayStart,
            "todayEnd"=>$todayEnd,
            "tracker"=>$tracker,
            "global"=>$global,
            "tracker_sort"=>$tracker_sort,
            "arr_tracker"=>$arr_tracker,
            "arr_label"=>$arr_label,
        ]);
    }
    function array_sort_by_column(&$array, $column, $direction = SORT_ASC) {
        $reference_array = array();
    
        foreach($array as $key => $row) {
            $reference_array[$key] = $row[$column];
        }
    
        array_multisort($reference_array, $direction, $array);
        return $array;
    }
}
