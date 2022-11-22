<?php

namespace App;

use App\Http\Controllers\Admin\ClusterWorkingHourController;
use App\Observers\AttendanceObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class Attendance extends BaseModel
{
    protected $dates = ['clock_in_time', 'clock_out_time','clock_in_after_timezone','clock_out_after_timezone'];
    protected $appends = ['clock_in_date'];
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::observe(AttendanceObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function getClockInDateAttribute()
    {
        $global = Company::withoutGlobalScope('active')->where('id', Auth::user()->company_id)->first();
        return $this->clock_in_time->timezone($global->timezone)->toDateString();
    }

    public static function attendanceByDate($date) {
        DB::statement("SET @attendance_date = '$date'");
        return User::withoutGlobalScope('active')
        ->leftJoin(
                'attendances', function ($join) use ($date) {
                    $join->on('users.id', '=', 'attendances.user_id')
                        ->where(DB::raw('DATE(attendances.clock_in_time)'), '=', $date)
                        ->whereNull('attendances.clock_out_time');
                }
            )
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->leftJoin('employee_details', 'employee_details.user_id', '=', 'users.id')
            ->leftJoin('designations', 'designations.id', '=', 'employee_details.designation_id')
            ->where('roles.name', '<>', 'client')
            ->select(
                DB::raw("( select count('atd.id') from attendances as atd where atd.user_id = users.id and DATE(atd.clock_in_time)  =  '".$date."' and DATE(atd.clock_out_time)  =  '".$date."' ) as total_clock_in"),
                DB::raw("( select count('atdn.id') from attendances as atdn where atdn.user_id = users.id and DATE(atdn.clock_in_time)  =  '".$date."' ) as clock_in"),
                'users.id',
                'users.name',
                'attendances.clock_in_ip',
                'attendances.clock_in_time',
                'attendances.clock_out_time',
                'attendances.late',
                'attendances.half_day',
                'attendances.working_from',
                'users.image',
                'designations.name as designation_name',
                DB::raw('@attendance_date as atte_date'),
                'attendances.id as attendance_id'
            )
            ->groupBy('users.id')
            ->orderBy('users.name', 'asc');
    }
    public static function attendanceByUserDate($userid, $date)
    {
        DB::statement("SET @attendance_date = '$date'");
        return User::withoutGlobalScope('active')
            ->leftJoin(
                'attendances',
                function ($join) use ($date) {
                    $join->on('users.id', '=', 'attendances.user_id')
                        ->where(DB::raw('DATE(attendances.clock_in_after_timezone)'), '=', $date);
                        // ->whereNull('attendances.clock_out_after_timezone');
                }
            )
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->leftJoin('employee_details', 'employee_details.user_id', '=', 'users.id')
            ->leftJoin('designations', 'designations.id', '=', 'employee_details.designation_id')
            ->where('roles.name', '<>', 'client')
            ->select(
                DB::raw("( select count('atd.id') from attendances as atd where atd.user_id = users.id and DATE(atd.clock_in_after_timezone)  =  '" . $date . "' and DATE(atd.clock_out_after_timezone)  =  '" . $date . "' ) as total_clock_in"),
                DB::raw("( select count('atdn.id') from attendances as atdn where atdn.user_id = users.id and DATE(atdn.clock_in_after_timezone)  =  '" . $date . "' ) as clock_in"),
                'users.id',
                'users.name',
                'attendances.clock_in_ip',
                'attendances.clock_in_time',
                'attendances.clock_in_after_timezone',
                'attendances.clock_out_time',
                'attendances.clock_out_after_timezone',
                'attendances.late',
                'attendances.half_day',
                'attendances.working_from',
                'attendances.clock_in_timezone',
                'attendances.clock_out_timezone',
                'designations.name as designation_name',
                'users.image',
                DB::raw('@attendance_date as atte_date'),
                'attendances.id as attendance_id'
            )
            ->where('users.id', $userid)->first();
    }

    public static function attendanceDate($date) {

        return User::with(['attendance' => function ($q) use ($date) {
            $q->where(DB::raw('DATE(attendances.clock_in_time)'), '=', $date);
        }])
        ->withoutGlobalScope('active')
        ->join('role_user', 'role_user.user_id', '=', 'users.id')
        ->join('roles', 'roles.id', '=', 'role_user.role_id')
        ->leftJoin('employee_details', 'employee_details.user_id', '=', 'users.id')
        ->leftJoin('designations', 'designations.id', '=', 'employee_details.designation_id')
        ->where('roles.name', '<>', 'client')
        ->select(
            'users.id',
            'users.name',
            'users.image',
            'designations.name as designation_name'
        )
        ->groupBy('users.id')
        ->orderBy('users.name', 'asc');
    }

    public static function attendanceHolidayByDate($date) {
        $holidays = Holiday::all();
        $user =  User::leftJoin(
                'attendances', function ($join) use ($date) {
                    $join->on('users.id', '=', 'attendances.user_id')
                        ->where(DB::raw('DATE(attendances.clock_in_time)'), '=', $date);
                }
            )
            ->withoutGlobalScope('active')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->leftJoin('employee_details', 'employee_details.user_id', '=', 'users.id')
            ->leftJoin('designations', 'designations.id', '=', 'employee_details.designation_id')
            ->where('roles.name', '<>', 'client')
            ->select(
                'users.id',
                'users.name',
                'attendances.clock_in_ip',
                'attendances.clock_in_time',
                'attendances.clock_out_time',
                'attendances.late',
                'attendances.half_day',
                'attendances.working_from',
                'users.image',
                'designations.name as job_title',
                'attendances.id as attendance_id'
            )
            ->groupBy('users.id')
            ->orderBy('users.name', 'asc')
        ->union($holidays)
        ->get();
        return $user;
    }

    public static function userAttendanceByDate($startDate, $endDate, $userId) {
        return Attendance::join('users', 'users.id', '=', 'attendances.user_id')
            ->where(DB::raw('DATE(attendances.clock_in_after_timezone)'), '>=', $startDate)
            ->where(DB::raw('DATE(attendances.clock_in_after_timezone)'), '<=', $endDate)
            ->where('attendances.user_id', '=', $userId)
            ->orderBy('attendances.id', 'desc')
            ->select('attendances.*', 'users.*', 'attendances.id as aId')
            ->get();
    }

    public static function countDaysPresentByUser($startDate, $endDate, $userId){
        $totalPresent = DB::select('SELECT count(DISTINCT DATE(attendances.clock_in_after_timezone) ) as presentCount from attendances where DATE(attendances.clock_in_after_timezone) >= "' . $startDate . '" and DATE(attendances.clock_in_after_timezone) <= "' . $endDate . '" and user_id="' . $userId . '" ');
        return $totalPresent = $totalPresent[0]->presentCount;
    }

    public static function countDaysLateByUser($startDate, $endDate, $userId){
        $totalLate = DB::select('SELECT count(DISTINCT DATE(attendances.clock_in_after_timezone) ) as lateCount from attendances where DATE(attendances.clock_in_after_timezone) >= "' . $startDate . '" and DATE(attendances.clock_in_after_timezone) <= "' . $endDate . '" and user_id="' . $userId . '" and late = "yes" ');
        return $totalLate = $totalLate[0]->lateCount;
    }

    public static function countHalfDaysByUser($startDate, $endDate, $userId){
        return Attendance::where(DB::raw('DATE(attendances.clock_in_after_timezone)'), '>=', $startDate)
            ->where(DB::raw('DATE(attendances.clock_in_after_timezone)'), '<=', $endDate)
            ->where('user_id', $userId)
            ->where('half_day', 'yes')
            ->count();
    }

    // Get User Clock-ins by date
    public static function getTotalUserClockIn($date, $userId){
        return Attendance::where(DB::raw('DATE(attendances.clock_in_after_timezone)'), '>=', $date)
            ->where(DB::raw('DATE(attendances.clock_in_after_timezone)'), '<=', $date)
            ->where('user_id', $userId)
            ->count();
    }

    // Attendance by User and date
    public static function attedanceByUserAndDate($date, $userId){
        return Attendance::where('user_id', $userId)
            ->where(DB::raw('DATE(attendances.clock_in_after_timezone)'), '=', "$date")->get();
    }

    public static function getTimezoneByUserId($user_id){
        // get timezone
        $timezone=7;
        $timezone_attendance = Attendance::where('user_id', $user_id)->orderBy('id','desc')->first();
        if ($timezone_attendance) {
            $timezone = $timezone_attendance->clock_in_timezone;
        }
        return $timezone;
    }
  
    public static function getLaporanKehadiran($start_date, $end_date,$department, $user_id, $subcompany,$wilayah,$office_id, $libur='no'){
        $start_date = Carbon::createFromFormat('d-m-Y', $start_date);
        $end_date = Carbon::createFromFormat('d-m-Y', $end_date);
        $employees = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->join('employee_details','employee_details.user_id','users.id')
            ->leftJoin('cluster_working_hours as cwh', 'cwh.id', 'employee_details.cluster_working_hour_id')
            ->select(
                'users.id', 
                'users.name', 
                'users.email', 
                'users.created_at', 
                'users.company_id',
                'cwh.type as cluster_type',
                'cwh.json as cluster_json',
                'cwh.start_hour as cluster_start_hour',
                'cwh.end_hour as cluster_end_hour',
                'employee_details.designation_id',
                'users.company_id'
            )
            ->where('roles.name', '<>', 'client')
            ->where('employee_details.department_id', $department)
            ->where('employee_details.sub_company_id', $subcompany)
            ->where('employee_details.wilayah_id', $wilayah);
            if (!empty($office_id)) {
                $employees = $employees->where('employee_details.office_id',$office_id);
            }
            $employees->groupBy('users.id');

        // where by department id
        // if ($department != '0') {
        //     $employees = $employees->where('employee_details.department_id', $department);
        // }
        if ($user_id == '0') {
            $employees = $employees->get();
        } else {
            $employees = $employees->where('users.id', $user_id)->get();
        }
        // get all type cuti
        $getTypeCuti = TipeCuti::where('company_id', \Auth::user()->company_id)
        ->get();
        
        // ->toArray();
        foreach ($employees as $employee) {
            // if ($employee->user_id=='177') {
            //     dd($employee);
            // }
            // get diff start date and end date in day
            $diff = $start_date->copy()->diffInDays($end_date);
            $terlambat = 0;
            // $ijin_terlambat = 0;
            // $ijin_pulang_awal = 0;
            // $ijin_tidak_masuk = 0;
            $pulang_tidak_absen = 0;
            // $sakit = 0;
            $tidak_hadir = 0; // alhpa
            $hadir = 0; 
            $ijin = 0;
            $cuti = 0;
            $dinas_sementara = 0;
            $dinas_luar_kota = 0;
            $wfh = 0;
            $wfh_with_dinas = 0;
            $wfh_with_dinas_weekend = 0;
            $wfo = 0;
            $wfo_weekend = 0;
            $gps = 0;
            $jabatan = Designation::find($employee->designation_id);
            $test_arr =[];
            $test_alpha_hadir =[];
            $arr_type_cuti =[];
            $arr_type_ijin =[];
            $arr_test =[];
            $arr_test_date =[];
          	$dateLateForEmployee = [];
            $akumulasiLembur = 0;
            $arr_wfh_cek =[];
            for ($i = 0; $i <= $diff; $i++) {
                $start_date_formated = $start_date->copy()->addDays($i)->format('Y-m-d');
                // $start_date_formated_add_7_hours = $start_date->copy()->addDays($i)->addHours(7);
                // if ($start_date_formated =='2021-05-20') {
                //     dd($start_date->copy()->addDays($i),$start_date_formated_add_7_hours);
                // }
                // if ($i=2) {
                //     dd($start_date_formated,'asd')
                // }
                
                $end_date_formated = $end_date->copy()->addDays($i)->format('Y-m-d');
                // get attendance by user id and company id
                $getAttendance = Attendance::leftJoin('attendances as at', function($q)
                    {
                        $q->on('at.id', '=', 'attendances.id')
                            ->where('at.working_from','WFH');
            
            
                    })
                    ->leftJoin('attendances as at2', function($q)
                    {
                        $q->on('at2.id', '=', 'attendances.id')
                            ->where('at2.working_from','LACAKGPS');
            
            
                    })
                    ->where('attendances.user_id', $employee->id)
                    ->where('attendances.company_id', $employee->company_id)
                    // ->whereDate(\DB::raw('DATE_ADD(attendances.clock_in_time, INTERVAL 7 HOUR)'), $start_date_formated)
                    ->whereDate('attendances.clock_in_after_timezone', Carbon::parse($start_date_formated)->format('Y-m-d'))
                    // ->whereDate('attendances.clock_in_after_timezone', '2021-07-14')
                    ->select('attendances.*',DB::raw('count(at.id) as wfh'),DB::raw('count(at2.id) as gps'))
                    ->groupBy('attendances.id')
                    ->limit(1)
                    ->get();
                // if ($start_date_formated == "2021-07-17") {
                //     dd($getAttendance,$employee->id,$start_date_formated);
                // }
                
                // pengecekan office open days dari setting attendance
                $setting = AttendanceSetting::where('company_id', company()->id)->first();
                $office_open_days = json_decode($setting->office_open_days);
                $dayW = date('w', strtotime($start_date_formated));
                array_push($arr_test,$start_date_formated);
                $is_weekend_date = Carbon::parse($start_date_formated);
                // ->isWeekend()
                // check terlambat
                foreach ($getAttendance as $attendance) {
                    // sum wfh
                    $wfh+=$attendance->wfh;
                    // // sum gps
                    $gps+=$attendance->gps;

                    // check wfo
                    // $check_office = $
                    // check attendance bukan wfh atau gps
                    
                    if ($attendance->working_from !='WFH' && $attendance->working_from !='LACAKGPS') {

                        // check working from dan clock_out_from sama(=)
                        if ($attendance->working_from == $attendance->clock_out_from) {
                            
                            // jika sama berarti wfo
                            if ($is_weekend_date->isWeekend()) {
                                // when true then add to wfo_weekend
                                $wfo_weekend++;
                            }else{
                                $wfo++;
                            }
                            

                        }else{
                            // tidak sama
                            // check clock_out_from == null /empty
                            if (empty($attendance->clock_out_from)) {
                                // jika null anggap wfo
                                if ($is_weekend_date->isWeekend()) {
                                    // when true then add to wfo_weekend
                                    $wfo_weekend++;
                                }else{
                                    $wfo++;
                                }
                            }else{
                                // case: working from office, clockout from home
                                // will be counted as wfo
                                if ($is_weekend_date->isWeekend()) {
                                    // when true then add to wfo_weekend
                                    $wfo_weekend++;
                                }else{
                                    $wfo++;
                                }
                            }
                        }
                        

                    }else{
                        // check apakah checkin wfh > checkout wfo
                        // masuk sini adalah WFH dan LACAKGPS
                        if ($attendance->working_from=='WFH' && ($attendance->clock_out_from!='WFH' && $attendance->clock_out_from!='LACAKGPS')) {
                            // berarti working_from WFH dan clock_out_from wfo
                            // check apakah memiliki dinas sementara
                            $check_dinas_sementara = Leave::join('leave_types as lt','lt.id','leaves.leave_type_id')
                                ->where('lt.type_name','Dinas sementara')
                                ->where('leaves.user_id',$attendance->user_id)
                                ->where('leaves.masking_status','done')
                                ->where(function($query) use ($start_date_formated,$end_date_formated){
                                    $query->whereDate('leaves.leave_date','<=',Carbon::parse($start_date_formated)->format('Y-m-d'))
                                        ->whereDate('leaves.leave_date_end','>=',Carbon::parse($start_date_formated)->format('Y-m-d'));
                                })->get();
                            if (count($check_dinas_sementara)>0) {
                                // dd($check_dinas_sementara);
                                // ada dinas sementara
                                // $wfo++;
                                if ($is_weekend_date->isWeekend()) {
                                    // when true then add to wfo_weekend
                                    $wfo_weekend++;
                                }else{
                                    $wfo++;
                                }
                                // wfh dikurangin 1 
                                $wfh--;
                            }else{
                                // do nothing
                            }
                        }
                        elseif($attendance->working_from=='WFH' && $attendance->clock_out_from=='WFH'){
                            // ketika working_from WFH dan clock_out_from WFH
                            // check dinas sementara
                            $check_dinas_sementara = Leave::join('leave_types as lt','lt.id','leaves.leave_type_id')
                                ->where('lt.type_name','Dinas sementara')
                                ->where('leaves.user_id',$attendance->user_id)
                                ->where('leaves.masking_status','done')
                                ->where(function($query) use ($start_date_formated,$end_date_formated){
                                    $query->whereDate('leaves.leave_date','<=',Carbon::parse($start_date_formated)->format('Y-m-d'))
                                        ->whereDate('leaves.leave_date_end','>=',Carbon::parse($start_date_formated)->format('Y-m-d'));
                                })->get();
                            if (count($check_dinas_sementara)>0) {
                                // ada dinas sementara
                                // $wfo++;
                                
                                // wfh dikurangin 1 
                                

                                // check is weekend
                                if ($is_weekend_date->isWeekend()) {
                                    $wfh_with_dinas_weekend++;
                                }else{
                                    $wfh_with_dinas++;
                                }
                                $wfh--;
                            }
                        }
                    }
                    // $attendance_clock_in_time = date('H:i:s', strtotime($attendance->clock_in_time . ' +7 hours'));
                    $attendance_clock_in_time = Carbon::parse($attendance->clock_in_after_timezone)->format('H:i:s');

                    // no = hari libur tidak dihitung
                    if ($libur=='no') {
                        // jika pilih ya
                        if(in_array($dayW, $office_open_days)){
                            // hari kerja
                        }else{
                            // hari libur
                            continue;
                        }
                    }

                    // get cluster from attendance
                    $cluster_meta = json_decode($attendance->cluster_meta, true);
                    // if ($employee->cluster_type == 'daily') {
                    if ($cluster_meta['type'] == 'daily') {
                        /* code lama pengecekan hari telat salah
                      	// check day of today
                        $dayOfToday = getDayInIndonesia(Carbon::now()->format('l'));
    
                        // get json
                        $json_cluster = json_decode($employee->cluster_json, true);
                        $office_start_time = date('H:i:s', strtotime($json_cluster[$dayOfToday]['jam_masuk']));
                      	*/ 
                      
                        // check day of today
                        //$dayOfToday = getDayInIndonesia(Carbon::now()->format('l'));
    					// $dayOfToday2 = getDayInIndonesia(date('l', strtotime($attendance->clock_in_time . ' +7 hours')));
    					$dayOfToday2 = getDayInIndonesia(Carbon::parse($attendance->clock_in_after_timezone)->format('l'));
                        // get json
                        // $json_cluster = json_decode($employee->cluster_json, true);
                        // this function to give default value when schedule is empty
                        $clusterController = new ClusterWorkingHourController;
                        $json_cluster = $clusterController->getJsonDaily($cluster_meta['json'], true);
                      
                      	if(isset($json_cluster[$dayOfToday2]['jam_masuk']))
                          $office_start_time = date('H:i:s', strtotime($json_cluster[$dayOfToday2]['jam_masuk']));
                      	else
                          $office_start_time = date('H:i:s', strtotime($json_cluster["senin"]['jam_masuk']));
                          
                    }else{
                        $office_start_time = date('H:i:s', strtotime($cluster_meta['start_hour']));
                    }
                    if ($office_start_time < $attendance_clock_in_time && $jabatan->check_late == 1) {
                        $carbonAttendance_clock_in_time = Carbon::parse($attendance_clock_in_time)->format('Y-m-d');
                        // check have ijin terlambat or not
                        $checkIjinTerlambat = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
                            ->join('leave_ijins as li', 'li.leave_id', 'leaves.id')
                            ->where('leaves.company_id', $employee->company_id)
                            ->where('leaves.user_id', $employee->id)
                            ->where('lt.type_name', 'Ijin')
                            ->where('li.alasan_ijin', 'datang-terlambat')
                            ->whereIn('leaves.masking_status', ['in progress', 'done'])
                            ->where(function($query) use ($start_date_formated){
                                // $query->whereDate('leave_date','<=',$carbonAttendance_clock_in_time)
                                // ->whereDate('leave_date_end','>=',$carbonAttendance_clock_in_time);
                                $query->whereDate('leaves.leave_date','<=',Carbon::parse($start_date_formated)->format('Y-m-d'))
                                    ->whereDate('leaves.leave_date_end','>=',Carbon::parse($start_date_formated)->format('Y-m-d'));
                            })
                            ->count();
                            // if ($employee->id=='83') {
                            //     dd($carbonAttendance_clock_in_time,$start_date_formated);
                            // }
                            if ($checkIjinTerlambat == 0) {
                                //$terlambat++; code lama ini saja
                              	$to_time = strtotime($attendance_clock_in_time);
                              	$from_time = strtotime($office_start_time);
                              	$late =  round(abs($to_time - $from_time) / 60,2). " minute"." cluster time:".$office_start_time;
                        		// $start_date_formated = date("Y-m-d H:i:s", strtotime($attendance->clock_in_time." +7 hours"));
                        		$start_date_formated_copy = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d H:i:s');
                              	$arrLate["value"] = $start_date_formated_copy;
                              	$arrLate["late"] = $late;
                      			
                              	// $check = date("Y-m-d", strtotime($attendance->clock_in_time." +7 hours"));
                              	$check = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d');
                              	if(!in_array($check, $dateLateForEmployee)){
                                  //kalau sudah terlambat sekali tidak usah dihitung lagi (absen bisa lebih dari sekali)
                                  $terlambat++;
                                //   $dateLateForEmployee[] = date("Y-m-d", strtotime($attendance->clock_in_time." +7 hours"));
                                  $dateLateForEmployee[] = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d');
                                }
                            }
                    }

                    // check pulang tidak absen
                    if($attendance->cron_clock_out == 1){
                        $pulang_tidak_absen++;
                    }else{ //08.02 --- cek lembur ---

                        // if ($employee->cluster_type == 'daily') {
                        if ($cluster_meta['type'] == 'daily') {
                            // $dayOfToday2 = getDayInIndonesia(date('l', strtotime($attendance->clock_in_time . ' +7 hours')));
                            $dayOfToday2 = getDayInIndonesia(Carbon::parse($attendance->clock_in_after_timezone)->format('l'));
                            // get json
                            // $json_cluster = json_decode($employee->cluster_json, true);
                            $clusterController = new ClusterWorkingHourController;
                            $json_cluster = $clusterController->getJsonDaily($cluster_meta['json'], true);
                          
                            if(isset($json_cluster[$dayOfToday2]['jam_pulang']))
                              $office_end_time = date('H:i:s', strtotime($json_cluster[$dayOfToday2]['jam_pulang']));
                            else
                              $office_end_time = date('H:i:s', strtotime($json_cluster["senin"]['jam_pulang']));

                            // $attendance_clock_out_time = date('H:i:s', strtotime($attendance->clock_out_time . ' +7 hours'));
                            $attendance_clock_out_time = date('H:i:s', strtotime($attendance->clock_out_after_timezone));
                            // dd($office_end_time , $attendance_clock_out_time);
                            // if ($office_end_time > $attendance_clock_out_time) {
                            if ($attendance_clock_out_time >$office_end_time) {

                                    // $clock_in_date = date("Y-m-d", strtotime($attendance->clock_in_time." +7 hours"));
                                    $clock_in_date = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d');
                                    $from = date("Y-m-d H:i:s", strtotime($clock_in_date." ".$office_end_time));
                                    // $from_time = strtotime($attendance_clock_out_time);
                                    // $from_time = strtotime($from);
                                    // $to_time = strtotime($attendance_clock_out_time);
                                    // $attendance_clock_out_time_copy = date('Y-m-d H:i:s', strtotime($attendance->clock_out_time . ' +7 hours'));
                                    $attendance_clock_out_time_copy = date('Y-m-d H:i:s', strtotime($attendance->clock_out_after_timezone));
                                    // dd($attendance_clock_out_time_copy, $from);
                                    $diffInMinutes = Carbon::parse($attendance_clock_out_time_copy)->diffInMinutes($from);
                                    // $lembur =  round(abs($to_time - $from_time) / 60,2). " minute"." cluster time:".$office_end_time;
                                    $lembur =  $diffInMinutes. " minute"." cluster time:".$office_end_time;
                                    $akumulasiLembur +=  $diffInMinutes;

                            }
                        }
                    }
                }
                
                // check Ijin Terlambat
                
                // $getIjinTerlambat = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
                //     ->where('leaves.company_id', $employee->company_id)
                //     ->where('leaves.user_id', $employee->id)
                //     ->where('lt.type_name', 'Ijin Terlambat')
                //     ->where('leaves.masking_status', 'done')
                //     ->where(function($query) use ($start_date_formated,$end_date_formated){
                //         $query->whereDate('leaves.leave_date','<=',$start_date_formated)
                //             ->whereDate('leaves.leave_date_end','>=',$start_date_formated);
                //     })
                //     ->count();
                // $ijin_terlambat += $getIjinTerlambat;
                // if (count($getIjinTerlambat) > 0) {
                //     foreach ($getIjinTerlambat as $val) {
                //         if ($start_date->copy()->addDays($i)->format('Y-m-d') >= $val->leave_date->format('Y-m-d') && $start_date->copy()->addDays($i)->format('Y-m-d') <= $val->leave_date_end->format('Y-m-d')) {
                //             $ijin_terlambat++;
                //         }
                //     }
                // }

                // check Ijin Pulang Awal
                // $getIjinPulangAwal = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
                //     ->where('leaves.company_id', $employee->company_id)
                //     ->where('leaves.user_id', $employee->id)
                //     ->where('lt.type_name', 'Ijin Pulang Awal')
                //     ->where('leaves.masking_status', 'done')
                //     ->where(function($query) use ($start_date_formated,$end_date_formated){
                //         $query->whereDate('leaves.leave_date','<=',$start_date_formated)
                //             ->whereDate('leaves.leave_date_end','>=',$start_date_formated);
                //     })
                //     ->count();
                // $ijin_pulang_awal += $getIjinPulangAwal;

                // check Ijin Tidak Masuk
                // $getIjinTidakMasuk = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
                //     ->where('leaves.company_id', $employee->company_id)
                //     ->where('leaves.user_id', $employee->id)
                //     ->where('lt.type_name', 'Ijin Tidak Masuk')
                //     ->where('leaves.masking_status', 'done')
                //     ->where(function($query) use ($start_date_formated,$end_date_formated){
                //         $query->whereDate('leaves.leave_date','<=',$start_date_formated)
                //             ->whereDate('leaves.leave_date_end','>=',$start_date_formated);
                //     })
                //     ->count();
                // $ijin_tidak_masuk += $getIjinTidakMasuk;

                // check Sakit
                // $getSakit = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
                //     ->where('leaves.company_id', $employee->company_id)
                //     ->where('leaves.user_id', $employee->id)
                //     ->where('lt.type_name', 'Sakit')
                //     ->where('leaves.masking_status', 'done')
                //     ->where(function($query) use ($start_date_formated,$end_date_formated){
                //         $query->whereDate('leaves.leave_date','<=',$start_date_formated)
                //             ->whereDate('leaves.leave_date_end','>=',$start_date_formated);
                //     })
                //     ->count();
                // $sakit += $getSakit;

                // check leave ijin
                // if ($employee->id=='83') {
                //     dd($start_date_formated);
                // }
                // get leave yang pending
                $getCountLeavePending = Leave::where('leaves.company_id', $employee->company_id)
                    ->where('leaves.user_id', $employee->id)
                    // ->whereIn('leaves.masking_status', ['in progress', 'done'])
                    ->whereIn('leaves.masking_status', ['pending','in progress'])
                    ->where(function($query) use ($start_date_formated,$end_date_formated){
                        $query->whereDate('leaves.leave_date','<=',Carbon::parse($start_date_formated)->format('Y-m-d'))
                            ->whereDate('leaves.leave_date_end','>=',Carbon::parse($start_date_formated)->format('Y-m-d'));
                    })
                    ->selectRaw('leaves.*')
                    ->count();

                $flag_datang_terlambat=0;
                $flag_pulang_awal=0;
                $flag_keluar_kantor=0;
                $getIjin = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
                    ->leftjoin('leave_ijins as li','li.leave_id','leaves.id')
                    ->where('leaves.company_id', $employee->company_id)
                    ->where('leaves.user_id', $employee->id)
                    ->where('lt.type_name', 'Ijin')
                    // ->whereIn('leaves.masking_status', ['in progress', 'done'])
                    ->whereIn('leaves.masking_status', ['done'])
                    ->where(function($query) use ($start_date_formated,$end_date_formated){
                        $query->whereDate('leaves.leave_date','<=',Carbon::parse($start_date_formated)->format('Y-m-d'))
                            ->whereDate('leaves.leave_date_end','>=',Carbon::parse($start_date_formated)->format('Y-m-d'));
                        // $query->whereDate('leaves.leave_date','<=',Carbon::parse($start_date_formated->copy())->format('Y-m-d'))
                        //     ->whereDate('leaves.leave_date_end','>=',Carbon::parse($start_date_formated->copy())->format('Y-m-d'));
                    })
                    ->selectRaw('leaves.*,li.alasan_ijin')
                    ->get();
                    if (count($getIjin)==0) {
                        // check with cluster
                        $checkCluster =self::checkClockInAndClockOut($employee,Carbon::parse($start_date_formated)->format('Y-m-d'));
                        if (!$checkCluster) {
                            array_push($arr_type_ijin,[
                                'pulang-awal-system' => 1
                            ]);
                            array_push($arr_test_date,Carbon::parse($start_date_formated)->format('Y-m-d'));
                            // dd($arr_test_date);
                        }
                    }
                    // dd($getIjin,$start_date_formated);
                    // dd(Carbon::parse($start_date_formated)->format('Y-m-d'));
                // dd($start_date_formated);
                    // if ($employee->id=='83') {
                    //     dd($start_date_formated,$getIjin);
                    // }
                //check ijin tidak-masuk
                foreach ($getIjin as $val) {
                    if ($val->alasan_ijin=='tidak-masuk') {
                        array_push($arr_type_ijin,[
                            'tidak-masuk' => 1
                        ]);
                    }elseif ($val->alasan_ijin=='sakit') {
                        array_push($arr_type_ijin,[
                            'sakit' => 1
                        ]);
                    }elseif ($val->alasan_ijin=='datang-terlambat') {
                        array_push($arr_type_ijin,[
                            'datang-terlambat' => 1
                        ]);
                        $flag_datang_terlambat=1;
                    }elseif ($val->alasan_ijin=='pulang-awal') {
                        if ($val->approved_by=='system') {
                            array_push($arr_type_ijin,[
                                'pulang-awal-system' => 1
                            ]);
                        }else{
                            array_push($arr_type_ijin,[
                                'pulang-awal' => 1
                            ]);
                        }
                        
                        $flag_pulang_awal=1;
                    }elseif ($val->alasan_ijin=='keluar-kantor') {
                        array_push($arr_type_ijin,[
                            'keluar-kantor' => 1
                        ]);
                        $flag_keluar_kantor=1;
                    }
                }
                // $ijin += $getIjin;
                // "tidak-masuk",
                // "sakit",
                // "datang-terlambat",
                // "pulang-awal",
                // "keluar-kantor"
                // if (count($getIjin) > 0) {
                //     foreach ($getIjin as $val) {
                //         if ($start_date->copy()->addDays($i)->format('Y-m-d') >= $val->leave_date->format('Y-m-d') && $start_date->copy()->addDays($i)->format('Y-m-d') <= $val->leave_date_end->format('Y-m-d')) {
                //             $ijin++;
                //         }
                //     }
                // }
                // dd($start_date_formated);
                // if ($start_date_formated == "2021-03-19") {
                //     dd($office_open_days);
                // }
                $getCuti =0;
                if(in_array($dayW, $office_open_days)){
                    $getCuti = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
                        ->leftjoin('leave_cutis','leave_cutis.leave_id','leaves.id')
                        ->leftjoin('tipe_cutis','tipe_cutis.id','leave_cutis.kategori_cuti')
                        ->where('leaves.company_id', $employee->company_id)
                        ->where('leaves.user_id', $employee->id)
                        ->whereIn('lt.type_name', ['Cuti','Cuti 3 Bulanan','Cuti Custom'])
                        ->whereIn('leaves.masking_status', ['done'])
                        ->where(function($query) use ($start_date_formated,$end_date_formated){
                            $query->whereDate('leaves.leave_date','<=',Carbon::parse($start_date_formated)->format('Y-m-d'))
                                ->whereDate('leaves.leave_date_end','>=',Carbon::parse($start_date_formated)->format('Y-m-d'));
                        })
                        ->selectRaw('leaves.*,tipe_cutis.id as tipe_cutis_id,tipe_cutis.name as tipe_cutis_name')
                        ->count();
                        // ->get();
                    // $cuti += count($getCuti);
                    $cuti += $getCuti;
                    
                }
                // if (count($getCuti) > 0) {
                //     foreach ($getCuti as $val) {
                //         if ($start_date->copy()->addDays($i)->format('Y-m-d') >= $val->leave_date->format('Y-m-d') && $start_date->copy()->addDays($i)->format('Y-m-d') <= $val->leave_date_end->format('Y-m-d')) {
                //             $cuti++;
                //         }
                //     }
                // }

                //
                // if($start_date_formated == '2020-10-15' && $employee->id =='16'){
                //     // dd($getCuti);   
                // }
                // foreach ($getCuti as $val) {
                //     foreach ($getTypeCuti as $typeCuti) {
                //         if ($val->tipe_cutis_id==$typeCuti->id) {
                //             array_push($arr_type_cuti,[
                //                 $val->tipe_cutis_name => 1
                //             ]);
                //         }
                    
                //     }
                // }

                // check leave Dinas sementara
                // $getDinasSementara = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
                //     ->where('leaves.company_id', $employee->company_id)
                //     ->where('leaves.user_id', $employee->id)
                //     ->where('lt.type_name', 'Dinas sementara')
                //     ->whereIn('leaves.masking_status', ['in progress', 'done'])
                //     ->where(function($query) use ($start_date_formated,$end_date_formated){
                //         $query->whereDate('leaves.leave_date','<=',$start_date_formated)
                //             ->whereDate('leaves.leave_date_end','>=',$start_date_formated);
                //     })
                //     ->count();
                //     $dinas_sementara += $getDinasSementara;
                // if (count($getDinasSementara) > 0) {
                //     foreach ($getDinasSementara as $val) {
                //         if ($start_date->copy()->addDays($i)->format('Y-m-d') >= $val->leave_date->format('Y-m-d') && $start_date->copy()->addDays($i)->format('Y-m-d') <= $val->leave_date_end->format('Y-m-d')) {
                //             $dinas_sementara++;
                //         }
                //     }
                // }
                // check leave Dinas Luar Kota
                // $getDinasLuarKota = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
                //     ->where('leaves.company_id', $employee->company_id)
                //     ->where('leaves.user_id', $employee->id)
                //     ->where('lt.type_name', 'Dinas Luar Kota')
                //     ->whereIn('leaves.masking_status', ['in progress', 'done'])
                //     ->where(function($query) use ($start_date_formated,$end_date_formated){
                //         $query->whereDate('leaves.leave_date','<=',$start_date_formated)
                //             ->whereDate('leaves.leave_date_end','>=',$start_date_formated);
                //     })
                //     ->count();
                // $dinas_luar_kota += $getDinasLuarKota;
                // if (count($getDinasLuarKota) > 0) {
                //     foreach ($getDinasLuarKota as $val) {
                //         if ($start_date->copy()->addDays($i)->format('Y-m-d') >= $val->leave_date->format('Y-m-d') && $start_date->copy()->addDays($i)->format('Y-m-d') <= $val->leave_date_end->format('Y-m-d')) {
                //             $dinas_luar_kota++;
                //         }
                //     }
                // }
                // dd($getAttendance);
                // if($start_date_formated == '2020-09-28' && $employee->id =='16'){
                    // $test = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
                    // ->where('leaves.company_id', $employee->company_id)
                    // ->where('leaves.user_id', $employee->id)
                    // ->where('lt.type_name', 'Cuti')
                    // ->where('leaves.masking_status','done')
                    // ->where(function($query) use ($start_date_formated,$end_date_formated){
                    //     $query->whereDate('leaves.leave_date','<=',$start_date_formated)
                    //         ->whereDate('leaves.leave_date_end','>=',$start_date_formated);
                    // })
                    // ->count();
                    // if(
                    //     $getIjin == 0 && 
                    //     $getCuti == 0 && 
                    //     // $getDinasSementara == 0 && 
                    //     // $getDinasLuarKota ==0 && 
                    //     $getIjinTidakMasuk ==0 && 
                    //     $getSakit ==0
                    //     ){
                    //     if (count($getAttendance) == 0) {
                    //         dd('masuk');
                    //     }
                    // }
                // }
                if(
                    count($getIjin) == 0 && 
                    $getCuti == 0  
                    // $getDinasSementara == 0 && 
                    // $getDinasLuarKota ==0 && 
                    // $getIjinTidakMasuk ==0 && 
                    // $getSakit ==0
                    ){
                    if (count($getAttendance) == 0) {
                        if ($libur=='no') {
                            // jika pilih ya
                            if(in_array($dayW, $office_open_days)){
                                    // hari kerja
                                  //pengecekan holiday
                                    $holidayDate = date('Y-m-d', strtotime($start_date_formated));
                                    $holidayExist = Holiday::where('date', $holidayDate)->first();
                                    if(empty($holidayExist)){
                                        $tidak_hadir++;
                                        array_push($test_alpha_hadir,$start_date_formated);
                                        array_push($test_arr,$start_date_formated);
                                    }
                                  //pengecekan holiday
                            }
                            // else{
                                // hari yang di set libur di system
                            //     $tidak_hadir++;
                            //     dd($start_date_formated);
                            //     array_push($test_alpha_hadir,$start_date_formated);
                            // }
                        }else{
                            // jika pilih tidak/no
                            if(in_array($dayW, $office_open_days)){
                                // hari biasa dan tidak ada attendance/attendance==0
                                $tidak_hadir++;
                                array_push($test_alpha_hadir,$start_date_formated);
                            }else{
                                // hari libur
                                if ($libur=='yes') {
                                    $tidak_hadir++;
                                    array_push($test_alpha_hadir,$start_date_formated);
                                }
                                // if ($start_date_formated=="2021-03-14") {
                                //     dd('asd1');
                                // }
                            }
                        }
                        // pengecekan office open days dari setting attendance
                      	//$tidak_hadir++;
                        //array_push($test_arr,$start_date_formated);
                    }else{
                        if ($libur!='no') {
                            // check attendance clock in and clockout with cluster
                            $check_hadir = self::checkClockInAndClockOut($employee,$start_date_formated,$hadir,$tidak_hadir);
                            if ($check_hadir) {
                                $hadir++;
                            }else{
                                $already_ijin = false;
                                foreach ($arr_type_ijin as $val) {
                                    if (isset($val['pulang-awal-system']) && !empty($val['pulang-awal-system'])){ 
                                        // jika ada ijin pulang awal system maka skip
                                        $already_ijin = true;
                                    }
                                }
                                if (!$already_ijin) {
                                    // ini bukan tidak hadir tapi masuk ijin pulang lebih awal
                                    array_push($arr_type_ijin,[
                                        'pulang-awal-system' => 1
                                    ]);
                                }
                                $hadir++;
                                // $tidak_hadir++;
                            }
                        }else{
                            if(in_array($dayW, $office_open_days)){
                                // check attendance clock in and clockout with cluster
                                $check_hadir = self::checkClockInAndClockOut($employee,$start_date_formated,$hadir,$tidak_hadir);
                                if ($check_hadir) {
                                    $hadir++;
                                }else{
                                    $already_ijin = false;
                                    foreach ($arr_type_ijin as $val) {
                                        if (isset($val['pulang-awal-system']) && !empty($val['pulang-awal-system'])){ 
                                            // jika ada ijin pulang awal system maka skip
                                            $already_ijin = true;
                                        }
                                    }
                                    if (!$already_ijin) {
                                        // ini bukan tidak hadir tapi masuk ijin pulang lebih awal
                                        array_push($arr_type_ijin,[
                                            'pulang-awal-system' => 1
                                        ]);
                                    }
                                    $hadir++;
                                    // $tidak_hadir++;
                                }
                                // $hadir++;
                            }
                        }
                    }
                }else{
                    // check jika ijin terlambat/keluar kantor/pulang awal
                    if ($flag_datang_terlambat==1 || $flag_pulang_awal==1 || $flag_keluar_kantor==1) {
                        // check user have attendance
                        if (count($getAttendance)!=0) {
                            $hadir++;
                            // CODE DIBAWAH UNTUK NGECEK APAKAH USER PULANG/ABSEN LEBIH AWAL ATAU TIDAK
                            // check attendance clock in and clockout with cluster
                            // $check_hadir = self::checkClockInAndClockOut($employee,$start_date_formated,$hadir,$tidak_hadir);
                            // if ($check_hadir) {
                            //     $hadir++;
                            // }
                            // ketika masuk dibawah berarti orangnya absen pulang sebelum jam nya
                            // else{
                            //     $tidak_hadir++;
                            //     array_push($test_alpha_hadir,$start_date_formated);
                            // }
                        }else{
                            $tidak_hadir++;
                            array_push($test_alpha_hadir,$start_date_formated);
                        }
                    }
                    
                    // if (count($getAttendance) != 0) {
                    //     $hadir++;
                    // }else{
                    //     // masuk sini berarti ada ijin /cuti
                    // }
                    // ini punya ijin/cuti
                    // if (count($getAttendance) == 0) {
                    //     $tidak_hadir++;
                    //     array_push($test_arr,$start_date_formated);
                    // }else{
                    //     // ada absen
                    //     $hadir++;
                    // }
                    // if ($libur =='no') {
                    //     if(!in_array($dayW, $office_open_days)){
                    //         $tidak_hadir++;
                    //         array_push($test_arr,$start_date_formated);
                    //     }
                    // }
                }
            }
            // dd($arr_wfh_cek,$wfh,$arr_test);
            // dd($arr_test_date);
            // ijin
            $arr_output_type_ijin = [];
            $key_ijins=[];
            foreach($arr_type_ijin as $subarr){
                $key_ijins[] = key($subarr);
            }

            // remove duplicate keys
            $key_ijins = array_unique($key_ijins);
            // sum values with same key from $arr_type_cuti and save to $arr_output_type_cuti
            foreach($key_ijins as $key_ijin){
                $arr_output_type_ijin[$key_ijin] = array_sum(array_column($arr_type_ijin,$key_ijin));    
            }

            // cuti
            // $arr_output_type_cuti = [];
            // $keys=[];
            // if($employee->id =='16'){
            //     // dd($arr_type_cuti);
            //     // find all subarray keys (2,3,4,5)
            //     foreach($arr_type_cuti as $subarr){
            //         $keys[] = key($subarr);
            //     }

            //     // remove duplicate keys
            //     $keys = array_unique($keys);

            //     // sum values with same key from $arr and save to $sums
            //     foreach($keys as $key){
            //         $arr_output_type_cuti[$key] = array_sum(array_column($arr_type_cuti,$key));    
            //     }
            // }
            // foreach($arr_type_cuti as $subarr){
            //     $keys[] = key($subarr);
            // }

            // remove duplicate keys
            // $keys = array_unique($keys);

            // sum values with same key from $arr_type_cuti and save to $arr_output_type_cuti
            // foreach($keys as $key){
            //     $arr_output_type_cuti[$key] = array_sum(array_column($arr_type_cuti,$key));    
            // }
            $employee->hadir = $hadir;
            $employee->alpha = $tidak_hadir;
            $employee->ijin = $ijin;
            $employee->cuti = $cuti;
            // $employee->type_cuti = $arr_output_type_cuti;
            $employee->type_ijin = $arr_output_type_ijin;
            $employee->dinas_sementara = $dinas_sementara;
            $employee->dinas_luar_kota = $dinas_luar_kota;
            $employee->terlambat = $terlambat;
            // $employee->ijin_terlambat = $ijin_terlambat;
            // $employee->ijin_pulang_awal = $ijin_pulang_awal;
            // $employee->ijin_tidak_masuk = $ijin_tidak_masuk;
            $employee->pulang_tidak_absen = $pulang_tidak_absen;
            $employee->lembur = $akumulasiLembur;
            $employee->wfh = $wfh;
            $employee->wfh_with_dinas = $wfh_with_dinas;
            $employee->wfh_with_dinas_weekend = $wfh_with_dinas_weekend;
            $employee->wfo = $wfo;
            $employee->wfo_weekend = $wfo_weekend;
            $employee->gps = $gps;
            $employee->tidak_absen_masuk = $getCountLeavePending;
            // $employee->sakit = $sakit;

            $image = '<img src="' . $employee->image_url . '" alt="user" class="img-circle" width="30" height="30"> ';
            $setImage = '<a class="userData" id="userID' . $employee->id . '" data-employee-id="' . $employee->id . '"  href="' . route('admin.employees.show', $employee->id) . '">' . $image . ' ' . ucwords($employee->name) . '</a>';
            $employee->base_name = ucwords($employee->name);
            $employee->name = $setImage;
            // if($employee->id =='16'){
            //     dd($employee);
            // }
        }
        // dd($employees);
        return $employees;
    }
    public static function getLaporanKehadiranDetail($employeeId,$start_date,$end_date,$libur='no'){
        $start_date = Carbon::createFromFormat('d-m-Y', $start_date);
        $end_date = Carbon::createFromFormat('d-m-Y', $end_date);

        $employee = DB::table('users')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->join('employee_details','employee_details.user_id','users.id')
            ->leftJoin('cluster_working_hours as cwh', 'cwh.id', 'employee_details.cluster_working_hour_id')
            ->select(
                'users.id', 
                'users.name', 
                'users.email', 
                'users.created_at', 
                'users.company_id',
                'cwh.type as cluster_type',
                'cwh.json as cluster_json',
                'cwh.start_hour as cluster_start_hour',
                'cwh.end_hour as cluster_end_hour',
                'employee_details.designation_id',
                'users.company_id',
                'users.image',
            )
            ->where('roles.name', '<>', 'client')
            ->where('users.id', $employeeId)
            ->groupBy('users.id')
            ->first();
            // get diff start date and end date in day
            $diff = $start_date->copy()->diffInDays($end_date);
            $terlambat = 0;
            // $ijin_terlambat = 0;
            // $ijin_pulang_awal = 0;
            // $ijin_tidak_masuk = 0;
            $pulang_tidak_absen = 0;
            // $sakit = 0;
            $tidak_hadir = 0; // alhpa
            $hadir = 0; 
            $ijin = 0;
            $cuti = 0;
            $dinas_sementara = 0;
            $dinas_luar_kota = 0;
            $jabatan = Designation::find($employee->designation_id);
            $test_arr =[];
            $arr_date_hadir =[];
            $arr_date_terlambat =[];
            $arr_date_lembur =[];
            $arr_date_pulang_tidak_absen =[];
            $arr_date_tidak_hadir =[];
            $arr_date_late =[];
            $arr_lembur = [];
            $arr_date_cuti =[];
            $arr_type_cuti =[];
            $arr_type_ijin =[];
            $arr_type_ijin_dates =[];
            $arr_wfh =[];
            $arr_wfh_with_dinas =[];
            $arr_wfh_with_dinas_weekend =[];
            $arr_wfo_weekend =[];
            $arr_wfo =[];
            $arr_gps =[];
            $arr_test =[];
          	$dateLateForEmployee = [];
            $akumulasiLembur = 0;
            for ($i = 0; $i <= $diff; $i++) {
                $start_date_formated = $start_date->copy()->addDays($i)->format('Y-m-d');
                
                // pengecekan office open days dari setting attendance
                $setting = AttendanceSetting::where('company_id', company()->id)->first();
                $office_open_days = json_decode($setting->office_open_days);
                $dayW = date('w', strtotime($start_date_formated));

                // no = hari libur tidak dihitung
                if ($libur=='no') {
                    // jika pilih ya
                    if(in_array($dayW, $office_open_days)){
                          // hari kerja
                    }else{
                        // hari libur
                        continue;
                    }
                }
                
                // if ($i=2) {
                //     dd($start_date_formated,'asd')
                // }
                array_push($arr_test,$start_date_formated);
                $end_date_formated = $end_date->copy()->addDays($i)->format('Y-m-d');
                // get attendance by user id and company id
                $getAttendance = Attendance::leftJoin('attendances as at', function($q)
                    {
                        $q->on('at.id', '=', 'attendances.id')
                            ->where('at.working_from','WFH');
            
            
                    })
                    ->leftJoin('attendances as at2', function($q)
                    {
                        $q->on('at2.id', '=', 'attendances.id')
                            ->where('at2.working_from','LACAKGPS');
            
            
                    })
                    ->where('attendances.user_id', $employee->id)
                    ->where('attendances.company_id', $employee->company_id)
                    // ->whereDate(\DB::raw('DATE_ADD(attendances.clock_in_time, INTERVAL 7 HOUR)'), $start_date_formated)
                    ->whereDate('attendances.clock_in_after_timezone', $start_date_formated)
                    ->select('attendances.*',DB::raw('count(at.id) as wfh'),DB::raw('count(at2.id) as gps'))
                    ->groupBy('attendances.id')
                    ->get();
                
                $is_weekend_date = Carbon::parse($start_date_formated);
                // $is_weekend_date->isWeekend()
                // check terlambat
                foreach ($getAttendance as $attendance) {
                    $skip_wfh = false;
                    // check attendance bukan wfh atau gps
                    if ($attendance->working_from !='WFH' && $attendance->working_from !='LACAKGPS') {
                        // check working from dan clock_out_from sama(=)
                        if ($attendance->working_from == $attendance->clock_out_from) {
                            // jika sama berarti wfo
                            if ($is_weekend_date->isWeekend()) {
                                array_push($arr_wfo_weekend,$start_date_formated);
                            }else{
                                array_push($arr_wfo,$start_date_formated);
                            }
                        }else{
                            // tidak sama
                            // check clock_out_from == null /empty
                            if (empty($attendance->clock_out_from)) {
                                // jika null anggap wfo
                                if ($is_weekend_date->isWeekend()) {
                                    array_push($arr_wfo_weekend,$start_date_formated);
                                }else{
                                    array_push($arr_wfo,$start_date_formated);
                                }
                            }else{
                                // do nothing
                            }
                        }
                    }else{
                        // dd('asd',$start_date_formated);
                        // check apakah checkin wfh > checkout wfo
                                
                        if ($attendance->working_from=='WFH' && ($attendance->clock_out_from!='WFH' && $attendance->clock_out_from!='LACAKGPS')) {
                            // berarti working_from WFH dan clock_out_from wfo
                            // check apakah memiliki dinas sementara
                            $check_dinas_sementara = Leave::join('leave_types as lt','lt.id','leaves.leave_type_id')
                                ->where('lt.type_name','Dinas sementara')
                                ->where('leaves.masking_status','done')
                                ->where('leaves.user_id',$attendance->user_id)
                                ->where(function($query) use ($start_date_formated,$end_date_formated){
                                    $query->whereDate('leaves.leave_date','<=',Carbon::parse($start_date_formated)->format('Y-m-d'))
                                        ->whereDate('leaves.leave_date_end','>=',Carbon::parse($start_date_formated)->format('Y-m-d'));
                                })
                                ->select('leaves.*')
                                ->first();
                            if ($check_dinas_sementara) {
                                // dd($check_dinas_sementara);
                                // ada dinas sementara
                                if ($is_weekend_date->isWeekend()) {
                                    array_push($arr_wfo_weekend,$start_date_formated.'|'.$check_dinas_sementara->id);
                                }else{
                                    array_push($arr_wfo,$start_date_formated.'|'.$check_dinas_sementara->id);
                                }
                                // array_push($arr_wfo,$start_date_formated.'|'.$check_dinas_sementara->id);
                                // wfh dikurangin 1 
                                $skip_wfh=true;
                                // $wfh--;
                            }else{
                                // do nothing
                            }
                        }
                        elseif($attendance->working_from=='WFH' && $attendance->clock_out_from=='WFH'){
                            // ketika working_from WFH dan clock_out_from WFH
                            // check dinas sementara
                            $check_dinas_sementara = Leave::join('leave_types as lt','lt.id','leaves.leave_type_id')
                                ->where('lt.type_name','Dinas sementara')
                                ->where('leaves.user_id',$attendance->user_id)
                                ->where('leaves.masking_status','done')
                                ->where(function($query) use ($start_date_formated,$end_date_formated){
                                    $query->whereDate('leaves.leave_date','<=',Carbon::parse($start_date_formated)->format('Y-m-d'))
                                        ->whereDate('leaves.leave_date_end','>=',Carbon::parse($start_date_formated)->format('Y-m-d'));
                                })
                                ->select('leaves.*')
                                ->first();
                            if ($check_dinas_sementara) {
                                // wfh dikurangin 1 
                                // TODO DIBAWAH ARRAY HARUS DIKURANGIN 1
                                $skip_wfh=true;
                                // $wfh--;
                                // check is weekend
                                if ($is_weekend_date->isWeekend()) {
                                    array_push($arr_wfh_with_dinas_weekend,$start_date_formated.'|'.$check_dinas_sementara->id);
                                }else{
                                    // ada dinas sementara
                                    array_push($arr_wfh_with_dinas,$start_date_formated.'|'.$check_dinas_sementara->id);
                                }
                                $skip_wfh=true;
                            }
                        }
                    }
                    // $attendance_clock_in_time = date('H:i:s', strtotime($attendance->clock_in_time . ' +7 hours'));
                    $attendance_clock_in_time = Carbon::parse($attendance->clock_in_after_timezone)->format('H:i:s');
                    // get cluster from attendance
                    $cluster_meta = json_decode($attendance->cluster_meta, true);
                    if ($cluster_meta['type'] == 'daily') {
                        /* code lama pengecekan hari telat salah
                      	// check day of today
                        $dayOfToday = getDayInIndonesia(Carbon::now()->format('l'));
    
                        // get json
                        $json_cluster = json_decode($employee->cluster_json, true);
                        $office_start_time = date('H:i:s', strtotime($json_cluster[$dayOfToday]['jam_masuk']));
                      	*/ 
                      
                        // check day of today
                        //$dayOfToday = getDayInIndonesia(Carbon::now()->format('l'));
    					// $dayOfToday2 = getDayInIndonesia(date('l', strtotime($attendance->clock_in_time . ' +7 hours')));
    					$dayOfToday2 = getDayInIndonesia(Carbon::parse($attendance->clock_in_after_timezone)->format('l'));
                        // get json
                        // $json_cluster = json_decode($employee->cluster_json, true);
                        $clusterController = new ClusterWorkingHourController;
                        $json_cluster = $clusterController->getJsonDaily($cluster_meta['json'], true);
                      
                      	if(isset($json_cluster[$dayOfToday2]['jam_masuk']))
                          $office_start_time = date('H:i:s', strtotime($json_cluster[$dayOfToday2]['jam_masuk']));
                      	else
                          $office_start_time = date('H:i:s', strtotime($json_cluster["senin"]['jam_masuk']));
                          
                      
                    }else{
                        $office_start_time = date('H:i:s', strtotime($cluster_meta['start_hour']));
                    }
                    if ($office_start_time < $attendance_clock_in_time && $jabatan->check_late == 1) {
                        $carbonAttendance_clock_in_time = Carbon::parse($attendance_clock_in_time)->format('Y-m-d');
                        // check have ijin terlambat or not
                        $checkIjinTerlambat = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
                            ->join('leave_ijins as li', 'li.leave_id', 'leaves.id')
                            ->where('leaves.company_id', $employee->company_id)
                            ->where('leaves.user_id', $employee->id)
                            ->where('lt.type_name', 'Ijin')
                            ->where('li.alasan_ijin', 'datang-terlambat')
                            ->whereIn('leaves.masking_status', ['in progress', 'done'])
                            ->where(function($query) use ($start_date_formated){
                                // $query->whereDate('leave_date','<=',$carbonAttendance_clock_in_time)
                                // ->whereDate('leave_date_end','>=',$carbonAttendance_clock_in_time);
                                $query->whereDate('leaves.leave_date','<=',Carbon::parse($start_date_formated)->format('Y-m-d'))
                                    ->whereDate('leaves.leave_date_end','>=',Carbon::parse($start_date_formated)->format('Y-m-d'));
                            })
                            ->count();
                            // if ($employee->id=='83') {
                            //     dd($carbonAttendance_clock_in_time,$start_date_formated);
                            // }
                            if ($checkIjinTerlambat == 0) {
                                //$terlambat++; code lama ini saja
                              	$to_time = strtotime($attendance_clock_in_time);
                              	$from_time = strtotime($office_start_time);
                              	$late =  round(abs($to_time - $from_time) / 60,2). " minute"." cluster time:".$office_start_time;
                        		// $start_date_formated_new = date("Y-m-d H:i:s", strtotime($attendance->clock_in_time." +7 hours"));
                        		$start_date_formated_new = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d H:i:s');
                              	$arrLate["value"] = $start_date_formated_new;
                                  $arrLate["office_start_time"] = $office_start_time;
                              	$arrLate["late"] = $late;
                      			
                              	// $check = date("Y-m-d", strtotime($attendance->clock_in_time." +7 hours"));
                              	$check = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d');
                              	if(!in_array($check, $dateLateForEmployee)){
                                  //kalau sudah terlambat sekali tidak usah dihitung lagi (absen bisa lebih dari sekali)
                                  $terlambat++;
                                  array_push($arr_date_terlambat,Carbon::parse($start_date_formated_new)->format('Y-m-d'));
                                //   $dateLateForEmployee[] = date("Y-m-d", strtotime($attendance->clock_in_time." +7 hours"));
                                  $dateLateForEmployee[] = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d');
                                  array_push($arr_date_late,$arrLate);
                                }
                            }
                    }

                    // check pulang tidak absen
                    if($attendance->cron_clock_out == 1){
                        $pulang_tidak_absen++;
                        array_push($arr_date_pulang_tidak_absen, Carbon::parse($start_date_formated)->format('Y-m-d'));
                    }
                    else{ //08.02 --- cek lembur ---

                        if ($cluster_meta['type'] == 'daily') {
                            // $dayOfToday2 = getDayInIndonesia(date('l', strtotime($attendance->clock_in_time . ' +7 hours')));
                            $dayOfToday2 = getDayInIndonesia(Carbon::parse($attendance->clock_in_after_timezone)->format('l'));
                            // get json
                            // $json_cluster = json_decode($employee->cluster_json, true);
                            $clusterController = new ClusterWorkingHourController;
                            $json_cluster = $clusterController->getJsonDaily($cluster_meta['json'], true);
                          
                            if(isset($json_cluster[$dayOfToday2]['jam_pulang']))
                              $office_end_time = date('H:i:s', strtotime($json_cluster[$dayOfToday2]['jam_pulang']));
                            else
                              $office_end_time = date('H:i:s', strtotime($json_cluster["senin"]['jam_pulang']));

                            // $attendance_clock_out_time = date('H:i:s', strtotime($attendance->clock_out_time . ' +7 hours'));
                            $attendance_clock_out_time = date('H:i:s', strtotime($attendance->clock_out_after_timezone));

                            // if ($office_end_time > $attendance_clock_out_time) {
                            if ($attendance_clock_out_time > $office_end_time) {

                                    // $clock_in_date = date("Y-m-d", strtotime($attendance->clock_in_time." +7 hours"));
                                    $clock_in_date = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d');
                                    $from = date("Y-m-d H:i:s", strtotime($clock_in_date." ".$office_end_time));
                                    // $from_time = strtotime($attendance_clock_out_time);
                                    // $from_time = strtotime($from);
                                    // $to_time = strtotime($attendance_clock_out_time);
                                    
                                    // $attendance_clock_out_time_copy = date('Y-m-d H:i:s', strtotime($attendance->clock_out_time . ' +7 hours'));
                                    $attendance_clock_out_time_copy = date('Y-m-d H:i:s', strtotime($attendance->clock_out_after_timezone));
                                    // dd($attendance_clock_out_time_copy, $from);
                                    $diffInMinutes = Carbon::parse($attendance_clock_out_time_copy)->diffInMinutes($from);
                                    // dd($diffInMinutes);
                                    // $lembur =  round(abs($to_time - $from_time) / 60,2). " minute"." cluster time:".$office_end_time;
                                    $lembur =  $diffInMinutes. " minute"." cluster time:".$office_end_time;
                                    // $akumulasiLembur +=  round(abs($to_time - $from_time) / 60,2);
                                    $akumulasiLembur +=  $diffInMinutes;
                                    // $start_date_formated_new = date("Y-m-d H:i:s", strtotime($attendance->clock_out_time." +7 hours"));
                                    $start_date_formated_new = date("Y-m-d H:i:s", strtotime($attendance->clock_out_after_timezone));
                                    array_push($arr_date_lembur,Carbon::parse($start_date_formated_new)->format('Y-m-d'));
                                    // $clock_in_time = date("Y-m-d H:i:s", strtotime($attendance->clock_in_time." +7 hours"));
                                    $clock_in_time = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d H:i:s');
                                    $arrLembur["value"] = $start_date_formated_new;
                                    $arrLembur["clock_in_time"] = $clock_in_time;
                                    $arrLembur["office_end_time"] = $office_end_time;
                                    $arrLembur["lembur"] = $lembur;

                                    array_push($arr_lembur,$arrLembur);
                            }
                        }
                    }
                    // get wfh
                    if ($attendance->wfh>0) {
                        if (!$skip_wfh) {
                            array_push($arr_wfh,$start_date_formated);
                        }
                    }
                    // get gps
                    if ($attendance->gps>0) {
                        array_push($arr_gps,$start_date_formated);
                    }
                }
                
                $flag_datang_terlambat=0;
                $flag_pulang_awal=0;
                $flag_keluar_kantor=0;
                $getIjin = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
                    ->leftjoin('leave_ijins as li','li.leave_id','leaves.id')
                    ->where('leaves.company_id', $employee->company_id)
                    ->where('leaves.user_id', $employee->id)
                    ->where('lt.type_name', 'Ijin')
                    // ->whereIn('leaves.masking_status', ['in progress', 'done'])
                    ->whereIn('leaves.masking_status', ['done'])
                    ->where(function($query) use ($start_date_formated,$end_date_formated){
                        $query->whereDate('leaves.leave_date','<=',Carbon::parse($start_date_formated)->format('Y-m-d'))
                            ->whereDate('leaves.leave_date_end','>=',Carbon::parse($start_date_formated)->format('Y-m-d'));
                    })
                    ->selectRaw('leaves.*,li.alasan_ijin')
                    ->get();
                if (count($getIjin)==0) {
                    // check with cluster
                    $checkCluster =self::checkClockInAndClockOut($employee,Carbon::parse($start_date_formated)->format('Y-m-d'));
                    if (!$checkCluster) {
                        array_push($arr_type_ijin,[
                            'pulang-awal-system' => 1
                        ]);
                        array_push($arr_type_ijin_dates,[
                            'pulang-awal-system' => $start_date_formated
                        ]);
                    }
                }
                //check ijin tidak-masuk
                foreach ($getIjin as $val) {
                    if ($val->alasan_ijin=='tidak-masuk') {
                        array_push($arr_type_ijin,[
                            'tidak-masuk' => 1
                        ]);
                        array_push($arr_type_ijin_dates,[
                            'tidak-masuk' => $start_date_formated
                        ]);
                    }elseif ($val->alasan_ijin=='sakit') {
                        array_push($arr_type_ijin,[
                            'sakit' => 1
                        ]);
                        array_push($arr_type_ijin_dates,[
                            'sakit' => $start_date_formated
                        ]);
                    }elseif ($val->alasan_ijin=='datang-terlambat') {
                        array_push($arr_type_ijin,[
                            'datang-terlambat' => 1
                        ]);
                        array_push($arr_type_ijin_dates,[
                            'datang-terlambat' => $start_date_formated
                        ]);
                        $flag_datang_terlambat=1;
                    }elseif ($val->alasan_ijin=='pulang-awal') {
                        if ($val->approved_by=='system') {
                            array_push($arr_type_ijin,[
                                'pulang-awal-system' => 1
                            ]);
                            array_push($arr_type_ijin_dates,[
                                'pulang-awal-system' => $start_date_formated
                            ]);
                        }else{
                            array_push($arr_type_ijin,[
                                'pulang-awal' => 1
                            ]);
                            array_push($arr_type_ijin_dates,[
                                'pulang-awal' => $start_date_formated
                            ]);
                        }
                        $flag_pulang_awal=1;
                    }elseif ($val->alasan_ijin=='keluar-kantor') {
                        array_push($arr_type_ijin,[
                            'keluar-kantor' => 1
                        ]);
                        array_push($arr_type_ijin_dates,[
                            'keluar-kantor' => $start_date_formated
                        ]);
                        $flag_keluar_kantor=1;
                    }
                }

                // get wfh
                // dd($start_date_formated);
                
                $getCuti =0;
                if(in_array($dayW, $office_open_days)){
                    $getCuti = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
                        ->leftjoin('leave_cutis','leave_cutis.leave_id','leaves.id')
                        ->leftjoin('tipe_cutis','tipe_cutis.id','leave_cutis.kategori_cuti')
                        ->where('leaves.company_id', $employee->company_id)
                        ->where('leaves.user_id', $employee->id)
                        ->whereIn('lt.type_name', ['Cuti','Cuti 3 Bulanan','Cuti Custom'])
                        ->whereIn('leaves.masking_status', ['done'])
                        ->where(function($query) use ($start_date_formated,$end_date_formated){
                            $query->whereDate('leaves.leave_date','<=',Carbon::parse($start_date_formated)->format('Y-m-d'))
                                ->whereDate('leaves.leave_date_end','>=',Carbon::parse($start_date_formated)->format('Y-m-d'));
                        })
                        ->selectRaw('leaves.*,tipe_cutis.id as tipe_cutis_id,tipe_cutis.name as tipe_cutis_name')
                        ->count();
                        // ->get();
                    // $cuti += count($getCuti);
                    $cuti += $getCuti;
                    if ($getCuti !=0) {
                        array_push($arr_date_cuti,Carbon::parse($start_date_formated)->format('Y-m-d'));
                    }
                }
                
                if(
                    count($getIjin) == 0 && 
                    $getCuti == 0  
                    // $getDinasSementara == 0 && 
                    // $getDinasLuarKota ==0 && 
                    // $getIjinTidakMasuk ==0 && 
                    // $getSakit ==0
                    ){
                    if (count($getAttendance) == 0) {
                        if ($libur=='no') {
                            
                            // jika pilih ya
                            if(in_array($dayW, $office_open_days)){
                                //pengecekan holiday
                                $holidayDate = date('Y-m-d', strtotime($start_date_formated));
                                $holidayExist = Holiday::where('date', $holidayDate)->first();
                                
                                    if(empty($holidayExist)){
                                        $tidak_hadir++;
                                        array_push($test_arr,$start_date_formated);
                                        array_push($arr_date_tidak_hadir,Carbon::parse($start_date_formated)->format('Y-m-d'));
                                    }
                                  //pengecekan holiday
                            }else{
                                $tidak_hadir++;
                                array_push($arr_date_tidak_hadir,Carbon::parse($start_date_formated)->format('Y-m-d'));
                            }
                        }else{
                            
                            // jika pilih tidak/no
                            if(in_array($dayW, $office_open_days)){
                                // hari biasa dan tidak ada attendance/attendance==0
                                $tidak_hadir++;
                                array_push($arr_date_tidak_hadir,Carbon::parse($start_date_formated)->format('Y-m-d'));
                            }else{
                                // hari libur
                                if ($libur=='yes') {
                                    $tidak_hadir++;
                                    array_push($arr_date_tidak_hadir,Carbon::parse($start_date_formated)->format('Y-m-d'));
                                }
                                // if ($start_date_formated=="2021-03-14") {
                                //     dd('asd1');
                                // }
                            }
                        }
                        // pengecekan office open days dari setting attendance
                      	//$tidak_hadir++;
                        //array_push($test_arr,$start_date_formated);
                    }else{
                        
                        // check attendance clock in and clockout with cluster
                        $check_hadir = self::checkClockInAndClockOut($employee,$start_date_formated,$hadir,$tidak_hadir);
                        if ($check_hadir) {
                            $hadir++;
                            array_push($arr_date_hadir, Carbon::parse($start_date_formated)->format('Y-m-d'));
                        }else{
                            $already_ijin = false;
                            foreach ($arr_type_ijin as $val) {
                                if (isset($val['pulang-awal-system']) && !empty($val['pulang-awal-system'])){ 
                                    // jika ada ijin pulang awal system maka skip
                                    $already_ijin = true;
                                }
                            }
                            if (!$already_ijin) {
                                // ini bukan tidak hadir tapi masuk ijin pulang lebih awal
                                array_push($arr_type_ijin,[
                                    'pulang-awal-system' => 1
                                ]);
                                array_push($arr_type_ijin_dates,[
                                    'pulang-awal-system' => $start_date_formated
                                ]);
                            }
                            $hadir++;
                            array_push($arr_date_hadir, Carbon::parse($start_date_formated)->format('Y-m-d'));
                            // $tidak_hadir++;
                            // array_push($arr_date_tidak_hadir, Carbon::parse($start_date_formated)->format('Y-m-d'));
                        }
                        // $hadir++;
                    }
                }else{
                    
                    // check jika ijin terlambat/keluar kantor/pulang awal
                    if ($flag_datang_terlambat==1 || $flag_pulang_awal==1 || $flag_keluar_kantor==1) {
                        if (count($getAttendance)!=0) {
                            // check attendance clock in and clockout with cluster
                            $check_hadir = self::checkClockInAndClockOut($employee,$start_date_formated,$hadir,$tidak_hadir);
                            if ($check_hadir) {
                                $hadir++;
                                array_push($arr_date_hadir, Carbon::parse($start_date_formated)->format('Y-m-d'));
                            }else{
                                $already_ijin = false;
                                foreach ($arr_type_ijin as $val) {
                                    if (isset($val['pulang-awal-system']) && !empty($val['pulang-awal-system'])){ 
                                        // jika ada ijin pulang awal system maka skip
                                        $already_ijin = true;
                                    }
                                }
                                if (!$already_ijin) {
                                    // ini bukan tidak hadir tapi masuk ijin pulang lebih awal
                                    array_push($arr_type_ijin,[
                                        'pulang-awal-system' => 1
                                    ]);
                                    array_push($arr_type_ijin_dates,[
                                        'pulang-awal-system' => $start_date_formated
                                    ]);
                                }
                                array_push($arr_date_hadir, Carbon::parse($start_date_formated)->format('Y-m-d'));
                                $hadir++;
                                // $tidak_hadir++;
                                // array_push($arr_date_tidak_hadir, Carbon::parse($start_date_formated)->format('Y-m-d'));
                            }
                            // $hadir++;
                            // array_push($arr_date_hadir,Carbon::parse($start_date_formated)->format('Y-m-d'));
                        }else{
                            $tidak_hadir++;
                            array_push($arr_date_tidak_hadir,Carbon::parse($start_date_formated)->format('Y-m-d'));
                        }
                    }
                }
                
            }
            // ijin
            $arr_output_type_ijin = [];
            $arr_output_type_ijin_date = [];
            $key_ijins=[];
            foreach($arr_type_ijin as $subarr){
                $key_ijins[] = key($subarr);
            }
            
            // remove duplicate keys
            $key_ijins = array_unique($key_ijins);

            // sum values with same key from $arr_type_cuti and save to $arr_output_type_cuti
            foreach($key_ijins as $key_ijin){
                $arr_output_type_ijin[$key_ijin] = array_sum(array_column($arr_type_ijin,$key_ijin));    
            }
            // mapping data to array
            foreach($arr_type_ijin_dates as $arr_type_ijin_date){
                foreach ($arr_type_ijin_date as $key => $val) {
                    $arr_output_type_ijin_date[$key][] = $arr_type_ijin_date[$key];    
                }
            }

            $employee->hadir = $hadir;
            // dd($arr_date_hadir);
            $employee->arr_date_hadir = $arr_date_hadir;
            $employee->alpha = $tidak_hadir;
            $employee->arr_date_tidak_hadir = $arr_date_tidak_hadir;
            $employee->ijin = $ijin;
            $employee->cuti = $cuti;
            $employee->arr_date_cuti = $arr_date_cuti;
            // $employee->type_cuti = $arr_output_type_cuti;
            // dd($arr_output_type_ijin);
            $employee->type_ijin = $arr_output_type_ijin;
            $employee->type_ijin_date = $arr_output_type_ijin_date;
            $employee->dinas_sementara = $dinas_sementara;
            $employee->dinas_luar_kota = $dinas_luar_kota;
            $employee->terlambat = $terlambat;
            $employee->arr_date_terlambat = $arr_date_terlambat;
            $employee->pulang_tidak_absen = $pulang_tidak_absen;
            $employee->arr_date_pulang_tidak_absen = $arr_date_pulang_tidak_absen;
            $employee->lembur = $akumulasiLembur;
            $employee->arr_date_lembur = $arr_date_lembur;
            $employee->arr_wfh = array_unique($arr_wfh);
            $employee->arr_wfh_with_dinas = array_unique($arr_wfh_with_dinas);
            $employee->arr_wfh_with_dinas_weekend = array_unique($arr_wfh_with_dinas_weekend);
            $employee->arr_wfo = array_unique($arr_wfo);
            $employee->arr_wfo_weekend = array_unique($arr_wfo_weekend);
            $employee->arr_gps = array_unique($arr_gps);
            // $employee->sakit = $sakit;

            $image_url = ($employee->image) ? asset_url('avatar/' . $employee->image) : asset('img/default-profile-3.png');
            $image = '<img src="' . $image_url . '" alt="user" class="img-circle" width="30" height="30"> ';
            $setImage = '<a class="userData" id="userID' . $employee->id . '" data-employee-id="' . $employee->id . '"  href="' . route('admin.employees.show', $employee->id) . '">' . $image . ' ' . ucwords($employee->name) . '</a>';
            $employee->base_name = ucwords($employee->name);
            $employee->name = $setImage;
            $employee->data_lembur = $arr_lembur;
            $employee->arr_date_late = $arr_date_late;
            return $employee;
            
    }
    public static function checkClockInAndClockOut($employee,$start_date_formated,$hadir = null,$tidak_hadir = null){
        $curr_start_date_formated = Carbon::parse($start_date_formated)->format('Y-m-d');
        $getAttendanceForCheck = Attendance::where('user_id', $employee->id)
            ->where('company_id', $employee->company_id)
            // ->whereDate(\DB::raw('DATE_ADD(clock_in_time, INTERVAL 7 HOUR)'), $curr_start_date_formated)
            ->whereDate('clock_in_after_timezone', $curr_start_date_formated)
            // ->whereDate('clock_in_time', $start_date_formated)
            ->first();
        // if ($curr_start_date_formated=="2021-07-01") {
        //     dd($getAttendanceForCheck);
        // }
        if (!empty($getAttendanceForCheck)) {
            // get date by current request
            // $datetime_attendance_clock_in = Carbon::parse($getAttendanceForCheck->clock_in_time)->addHours(7);
            $datetime_attendance_clock_in = Carbon::parse($getAttendanceForCheck->clock_in_after_timezone);
            $datetime_attendance_clock_in_day = $datetime_attendance_clock_in->copy()->format('l');
            $datetime_attendance_clock_in_day = getDayInIndonesia($datetime_attendance_clock_in_day);
            // dd($getAttendanceForCheck->clock_out_time);
            if (isset($getAttendanceForCheck->clock_out_time) && !empty($getAttendanceForCheck->clock_out_time)){  
            //  $datetime_attendance_clock_out = Carbon::parse($getAttendanceForCheck->clock_out_time)->addHours(7);
             $datetime_attendance_clock_out = Carbon::parse($getAttendanceForCheck->clock_out_after_timezone);
             // todo: do something when clockout null
             // dd($datetime_attendance_clock_out);
             $datetime_attendance_clock_out_day = $datetime_attendance_clock_out->copy()->format('l');
             $datetime_attendance_clock_out_day = getDayInIndonesia($datetime_attendance_clock_out_day);
             // get cluster from attendance
             $cluster_meta = json_decode($getAttendanceForCheck->cluster_meta);
             if ($cluster_meta->type=='daily') {
                 // this function to give default value when schedule is empty
                 $clusterController = new ClusterWorkingHourController;
                 $cluster_meta_json = $clusterController->getJsonDaily($cluster_meta->json, true);
     
                //  $json = json_decode($employee->cluster_json,true);
                 $jam_masuk = $cluster_meta_json[$datetime_attendance_clock_in_day]['jam_masuk'];
                 $carbon_jam_masuk = Carbon::parse($jam_masuk)->format('H:i');
                 $jam_pulang = $cluster_meta_json[$datetime_attendance_clock_in_day]['jam_pulang'];
                 $carbon_jam_pulang = Carbon::parse($jam_pulang)->format('H:i');
                 // dd($jam_masuk,$jam_pulang);
                 $datetime_attendance_clock_in_hour = $datetime_attendance_clock_in->copy()->format('H:i');
                 $datetime_attendance_clock_out_hour = $datetime_attendance_clock_out->copy()->format('H:i');
     
                 // $datetime_jam_pulang_cluster = Carbon::parse($jam_pulang);
                 // // get general setting
                 $attendanceSetting = AttendanceSetting::where('company_id',$employee->company_id)->first();
                 $datetime_jam_pulang_cluster_minus_setting = Carbon::parse($carbon_jam_pulang)->subMinutes($attendanceSetting->toleransi_absen_pulang)->format('H:i');
                 // dd($datetime_attendance_clock_in_hour<$carbon_jam_masuk && $datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting,"$datetime_attendance_clock_in_hour<$carbon_jam_masuk && $datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting");
                 // if ($datetime_attendance_clock_in_hour<$carbon_jam_masuk && $datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting) {
                     // dd($datetime_attendance_clock_out_hour<$carbon_jam_pulang,"$datetime_attendance_clock_out_hour<$carbon_jam_pulang");
                     // dd($datetime_jam_pulang_cluster_minus_setting);
                     // dd($datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting,"$datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting");
                    // if ($curr_start_date_formated=="2021-06-24") {
                    //     dd($getAttendanceForCheck,"$datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting");
                    // }
                 if ($datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting) {
                     // jika absen masuk dan pulang dibawah jadwal cluster
                     // TAMBAH PENEGCEKAN SKIP JIKA HARI INI ADALAH HARI MINGGU ATAU HARI LIBUR
                     // pengecekan office open days dari setting attendance
                    $setting = AttendanceSetting::where('company_id', company()->id)->first();
                    $office_open_days = json_decode($setting->office_open_days);
                    $dayW = date('w', strtotime($datetime_attendance_clock_in));
                    // if ($datetime_attendance_clock_in->copy()->format('Y-m-d')=="2021-06-27") {
                    //     dd(in_array($dayW, $office_open_days),$office_open_days);
                    // }
                    if(!in_array($dayW, $office_open_days)){
                        // masuk sini berarti hari libur
                        return true;
                    }
                    // check with holiday
                    $checkHoliday = Holiday::whereDate('holidays.date', $datetime_attendance_clock_in)->get();
                    if (count($checkHoliday)>0){ 
                        // masuk sini berarti hari libur
                        return true;
                    }
                    return false;
                 //   $tidak_hadir++;
                 }else{
                     return true;
                 //   $hadir++;
                 }
             }else{
                 
                 $carbon_jam_masuk = Carbon::parse($cluster_meta->start_hour)->format('H:i');
                 // $jam_pulang = $json[$datetime_attendance_clock_in_day]['jam_pulang'];
                 $carbon_jam_pulang = Carbon::parse($cluster_meta->end_hour)->format('H:i');
     
                 $datetime_attendance_clock_in_hour = $datetime_attendance_clock_in->copy()->format('H:i');
                 $datetime_attendance_clock_out_hour = $datetime_attendance_clock_out->copy()->format('H:i');
     
                 // $datetime_jam_pulang_cluster = Carbon::parse($jam_pulang);
                 // // get general setting
                 $attendanceSetting = AttendanceSetting::where('company_id',$employee->company_id)->first();
                 $datetime_jam_pulang_cluster_minus_setting = Carbon::parse($carbon_jam_pulang)->subMinutes($attendanceSetting->toleransi_absen_pulang)->format('H:i');
                 // dd($datetime_attendance_clock_in_hour<$carbon_jam_masuk && $datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting,"$datetime_attendance_clock_in_hour<$carbon_jam_masuk && $datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting");
                 // if ($datetime_attendance_clock_in_hour<$carbon_jam_masuk && $datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting) {
                 if ($datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting) {
                    // jika absen masuk dan pulang dibawah jadwal cluster
                    // check dengan hari libur
                    $setting = AttendanceSetting::where('company_id', company()->id)->first();
                    $office_open_days = json_decode($setting->office_open_days);
                    $dayW = date('w', strtotime($datetime_attendance_clock_in));
                    // if ($datetime_attendance_clock_in->copy()->format('Y-m-d')=="2021-06-27") {
                    //     dd(in_array($dayW, $office_open_days),$office_open_days);
                    // }
                    if(!in_array($dayW, $office_open_days)){
                        // masuk sini berarti hari libur
                        return true;
                    }
                    // check with holiday
                    $checkHoliday = Holiday::whereDate('holidays.date', $datetime_attendance_clock_in)->get();
                    if (count($checkHoliday)>0){ 
                        // masuk sini berarti hari libur
                        return true;
                    }
                    return false;
                 //   $tidak_hadir++;
                 }else{
                     return true;
                 //   $hadir++;
                 }
             }
            }else{
                // if null diasumsikan belum clock out, karena seharusnya tidak ada data clock_out_time yang null
                // pake pada kondisi ini di return true, karena user belum clockout
                return true;
            }
        }else{
            // KALAU MASUK SINI BERARTI USER TIDAK ABSEN MA DISET TRUE AJA
            // check dengan hari libur
            // $setting = AttendanceSetting::where('company_id', company()->id)->first();
            // $office_open_days = json_decode($setting->office_open_days);
            // $dayW = date('w', strtotime($curr_start_date_formated));
            // // if ($datetime_attendance_clock_in->copy()->format('Y-m-d')=="2021-06-27") {
            // //     dd(in_array($dayW, $office_open_days),$office_open_days);
            // // }
            // if(!in_array($dayW, $office_open_days)){
            //     // if ($curr_start_date_formated=="2021-07-01") {
            //     //     dd($getAttendanceForCheck);
            //     // }
            //     // masuk sini berarti hari libur
            //     return true;
            // }
            // // check with holiday
            // $checkHoliday = Holiday::whereDate('holidays.date', $curr_start_date_formated)->get();
            // if (count($checkHoliday)>0){ 
            //     // masuk sini berarti hari libur
            //     return true;
            // }
            return true;
        }
    }
    // CODE OLD READ FROM TABLE CLUSTER_WORKING_HOURS
    // public static function checkClockInAndClockOut($employee,$start_date_formated,$hadir = null,$tidak_hadir = null){
    //     $curr_start_date_formated = Carbon::parse($start_date_formated)->format('Y-m-d');
    //     $getAttendanceForCheck = Attendance::where('user_id', $employee->id)
    //         ->where('company_id', $employee->company_id)
    //         ->whereDate(\DB::raw('DATE_ADD(clock_in_time, INTERVAL 7 HOUR)'), $curr_start_date_formated)
    //         // ->whereDate('clock_in_time', $start_date_formated)
    //         ->first();
    //         // dd($getAttendanceForCheck);
    //     if (!empty($getAttendanceForCheck)) {
    //         // get date by current request
    //         $datetime_attendance_clock_in = Carbon::parse($getAttendanceForCheck->clock_in_time)->addHours(7);
    //         $datetime_attendance_clock_in_day = $datetime_attendance_clock_in->copy()->format('l');
    //         $datetime_attendance_clock_in_day = getDayInIndonesia($datetime_attendance_clock_in_day);
    //         // dd($getAttendanceForCheck->clock_out_time);
    //         if (isset($getAttendanceForCheck->clock_out_time) && !empty($getAttendanceForCheck->clock_out_time)){  
    //          $datetime_attendance_clock_out = Carbon::parse($getAttendanceForCheck->clock_out_time)->addHours(7);
    //          // todo: do something when clockout null
    //          // dd($datetime_attendance_clock_out);
    //          $datetime_attendance_clock_out_day = $datetime_attendance_clock_out->copy()->format('l');
    //          $datetime_attendance_clock_out_day = getDayInIndonesia($datetime_attendance_clock_out_day);
    //          if ($employee->cluster_type=='daily') {
     
     
    //              $json = json_decode($employee->cluster_json,true);
    //              $jam_masuk = $json[$datetime_attendance_clock_in_day]['jam_masuk'];
    //              $carbon_jam_masuk = Carbon::parse($jam_masuk)->format('H:i');
    //              $jam_pulang = $json[$datetime_attendance_clock_in_day]['jam_pulang'];
    //              $carbon_jam_pulang = Carbon::parse($jam_pulang)->format('H:i');
    //              // dd($jam_masuk,$jam_pulang);
    //              $datetime_attendance_clock_in_hour = $datetime_attendance_clock_in->copy()->format('H:i');
    //              $datetime_attendance_clock_out_hour = $datetime_attendance_clock_out->copy()->format('H:i');
     
    //              // $datetime_jam_pulang_cluster = Carbon::parse($jam_pulang);
    //              // // get general setting
    //              $attendanceSetting = AttendanceSetting::where('company_id',$employee->company_id)->first();
    //              $datetime_jam_pulang_cluster_minus_setting = Carbon::parse($carbon_jam_pulang)->subMinutes($attendanceSetting->toleransi_absen_pulang)->format('H:i');
    //              // dd($datetime_attendance_clock_in_hour<$carbon_jam_masuk && $datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting,"$datetime_attendance_clock_in_hour<$carbon_jam_masuk && $datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting");
    //              // if ($datetime_attendance_clock_in_hour<$carbon_jam_masuk && $datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting) {
    //                  // dd($datetime_attendance_clock_out_hour<$carbon_jam_pulang,"$datetime_attendance_clock_out_hour<$carbon_jam_pulang");
    //                  // dd($datetime_jam_pulang_cluster_minus_setting);
    //                  // dd($datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting,"$datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting");
    //              if ($datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting) {
    //                  // jika absen masuk dan pulang dibawah jadwal cluster
    //                  return false;
    //              //   $tidak_hadir++;
    //              }else{
    //                  return true;
    //              //   $hadir++;
    //              }
    //          }else{
    //              $carbon_jam_masuk = Carbon::parse($employee->cluster_start_hour)->format('H:i');
    //              // $jam_pulang = $json[$datetime_attendance_clock_in_day]['jam_pulang'];
    //              $carbon_jam_pulang = Carbon::parse($employee->cluster_end_hour)->format('H:i');
     
    //              $datetime_attendance_clock_in_hour = $datetime_attendance_clock_in->copy()->format('H:i');
    //              $datetime_attendance_clock_out_hour = $datetime_attendance_clock_out->copy()->format('H:i');
     
    //              // $datetime_jam_pulang_cluster = Carbon::parse($jam_pulang);
    //              // // get general setting
    //              $attendanceSetting = AttendanceSetting::where('company_id',$employee->company_id)->first();
    //              $datetime_jam_pulang_cluster_minus_setting = Carbon::parse($carbon_jam_pulang)->subMinutes($attendanceSetting->toleransi_absen_pulang)->format('H:i');
    //              // dd($datetime_attendance_clock_in_hour<$carbon_jam_masuk && $datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting,"$datetime_attendance_clock_in_hour<$carbon_jam_masuk && $datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting");
    //              // if ($datetime_attendance_clock_in_hour<$carbon_jam_masuk && $datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting) {
    //              if ($datetime_attendance_clock_out_hour<$datetime_jam_pulang_cluster_minus_setting) {
    //                  // jika absen masuk dan pulang dibawah jadwal cluster
    //                  return false;
    //              //   $tidak_hadir++;
    //              }else{
    //                  return true;
    //              //   $hadir++;
    //              }
    //          }
    //         }else{
    //             // if null diasumsikan belum clock out, karena seharusnya tidak ada data clock_out_time yang null
    //             // pake pada kondisi ini di return true, karena user belum clockout
    //             return true;
    //         }
    //     }else{
    //         return false;
    //     }
    // }


}
