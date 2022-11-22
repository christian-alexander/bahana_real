<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Role;
use Froiden\RestAPI\ApiController;
use Illuminate\Support\Facades\Log;
use Modules\RestAPI\Entities\Employee;
use Modules\RestAPI\Http\Requests\Employee\IndexRequest;
use Modules\RestAPI\Http\Requests\Employee\CreateRequest;
use Modules\RestAPI\Http\Requests\Employee\ShowRequest;
use Modules\RestAPI\Http\Requests\Employee\UpdateRequest;
use Modules\RestAPI\Http\Requests\Employee\DeleteRequest;

use App\Attendance;
use App\AttendanceSetting;
use App\ClusterWorkingHour;
use App\Http\Requests\Attendance\StoreAttendance;
use Carbon\Carbon;
use App\Company;
use Illuminate\Support\Facades\DB;
use Froiden\RestAPI\ApiResponse;
use Modules\RestAPI\Entities\Tracker;
// use App\Tracker;
    
use App\Http\Requests\API\APIRequest;
use App\EmployeeDetails;
use App\GeneralSetting;
use App\Notifications\KeluarRadiusNotif;
use App\Notifications\TaskLate;
use App\User;
use App\Task;
use App\Notifications\CustomMessageNotif;
use App\OfficeWifi;

class EmployeeController extends ApiController
{

    protected $model = Employee::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query)
    {
        return $query->visibility();
    }
    public function modifyShow($query)
    {
        return $query->withoutGlobalScope('active');
    }

    public function modifyDelete($query)
    {
        return $query->withoutGlobalScope('active');
    }
    public function modifyUpdate($query)
    {
        return $query->withoutGlobalScope('active');
    }


    public function stored(Employee $employee)
    {
        $employeeDetail = request()->all('employee_detail')['employee_detail'];
        $employee->employeeDetail()->create($employeeDetail);

        // To add custom fields data
        if (request()->get('custom_fields_data')) {
            $employee->updateCustomFieldData(request()->get('custom_fields_data'));
        }


        $employeeRole = Role::where('name','employee')->first();
        $employee->attachRole($employeeRole);

        return $employee;
    }

    public function updating(Employee $employee)
    {

        $data = request()->all('employee_detail')['employee_detail'];
        $data['department_id'] = $data['department']['id'];
        $data['designation_id'] = $data['designation']['id'];
        unset($data['designation']);
        unset($data['department']);
        $employee->employeeDetail()->update($data);
        return $employee;
    }
    
    public function storeAttendance(StoreAttendance $request)
    {
        try {
            $user = auth()->user();
            $this->global = $this->company = Company::withoutGlobalScope('active')->where('id', $user->company_id)->first();
            $this->attendanceSettings = AttendanceSetting::first();
            
            $date = Carbon::createFromFormat($this->global->date_format, $request->date)->format('Y-m-d');
            $clockIn = Carbon::createFromFormat($this->global->time_format, $request->clock_in_time, $this->global->timezone);
            $clockIn->setTimezone('UTC');
            $clockIn = $clockIn->format('H:i:s');
            if ($request->clock_out_time != '') {
                $clockOut = Carbon::createFromFormat($this->global->time_format, $request->clock_out_time, $this->global->timezone);
                $clockOut->setTimezone('UTC');
                $clockOut = $clockOut->format('H:i:s');
                $clockOut = $date . ' ' . $clockOut;
            } else {
                $clockOut = null;
            }
            
            $attendance = Attendance::where('user_id', $user->id)
                ->where(DB::raw('DATE(`clock_in_time`)'), $date)
                ->whereNull('clock_out_time')
                ->first();

            $clockInCount = Attendance::getTotalUserClockIn($date, $user->id);

            if (!is_null($attendance)) {
                $attendance->update([
                    'user_id' => $user->id,
                    'clock_in_time' => $date . ' ' . $clockIn,
                    'clock_in_ip' => $request->clock_in_ip,
                    'clock_out_time' => $clockOut,
                    'clock_out_ip' => $request->clock_out_ip,
                    'working_from' => $request->working_from,
                    'late' => $request->late,
                    'half_day' => $request->half_day
                ]);
            } else {

                // Check maximum attendance in a day
                if ($clockInCount < $this->attendanceSettings->clockin_in_day) {
                    Attendance::create([
                        'user_id' => $user->id,
                        'clock_in_time' => $date . ' ' . $clockIn,
                        'clock_in_ip' => $request->clock_in_ip,
                        'clock_out_time' => $clockOut,
                        'clock_out_ip' => $request->clock_out_ip,
                        'working_from' => $request->working_from,
                        'late' => $request->late,
                        'half_day' => $request->half_day
                    ]);
                } else {
                    // return ApiResponse::make('Sudah check out pada hari ini');
                    // return ApiResponse::make('Anda sudah melakukan Absensi pada hari ini, Cek history absensi untuk melihat absensi masuk/pulang yang sudah dilakukan.', [
                    //     'user' => $user
                    // ]);
                    return ApiResponse::make('Already checkout on this day', [
                        'user' => $user
                    ]);
                }
            }

            return ApiResponse::make('Attendance success', [
                'user' => $user
            ]);
        } catch (Exception $e) {
            // return ApiResponse::make('Attendance failed '.$e->getMessages());
            $exception = new ApiException('Attendance failed '.$e->getMessages(), null, 403, 403, 2001);
            return ApiResponse::exception($exception);
            
        }
            
    }

    public function checkWifi($request){
        $catatan = null;
        // check wifi
        if (isset($request->wifi) && !empty($request->wifi)){
            // check var not null
            if (isset($request->wifi[0]) && !empty($request->wifi[0])){
                // check var not string
                if (!is_string($request->wifi[0])) {
                    if (count($request->wifi[0])>0) {
                        $catatan = "Berada diluar kantor";
                        // ada request wifi
                        foreach ($request->wifi as $wifi) {
                            // check data to office_wifi
                            if (isset($wifi['bssid']) && !empty($wifi['bssid'])){  
                                $checkWifi = OfficeWifi::where('bssid',$wifi['bssid'])->count();
                                // ada 1 aja yang nyantol ke wifi maka dinyatakan berada dikantor
                                if ($checkWifi>0) {
                                    // ada yang cocok dengan office_wifi
                                    $catatan = "Berada dikantor";
                                }
                            }
                        }
                    }
                }
            }
        }else{
            // gak ada request wifi
        }
        return $catatan;
    }
    public function logicStoreGps($request, $user){
        
        // NOTE created_at_after_timezone DAN updated_at_after_timezone TIDAK DIGUNAKAN
        // KARENA CREATED AT YANG DIKIRIM TIDAK DALAM UTC,
        // MELAINKAN UDAH FIX DATE NYA
        $tracker = null;
        $catatan=null;
        $note = (isset($request->note)) ? $request->note : "";
        if ((isset($request->latitude) && !empty($request->latitude)) && (isset($request->longitude) && !empty($request->longitude))){

            // ALREADY CHECKED ALL WORKING FINE 
            // date ambil dari request->created_at dan juga timezone dari request->timezone
            // jika timezone kosong maka di set dafault 7
            // $date = Carbon::now();
            $timezone= 7;
            if (isset($request->timezone) && !empty($request->timezone)){ 
                // set timezone
                $timezone = $request->timezone;
            }
            // created at adalah actual time
            if (isset($request->created_at) && !empty($request->created_at)){
                $date = Carbon::parse($request->created_at);
                // created_at_after_timezone doesnt need to add with timezone, because app send actual datetime
                $created_at_after_timezone=Carbon::parse($request->created_at);
                $updated_at_after_timezone=Carbon::parse($request->created_at);
                if ($timezone>=0) {
                    $created_at = Carbon::parse($request->created_at)->subHours(abs($timezone));
                }else{
                    $created_at = Carbon::parse($request->created_at)->addHours($timezone);
                }
            }else{
                $created_at = Carbon::now();
                if ($timezone>=0) {
                    $date = Carbon::now()->addHours($timezone);
                    $created_at_after_timezone = Carbon::now()->addHours($timezone);
                    $updated_at_after_timezone = Carbon::now()->addHours($timezone);
                }else{
                    $date = $date->subHours(abs($timezone));
                    $created_at_after_timezone = Carbon::now()->subHours(abs($timezone));
                    $updated_at_after_timezone = Carbon::now()->subHours(abs($timezone));
                }
            }
            $catatan = $this->checkWifi($request);
            // get general setting
            $by_pass = false;
            $setting = GeneralSetting::where('company_id',$user->company_id)->first();
            if (isset($setting) && !empty($setting)){ 
                $json_setting = \json_decode($setting->json);
                if (isset($json_setting->bypass_store_gps_cluster) && !empty($json_setting->bypass_store_gps_cluster)){ 
                    $by_pass = true;
                }
            }
            if ($by_pass) {
                $tracker = Tracker::updateOrCreate(
                    [
                      'user_id' => $user->id, 
                    //   'created_at' => $date->format('Y-m-d H:i:00')
                      'created_at' => $created_at
                    ],
                    [
                    'user_id' => $user->id, 
                    'company_id' => $user->company_id,
                    'type' => $request->type,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'timezone' => $timezone,
                    'catatan' => ($catatan) ? $catatan : '',
                    'custom_note' => $note,
                    'is_manual' => ($request->is_manual) ? 1 : 0,
                    // 'created_at' => $date->format('Y-m-d H:i:00')
                    'created_at' => $created_at,
                    'updated_at' => $created_at,
                    'created_at_after_timezone' => $created_at_after_timezone,
                    'updated_at_after_timezone' => $updated_at_after_timezone,
                    ]
                );
            }else{
                // get absen this day
                $date_plus_time_zone = $date->copy()->format('Y-m-d');
                $attendance = Attendance::where('user_id',$user->id)
                    ->whereDate('clock_in_after_timezone', $date_plus_time_zone)
                    ->first();
                // check attendance exist
                if (isset($attendance) && !empty($attendance)){
                    $clock_in_time = Carbon::parse($attendance->clock_in_after_timezone);
                    if (empty($attendance->clock_out_time)) {
                        if ($date>$clock_in_time) {
                            // selama tidak ada clock out time nya maka insert tracker
                            $tracker = Tracker::updateOrCreate(
                            [
                                'user_id' => $user->id, 
                                // 'created_at' => $date->format('Y-m-d H:i:00')
                                'created_at' => $created_at
                            ],
                            [
                                'user_id' => $user->id, 
                                'company_id' => $user->company_id,
                                'type' => $request->type,
                                'latitude' => $request->latitude,
                                'longitude' => $request->longitude,
                                'timezone' => $timezone,
                                'catatan' => ($catatan) ? $catatan : '',
                                'custom_note' => $note,
                                'is_manual' => ($request->is_manual) ? 1 : 0,
                                // 'created_at' => $date->format('Y-m-d H:i:00')
                                'created_at' => $created_at,
                                'updated_at' => $created_at,
                                'created_at_after_timezone' => $created_at_after_timezone,
                                'updated_at_after_timezone' => $updated_at_after_timezone,
                            ]
                            );
                        }
                    }else{
                        // kondisi dimana clock in dan clock out tidak null
                        $clock_out_time = Carbon::parse($attendance->clock_out_after_timezone);
                        if ($date>=$clock_in_time && $date<=$clock_out_time) {
                            // selama tidak ada clock out time nya maka insert tracker
                            $tracker = Tracker::updateOrCreate(
                            [
                                'user_id' => $user->id, 
                                // 'created_at' => $date->format('Y-m-d H:i:00')
                                'created_at' => $created_at
                            ],
                            [
                                'user_id' => $user->id, 
                                'company_id' => $user->company_id,
                                'type' => $request->type,
                                'latitude' => $request->latitude,
                                'longitude' => $request->longitude,
                                'timezone' => $timezone,
                                'catatan' => $catatan,
                                'custom_note' => $note,
                                'is_manual' => ($request->is_manual) ? 1 : 0,
                                // 'created_at' => $date->format('Y-m-d H:i:00')
                                'created_at' => $created_at,
                                'updated_at' => $created_at,
                                'created_at_after_timezone' => $created_at_after_timezone,
                                'updated_at_after_timezone' => $updated_at_after_timezone,
                            ]
                            );
                        }
                    }
                }else{
                    $tracker = null;
                }
            }
                       
        }else{
            // date ambil dari request->created_at dan juga timezone dari request->timezone
            // jika timezone kosong maka di set dafault 7
            $timezone = 7;
            // get general setting
            $by_pass = false;
            $setting = GeneralSetting::where('company_id',$user->company_id)->first();
            if (isset($setting) && !empty($setting)){ 
                $json_setting = \json_decode($setting->json);
                if (isset($json_setting->bypass_store_gps_cluster) && !empty($json_setting->bypass_store_gps_cluster)){ 
                    $by_pass = true;
                }
            }
            if ($by_pass) {
                // insert yang ada di bulk_sync
                if (isset($request->bulk_sync) && !empty($request->bulk_sync)){  
                    $bulk_sync = $request->bulk_sync;
                }else{
                    $bulk_sync = [];
                }
                $arr_tracker =[];
                foreach ($bulk_sync as $val) {
                    // check created at exist
                    if (isset($val['timezone']) && !empty($val['timezone'])){ 
                        // set timezone
                        $timezone = $val['timezone'];
                    }
                    // created at adalah actual time
                    if (isset($val['created_at']) && !empty($val['created_at'])){
                        $date = Carbon::parse($val['created_at']);
                        // created_at_after_timezone doesnt need to add with timezone, because app send actual datetime
                        $created_at_after_timezone=Carbon::parse($val['created_at']);
                        $updated_at_after_timezone=Carbon::parse($val['created_at']);
                        if ($timezone>=0) {
                            $created_at = Carbon::parse($val['created_at'])->subHours(abs($timezone));
                        }else{
                            $created_at = Carbon::parse($val['created_at'])->addHours($timezone);
                        }
                    }else{
                        $created_at = Carbon::now();
                        if ($timezone>=0) {
                            $date = Carbon::now()->addHours($timezone);
                            $created_at_after_timezone = Carbon::now()->addHours($timezone);
                            $updated_at_after_timezone = Carbon::now()->addHours($timezone);
                        }else{
                            $date = $date->subHours(abs($timezone));
                            $created_at_after_timezone = Carbon::now()->subHours(abs($timezone));
                            $updated_at_after_timezone = Carbon::now()->subHours(abs($timezone));
                        }
                    }
                    // check office wifi
                    $catatan = $this->checkWifi((object) $val);
                    // if (isset($val['created_at']) && !empty($val['created_at'])){
                    //     // $created_at = $val['created_at'];
                    //     $date = Carbon::parse($val['created_at']);
                    //     $created_at_after_timezone=Carbon::parse($val['created_at']);
                    //     $updated_at_after_timezone=Carbon::parse($val['created_at']);
                    // }else{
                    //     // $created_at = Carbon::now();
                    //     $date = Carbon::now();
                    //     $created_at_after_timezone=Carbon::now();
                    //     $updated_at_after_timezone=Carbon::now();
                    // }
                    // if (isset($val['timezone']) && !empty($val['timezone'])){ 
                    //     $timezone = $val['timezone'];
                    // }
                    // if ($timezone>=0) {
                    //     $date = $date->addHours($timezone);
                    //     $created_at_after_timezone = $created_at_after_timezone->addHours($timezone);
                    //     $updated_at_after_timezone = $updated_at_after_timezone->addHours($timezone);
                    // }else{
                    //     $date = $date->subHours(abs($timezone));
                    //     $created_at_after_timezone = $created_at_after_timezone->subHours(abs($timezone));
                    //     $updated_at_after_timezone = $updated_at_after_timezone->subHours(abs($timezone));
                    // }
                    // // check date again 
                    // if (isset($val['created_at']) && !empty($val['created_at'])){
                    //     $created_at = Carbon::parse($val['created_at']);
                    //     if ($timezone>=0) {
                    //         $created_at->subHours(abs($timezone));
                    //     }else{
                    //         $created_at->addHours($timezone);
                    //     }
                    // }else{
                    //     $created_at = Carbon::now();
                    // }
                    $tracker = Tracker::updateOrCreate(
                    [
                        'user_id' => $user->id, 
                        // 'created_at' => $date->format('Y-m-d H:i:00')
                        'created_at' => $created_at
                    ],
                    [
                        'user_id' => $user->id, 
                        'company_id' => $user->company_id,
                        'type' => $val['type'],
                        'latitude' => $val['latitude'],
                        'longitude' => $val['longitude'],
                        'timezone' => $timezone,
                        'catatan' => $catatan,
                        'custom_note' => $note,
                        'is_manual' => ($request->is_manual) ? 1 : 0,
                        // 'created_at' => $date->format('Y-m-d H:i:00')
                        'created_at' => $created_at,
                        'updated_at' => $created_at,
                        'created_at_after_timezone' => $created_at_after_timezone,
                        'updated_at_after_timezone' => $updated_at_after_timezone,
                    ]
                    );
                    array_push($arr_tracker, $tracker);
                }
                $tracker =$arr_tracker;
            }else{
                $arr_tracker =[];
                if (isset($request->bulk_sync) && !empty($request->bulk_sync)){  
                    $bulk_sync = $request->bulk_sync;
                }else{
                    $bulk_sync = [];
                }
                foreach ($bulk_sync as $val) {
                    if (isset($val['timezone']) && !empty($val['timezone'])){ 
                        // set timezone
                        $timezone = $val['timezone'];
                    }
                    // created at adalah actual time
                    if (isset($val['created_at']) && !empty($val['created_at'])){
                        $date = Carbon::parse($val['created_at']);
                        // created_at_after_timezone doesnt need to add with timezone, because app send actual datetime
                        $created_at_after_timezone=Carbon::parse($val['created_at']);
                        $updated_at_after_timezone=Carbon::parse($val['created_at']);
                        if ($timezone>=0) {
                            $created_at = Carbon::parse($val['created_at'])->subHours(abs($timezone));
                        }else{
                            $created_at = Carbon::parse($val['created_at'])->addHours($timezone);
                        }
                    }else{
                        $created_at = Carbon::now();
                        if ($timezone>=0) {
                            $date = Carbon::now()->addHours($timezone);
                            $created_at_after_timezone = Carbon::now()->addHours($timezone);
                            $updated_at_after_timezone = Carbon::now()->addHours($timezone);
                        }else{
                            $date = $date->subHours(abs($timezone));
                            $created_at_after_timezone = Carbon::now()->subHours(abs($timezone));
                            $updated_at_after_timezone = Carbon::now()->subHours(abs($timezone));
                        }
                    }
                    // check office wifi
                    $catatan = $this->checkWifi((object) $val);
                    // if (isset($val['created_at']) && !empty($val['created_at'])){
                    //     // $created_at = $val['created_at'];
                    //     $date = Carbon::parse($val['created_at']);
                    //     $created_at_after_timezone=Carbon::parse($val['created_at']);
                    //     $updated_at_after_timezone=Carbon::parse($val['created_at']);
                    // }else{
                    //     // $created_at = Carbon::now();
                    //     $date = Carbon::now();
                    //     $created_at_after_timezone=Carbon::now();
                    //     $updated_at_after_timezone=Carbon::now();
                    // }
                    // if (isset($val['timezone']) && !empty($val['timezone'])){ 
                    //     $timezone = $val['timezone'];
                    // }
                    // if ($timezone>=0) {
                    //     $date = $date->addHours($timezone);
                    //     $created_at_after_timezone = $created_at_after_timezone->addHours($timezone);
                    //     $updated_at_after_timezone = $updated_at_after_timezone->addHours($timezone);
                    // }else{
                    //     $date = $date->subHours(abs($timezone));
                    //     $created_at_after_timezone = $created_at_after_timezone->subHours(abs($timezone));
                    //     $updated_at_after_timezone = $updated_at_after_timezone->subHours(abs($timezone));
                    // }
                    // // check date again 
                    // if (isset($val['created_at']) && !empty($val['created_at'])){
                    //     $created_at = Carbon::parse($val['created_at']);
                    //     if ($timezone>=0) {
                    //         $created_at->subHours(abs($timezone));
                    //     }else{
                    //         $created_at->addHours($timezone);
                    //     }
                    // }else{
                    //     $created_at = Carbon::now();
                    // }
                    $date_plus_time_zone = $date->copy()->format('Y-m-d');
                    $attendance = Attendance::where('user_id',$user->id)
                        ->whereDate('clock_in_after_timezone', $date_plus_time_zone)
                        ->first();
                        // check attendance exist
                        if (isset($attendance) && !empty($attendance)){
                            $clock_in_time = Carbon::parse($attendance->clock_in_after_timezone);
                            // insert yang ada di bulk_sync
                            if (empty($attendance->clock_out_time)) {
                                if ($date>$clock_in_time) {
                                    // selama tidak ada clock out time nya maka insert tracker
                                    $tracker = Tracker::updateOrCreate(
                                    [
                                        'user_id' => $user->id, 
                                        // 'created_at' => $date->format('Y-m-d H:i:00')
                                        'created_at' => $created_at
                                    ],
                                    [
                                        'user_id' => $user->id, 
                                        'company_id' => $user->company_id,
                                        'type' => $val['type'],
                                        'latitude' => $val['latitude'],
                                        'longitude' => $val['longitude'],
                                        'timezone' => $timezone,
                                        'catatan' => $catatan,
                                        'custom_note' => $note,
                                        'is_manual' => ($request->is_manual) ? 1 : 0,
                                        // 'created_at' => $date->format('Y-m-d H:i:00')
                                        'created_at' => $created_at,
                                        'updated_at' => $created_at,
                                        'created_at_after_timezone' => $created_at_after_timezone,
                                        'updated_at_after_timezone' => $updated_at_after_timezone
                                    ]
                                    );
                                    array_push($arr_tracker, $tracker);
                                }
                            }else{
                                // kondisi dimana clock in dan clock out tidak null
                                $clock_out_time = Carbon::parse($attendance->clock_out_after_timezone);
                                if ($date>=$clock_in_time && $date<=$clock_out_time) {
                                    // selama tidak ada clock out time nya maka insert tracker
                                    $tracker = Tracker::updateOrCreate(
                                    [
                                        'user_id' => $user->id, 
                                        // 'created_at' => $date->format('Y-m-d H:i:00')
                                        'created_at' => $created_at
                                    ],
                                    [
                                        'user_id' => $user->id, 
                                        'company_id' => $user->company_id,
                                        'type' => $val['type'],
                                        'latitude' => $val['latitude'],
                                        'longitude' => $val['longitude'],
                                        'timezone' => $timezone,
                                        'catatan' => $catatan,
                                        'custom_note' => $note,
                                        'is_manual' => ($request->is_manual) ? 1 : 0,
                                        // 'created_at' => $date->format('Y-m-d H:i:00')
                                        'created_at' => $created_at,
                                        'updated_at' => $created_at,
                                        'created_at_after_timezone' => $created_at_after_timezone,
                                        'updated_at_after_timezone' => $updated_at_after_timezone
                                    ]
                                    );
                                    array_push($arr_tracker, $tracker);
                                }
                            }
                        }
                    $tracker =$arr_tracker;
                }
            }
        }
        return $tracker;
    }

    
    public function storeGps(APIRequest $request)
    {
        $userLogin = api_user();
        $jangan_lacak_saya = false;
        $additional_field = json_decode($userLogin->employeeDetail->additional_field, true);
        if ($additional_field) {
            if (isset($additional_field['jangan_lacak_saya'])){ 
                if ($additional_field['jangan_lacak_saya']==1) {
                    $jangan_lacak_saya = true;
                }
            }
        }
        
        if ($jangan_lacak_saya) {
            return ApiResponse::make('Track success', [
                'tracker' => []
            ]);
        }

        DB::beginTransaction();
      
        try {
            $user = auth()->user();
          
          	if (isset($request->all()[0]) && !empty($request->all()[0])){
                $request = (object)$request->all()[0];
            }

            // temporarily disabled for heavy load on server
            // $tracker = null;
            $tracker = $this->logicStoreGps($request, $user);

            // if ((isset($request->latitude) && !empty($request->latitude)) && (isset($request->longitude) && !empty($request->longitude))){ 
            //     // date ambil dari request->created_at dan juga timezone dari request->timezone
            //     // jika timezone kosong maka di set dafault 7
            //     $date = Carbon::now();
            //     // $date = Carbon::parse($request->created_at);
            //     // if (isset($request->timezone) && !empty($request->timezone)){ 
            //     //     if ($request->timezone>=0) {
            //     //         $date = $date->addHours(abs($request->timezone));
            //     //     }else{
            //     //           $date = $date->subHours($request->timezone);
            //     //     }
            //     // }else{
            //     //     $date = $date->addHours(7);
            //     // }
            //     // get general setting
            //     $by_pass = false;
            //     $setting = GeneralSetting::where('company_id',$user->company_id)->first();
            //     if (isset($setting) && !empty($setting)){ 
            //         $json_setting = \json_decode($setting->json);
            //         if (isset($json_setting->bypass_store_gps_cluster) && !empty($json_setting->bypass_store_gps_cluster)){ 
            //             $by_pass = true;
            //         }
            //     }
            //     $by_pass =false;
            //     if ($by_pass) {
            //         $tracker = Tracker::updateOrCreate(
            //             [
            //               'user_id' => $user->id, 
            //             //   'created_at' => $date->format('Y-m-d H:i:00')
            //               'created_at' => $request->created_at
            //             ],
            //             [
            //             'user_id' => $user->id, 
            //             'company_id' => $user->company_id,
            //             'latitude' => $request->latitude,
            //             'longitude' => $request->longitude,
            //             // 'created_at' => $date->format('Y-m-d H:i:00')
            //             'created_at' => $request->created_at,
            //             'updated_at' => $request->created_at,
            //             ]
            //         );
            //     }else{
            //         // check hanya store gps ketika jam kantor saja
            //         // dd($date->copy()->format('Y-m-d H:i'));
            //         // $isNowWorkingHour = ClusterWorkingHour::isNowWorkingHour("2021-07-13 01:13",$user->id);

            //         // TODO: GANTI BACA DARI ABSEN MASUK DAN ABSEN PULANG
            //         // get absen this day
            //         $date_plus_time_zone = $date->copy()->addHours(7)->format('Y-m-d');
            //         $attendance = Attendance::where('user_id',$user->id)
            //             // ->whereDate(\DB::raw('DATE_ADD(clock_in_time, INTERVAL 7 HOUR)'), $date_plus_time_zone)
            //             ->whereDate('clock_in_after_timezone', $date_plus_time_zone)
            //             ->first();
            //         // check attendance exist
            //         if (isset($attendance) && !empty($attendance)){
            //             $clock_in_time = Carbon::parse($attendance->clock_in_after_timezone);
            //             $timezone = $attendance->clock_in_timezone;
            //             // now take from $request->created_at
            //             // $now = Carbon::now()->addHours($attendance->clock_in_timezone);
            //             $now = Carbon::parse($request->created_at);
            //             if ($timezone>=0) {
            //                 $now = $now->addHours(abs($timezone));
            //             }else{
            //                   $now = $now->subHours($timezone);
            //             }
            //             if (empty($attendance->clock_out_time)) {
            //                 if ($now>$clock_in_time) {
            //                     // selama tidak ada clock out time nya maka insert tracker
            //                     $tracker = Tracker::updateOrCreate(
            //                     [
            //                         'user_id' => $user->id, 
            //                         // 'created_at' => $date->format('Y-m-d H:i:00')
            //                         'created_at' => $request->created_at
            //                     ],
            //                     [
            //                         'user_id' => $user->id, 
            //                         'company_id' => $user->company_id,
            //                         'latitude' => $request->latitude,
            //                         'longitude' => $request->longitude,
            //                         // 'created_at' => $date->format('Y-m-d H:i:00')
            //                         'created_at' => $request->created_at,
            //                         'updated_at' => $request->created_at,
            //                     ]
            //                     );
            //                 }
            //             }else{
            //                 // kondisi dimana clock in dan clock out tidak null
            //                 $clock_out_time = Carbon::parse($attendance->clock_out_after_timezone);
            //                 if ($now>=$clock_in_time && $now<=$clock_out_time) {
            //                     // selama tidak ada clock out time nya maka insert tracker
            //                     $tracker = Tracker::updateOrCreate(
            //                     [
            //                         'user_id' => $user->id, 
            //                         // 'created_at' => $date->format('Y-m-d H:i:00')
            //                         'created_at' => $request->created_at
            //                     ],
            //                     [
            //                         'user_id' => $user->id, 
            //                         'company_id' => $user->company_id,
            //                         'latitude' => $request->latitude,
            //                         'longitude' => $request->longitude,
            //                         // 'created_at' => $date->format('Y-m-d H:i:00')
            //                         'created_at' => $request->created_at,
            //                         'updated_at' => $request->created_at,
            //                     ]
            //                     );
            //                 }
            //             }
                        
            //         }else{
            //             $tracker = null;
            //         }
            //         // $isNowWorkingHour = ClusterWorkingHour::isNowWorkingHour($date->copy()->format('Y-m-d H:i'),$user->id);
            //         // // dd($isNowWorkingHour);
            //         // if ($isNowWorkingHour) {
            //         //     $tracker = Tracker::updateOrCreate(
            //         //     [
            //         //         'created_at' => $date->format('Y-m-d H:i:00')
            //         //     ],
            //         //     [
            //         //         'user_id' => $user->id, 
            //         //         'company_id' => $user->company_id,
            //         //         'latitude' => $request->latitude,
            //         //         'longitude' => $request->longitude,
            //         //         'created_at' => $date->format('Y-m-d H:i:00')
            //         //     ]
            //         //     );
            //         // }
            //     }
                
            //     /*
            //     $tracker = new Tracker;
            //     $tracker->user_id = $user->id;
            //     $tracker->company_id = $user->company_id;
            //     $tracker->latitude = $request->latitude;
            //     $tracker->longitude = $request->longitude;
                
            //     $tracker->created_at = $date->format('Y-m-d H:i:00');
            //     $tracker->save();
            //     */
                
                
            // }else{
            //     // date ambil dari request->created_at dan juga timezone dari request->timezone
            //     // jika timezone kosong maka di set dafault 7
            //     $date = Carbon::now();
            //     // $date = Carbon::parse($request->created_at);
            //     // if (isset($request->timezone) && !empty($request->timezone)){ 
            //     //     if ($request->timezone>=0) {
            //     //         $date = $date->addHours(abs($request->timezone));
            //     //     }else{
            //     //           $date = $date->subHours($request->timezone);
            //     //     }
            //     // }else{
            //     //     $date = $date->addHours(7);
            //     // }
            //     // get general setting
            //     $by_pass = false;
            //     $setting = GeneralSetting::where('company_id',$user->company_id)->first();
            //     if (isset($setting) && !empty($setting)){ 
            //         $json_setting = \json_decode($setting->json);
            //         if (isset($json_setting->bypass_store_gps_cluster) && !empty($json_setting->bypass_store_gps_cluster)){ 
            //             $by_pass = true;
            //         }
            //     }
            //     $by_pass =true;
            //     if ($by_pass) {
            //         // insert yang ada di bulk_sync
            //         $bulk_sync = $request->bulk_sync;
            //         $arr_tracker =[];
            //         foreach ($bulk_sync as $val) {
            //             $tracker = Tracker::updateOrCreate(
            //             [
            //                 'user_id' => $user->id, 
            //                 // 'created_at' => $date->format('Y-m-d H:i:00')
            //                 'created_at' => $val['created_at']
            //             ],
            //             [
            //                 'user_id' => $user->id, 
            //                 'company_id' => $user->company_id,
            //                 'latitude' => $val['latitude'],
            //                 'longitude' => $val['longitude'],
            //                 // 'created_at' => $date->format('Y-m-d H:i:00')
            //                 'created_at' => $val['created_at'],
            //                 'created_at' => $val['created_at']
            //             ]
            //             );
            //             array_push($arr_tracker, $tracker);
            //         }
            //         $tracker =$arr_tracker;
            //     }else{
            //         $date_plus_time_zone = $date->copy()->addHours(7)->format('Y-m-d');
            //         $attendance = Attendance::where('user_id',$user->id)
            //             // ->whereDate(\DB::raw('DATE_ADD(clock_in_time, INTERVAL 7 HOUR)'), $date_plus_time_zone)
            //             ->whereDate('clock_in_after_timezone', $date_plus_time_zone)
            //             ->first();
            //         // check attendance exist
            //         if (isset($attendance) && !empty($attendance)){
            //             $clock_in_time = Carbon::parse($attendance->clock_in_after_timezone);
            //             $timezone = $attendance->clock_in_timezone;
            //             // now take from $request->created_at
            //             // insert yang ada di bulk_sync
            //             $bulk_sync = $request->bulk_sync;
            //             $arr_tracker =[];
            //             foreach ($bulk_sync as $val) {
            //                 $now = Carbon::parse($val['created_at']);
            //                 if ($timezone>=0) {
            //                     $now = $now->addHours(abs($timezone));
            //                 }else{
            //                     $now = $now->subHours($timezone);
            //                 }
            //                 if (empty($attendance->clock_out_time)) {
            //                     if ($now>$clock_in_time) {
            //                         // selama tidak ada clock out time nya maka insert tracker
            //                         $tracker = Tracker::updateOrCreate(
            //                         [
            //                             'user_id' => $user->id, 
            //                             // 'created_at' => $date->format('Y-m-d H:i:00')
            //                             'created_at' => $val['created_at']
            //                         ],
            //                         [
            //                             'user_id' => $user->id, 
            //                             'company_id' => $user->company_id,
            //                             'latitude' => $val['latitude'],
            //                             'longitude' => $val['longitude'],
            //                             // 'created_at' => $date->format('Y-m-d H:i:00')
            //                             'created_at' => $val['created_at'],
            //                             'updated_at' => $val['created_at']
            //                         ]
            //                         );
            //                         array_push($arr_tracker, $tracker);
            //                     }
            //                 }else{
            //                     // kondisi dimana clock in dan clock out tidak null
            //                     $clock_out_time = Carbon::parse($attendance->clock_out_after_timezone);
            //                     if ($now>=$clock_in_time && $now<=$clock_out_time) {
            //                         // selama tidak ada clock out time nya maka insert tracker
            //                         $tracker = Tracker::updateOrCreate(
            //                         [
            //                             'user_id' => $user->id, 
            //                             // 'created_at' => $date->format('Y-m-d H:i:00')
            //                             'created_at' => $val['created_at']
            //                         ],
            //                         [
            //                             'user_id' => $user->id, 
            //                             'company_id' => $user->company_id,
            //                             'latitude' => $val['latitude'],
            //                             'longitude' => $val['longitude'],
            //                             // 'created_at' => $date->format('Y-m-d H:i:00')
            //                             'created_at' => $val['created_at'],
            //                             'updated_at' => $val['created_at']
            //                         ]
            //                         );
            //                         array_push($arr_tracker, $tracker);
            //                     }
            //                 }
            //             }
            //             $tracker =$arr_tracker;
            //         }else{
            //             $tracker = null;
            //         }
            //     }
            // }
            DB::commit();
            return ApiResponse::make('Track success', [
                'tracker' => $tracker
            ]);
        } catch (Exception $e) {
            DB::rollback();
            // return ApiResponse::make('Attendance failed '.$e->getMessages());
            $exception = new ApiException('Track failed '.$e->getMessages(), null, 403, 403, 2001);
            return ApiResponse::exception($exception);
            
        }
            
    }
    public function storeGpsWithUser(APIRequest $request)
    {
        $userLogin = api_user();
        $jangan_lacak_saya = false;
        $additional_field = json_decode($userLogin->employeeDetail->additional_field, true);
        if ($additional_field) {
            if (isset($additional_field['jangan_lacak_saya'])){ 
                if ($additional_field['jangan_lacak_saya']==1) {
                    $jangan_lacak_saya = true;
                }
            }
        }
        
        if ($jangan_lacak_saya) {
            return ApiResponse::make('Track success', [
                'tracker' => []
            ]);
        }
        try {
            if (!isset($request->user_id) && empty($request->user_id)){
                return ApiResponse::make('user_id required', [
                    'tracker' => []
                ]);
            }
            $user = User::find($request->user_id);
            if (!isset($user) && empty($user)){ 
                return ApiResponse::make('User not found', [
                    'tracker' => []
                ]);
            }
          
          	if (isset($request->all()[0]) && !empty($request->all()[0])){
                $request = (object)$request->all()[0];
            }
          
            $tracker = null;
            if ((isset($request->latitude) && !empty($request->latitude)) && (isset($request->longitude) && !empty($request->longitude))){ 
             $date = Carbon::now();
                // $checkTracker = Tracker::where('user_id', $user->id)
                //     ->where('company_id', $user->company_id)
                //     ->where('created_at',$date->copy()->format('Y-m-d H:i:s'))
                //     ->count();
                // if ($checkTracker==0) {
                //     $tracker = new Tracker;
                //     $tracker->user_id = $user->id;
                //     $tracker->company_id = $user->company_id;
                //     $tracker->latitude = $request->latitude;
                //     $tracker->longitude = $request->longitude;
                    
                //     $tracker->created_at = $date->copy()->format('Y-m-d H:i:s');
                //     $tracker->save();
                // }
                //  $tracker = Tracker::updateOrCreate(
                //    [
                //      'latitude' => $request->latitude,
                //      'longitude' => $request->longitude,
                //      'created_at' => $date->format('Y-m-d H:i:00')
                //    ],
                //    [
                //      'user_id' => $user->id, 
                //      'company_id' => $user->company_id,
                //      'latitude' => $request->latitude,
                //      'longitude' => $request->longitude,
                //      'created_at' => $date->format('Y-m-d H:i:00')
                //    ]
                //  );
                // get general setting
                $by_pass = false;
                $setting = GeneralSetting::where('company_id',$user->company_id)->first();
                if (isset($setting) && !empty($setting)){ 
                    $json_setting = \json_decode($setting->json);
                    if (isset($json_setting->bypass_store_gps_cluster) && !empty($json_setting->bypass_store_gps_cluster)){ 
                        $by_pass = true;
                    }
                }
                if ($by_pass) {
                    $tracker = Tracker::updateOrCreate(
                        [
                          'user_id' => $user->id, 
                          'created_at' => $date->format('Y-m-d H:i:00')
                        ],
                        [
                        'user_id' => $user->id, 
                        'company_id' => $user->company_id,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude,
                        'created_at' => $date->format('Y-m-d H:i:00')
                        ]
                    );
                }else{
                    // check hanya store gps ketika jam kantor saja
                    // dd($date->copy()->format('Y-m-d H:i'));
                    // $isNowWorkingHour = ClusterWorkingHour::isNowWorkingHour("2021-07-13 01:13",$user->id);
                    $isNowWorkingHour = ClusterWorkingHour::isNowWorkingHour($date->copy()->format('Y-m-d H:i'),$user->id);
                    // dd($isNowWorkingHour);
                    if ($isNowWorkingHour) {
                        $tracker = Tracker::updateOrCreate(
                        [
                          'user_id' => $user->id,
                          'created_at' => $date->format('Y-m-d H:i:00')
                        ],
                        [
                            'user_id' => $user->id, 
                            'company_id' => $user->company_id,
                            'latitude' => $request->latitude,
                            'longitude' => $request->longitude,
                            'created_at' => $date->format('Y-m-d H:i:00')
                        ]
                        );
                    }
                }
                
                /*
                $tracker = new Tracker;
                $tracker->user_id = $user->id;
                $tracker->company_id = $user->company_id;
                $tracker->latitude = $request->latitude;
                $tracker->longitude = $request->longitude;
                
                $tracker->created_at = $date->format('Y-m-d H:i:00');
                $tracker->save();
                */
                
                
            }else{
                $tracker =null;
            }

            return ApiResponse::make('Track success', [
                'tracker' => $tracker
            ]);
        } catch (Exception $e) {
            // return ApiResponse::make('Attendance failed '.$e->getMessages());
            $exception = new ApiException('Track failed '.$e->getMessages(), null, 403, 403, 2001);
            return ApiResponse::exception($exception);
            
        }
            
    }
  
    
    public function notifyAtasanKeluarRadius(APIRequest $request)
    {
        try {
            $flagErrorMail = false;
            $user = auth()->user();
          
          	
            //notif atasan
            $loginEmployee = EmployeeDetails::where('user_id', '=', $user->id)->first();
            $json = json_decode($loginEmployee->permission_require);
            if(isset($json[0]) && !empty($json[0])){
              $atasan = User::find($json[0]);
            try {
                $atasan->notify(new KeluarRadiusNotif($user));
            } catch (\Throwable $th) {
                $flagErrorMail = true;
            }
            //   $atasan->notify(new KeluarRadiusNotif($user));
              //$task = Task::find(23);
              //$atasan->notify(new TaskLate($task, 7));
              
            }

            if ($flagErrorMail) {
                return ApiResponse::make('Notify atasan success, Email error silahkan hubungi developer', [
                ]);
            }else{
                return ApiResponse::make('Notify atasan success', [
                ]);
            }
        } catch (Exception $e) {
            // return ApiResponse::make('Attendance failed '.$e->getMessages());
            $exception = new ApiException('Notify atasan failed '.$e->getMessages(), null, 403, 403, 2001);
            return ApiResponse::exception($exception);
            
        }
            
    }
  
    
    public function editNotificationSetting(APIRequest $request)
    {
        try {
            $user = auth()->user();
            $this->global = $this->company = Company::withoutGlobalScope('active')->where('id', $user->company_id)->first();
          
          	$bawahan = User::find($request->bawahan_user_id);
          	$employee = EmployeeDetails::where('user_id', '=', $bawahan->id)->first();
          
          	if(isset($request->no_notification_start_date) && isset($request->no_notification_start_time) && isset($request->no_notification_end_date) && isset($request->no_notification_end_time)){
              
              $no_notification_start_date = Carbon::createFromFormat($this->global->date_format, $request->no_notification_start_date)->format('Y-m-d');
              $no_notification_start_time = Carbon::createFromFormat($this->global->time_format, $request->no_notification_start_time, $this->global->timezone);
              $no_notification_start_time->setTimezone('UTC');
              $no_notification_start_time = $no_notification_start_time->format('H:i:s');

              $limitTime = date("H:i:s", strtotime("17:00:00"));
              if($no_notification_start_time > $limitTime){
                $no_notification_start_date = date("Y-m-d", strtotime($no_notification_start_date." -1 DAY"));
              }
              $no_notification_start_datetime = $no_notification_start_date." ".$no_notification_start_time;



              $no_notification_end_date = Carbon::createFromFormat($this->global->date_format, $request->no_notification_end_date)->format('Y-m-d');
              $no_notification_end_time = Carbon::createFromFormat($this->global->time_format, $request->no_notification_end_time, $this->global->timezone);
              $no_notification_end_time->setTimezone('UTC');
              $no_notification_end_time = $no_notification_end_time->format('H:i:s');

              $limitTime = date("H:i:s", strtotime("17:00:00"));
              if($no_notification_end_time > $limitTime){
                $no_notification_end_date = date("Y-m-d", strtotime($no_notification_end_date." -1 DAY"));
              }
              $no_notification_end_datetime = $no_notification_end_date." ".$no_notification_end_time;


              $employee->no_notification_start = $no_notification_start_datetime;
              $employee->no_notification_end = $no_notification_end_datetime;
              $employee->no_notification_updated_by = $user->id;
              $employee->no_notification_updated_at = date("Y-m-d H:i:s", strtotime("NOW"));
            
            }
          
          	if(isset($request->no_notification)){
              $employee->no_notification = $request->no_notification;
              $employee->no_notification_updated_by = $user->id;
              $employee->no_notification_updated_at = date("Y-m-d H:i:s", strtotime("NOW"));
              
            }
			$employee->save();

            return ApiResponse::make('Edit Notification success', [
              "payload" => $request->all(),
              "employee" => $employee
            ]);
        } catch (Exception $e) {
            // return ApiResponse::make('Attendance failed '.$e->getMessages());
            $exception = new ApiException('Edit Notification failed '.$e->getMessages(), null, 403, 403, 2001);
            return ApiResponse::exception($exception);
            
        }
            
    }
  
    
    public function notifyAtasanCustomMessage(APIRequest $request)
    {
        try {
            $flagErrorMail = false;
            $user = auth()->user();
          
          	
            //notif atasan
            $loginEmployee = EmployeeDetails::where('user_id', '=', $user->id)->first();
            $json = json_decode($loginEmployee->permission_require);
            if(isset($json[0]) && !empty($json[0])){
              $atasan = User::find($json[0]);
            try {
                $atasan->notify(new CustomMessageNotif($user, $request->message, "NOTIF-ATASAN",$atasan));
            } catch (\Throwable $th) {
                $flagErrorMail = true;
            }
            //   $atasan->notify(new CustomMessageNotif($user, $request->message, "NOTIF-ATASAN"));
              //$task = Task::find(23);
              //$atasan->notify(new TaskLate($task, 7));
              
            }

            if ($flagErrorMail) {
                return ApiResponse::make('Custom Notif atasan success, Email error silahkan hubungi developer', [
                ]);
            }else{
                return ApiResponse::make('Custom Notif atasan success', [
                ]);
            }
        } catch (Exception $e) {
            // return ApiResponse::make('Attendance failed '.$e->getMessages());
            $exception = new ApiException('Custom Notif atasan failed '.$e->getMessages(), null, 403, 403, 2001);
            return ApiResponse::exception($exception);
            
        }
            
    }

}
