<?php

namespace Modules\RestAPI\Http\Controllers;

use App\AttendancePertanyaan;
use Froiden\RestAPI\ApiController;
use Modules\RestAPI\Entities\Attendance;
use Modules\RestAPI\Http\Requests\Attendance\IndexRequest;
use Modules\RestAPI\Http\Requests\Attendance\CreateRequest;
use Modules\RestAPI\Http\Requests\Attendance\UpdateRequest;
use Modules\RestAPI\Http\Requests\Attendance\ShowRequest;
use Modules\RestAPI\Http\Requests\Attendance\DeleteRequest;
use Froiden\RestAPI\Exceptions\ApiException;

use App\AttendanceSetting;
use App\Http\Requests\Attendance\APIStoreAttendance;
use Carbon\Carbon;
use App\Company;
use Illuminate\Support\Facades\DB;
use Froiden\RestAPI\ApiResponse;
use App\Office;
use Illuminate\Support\Facades\File;
use App\Helper\Files;
use Modules\RestAPI\Entities\ProjectTimeLog;
use App\EmployeeDetails;
use App\Http\Controllers\Admin\ClusterWorkingHourController;
use App\Http\Requests\API\APIRequest;
use App\Leave;
use App\LeaveIjin;
use App\OfficeWifi;
use App\ScheduleKapal;
use App\Notifications\CustomMessageNotif;
use App\User;
use Modules\RestAPI\Entities\LeaveType;

class AttendanceController extends ApiBaseController
{
  protected $model = Attendance::class;

  protected $indexRequest = IndexRequest::class;
  protected $storeRequest = CreateRequest::class;
  protected $updateRequest = UpdateRequest::class;
  protected $showRequest = ShowRequest::class;
  protected $deleteRequest = DeleteRequest::class;
  protected $errorCode = true;


  public function getServerTime(APIStoreAttendance $request)
  {

    try {
      DB::beginTransaction();
      // $user = auth()->user();

      return ApiResponse::make('Server time', [
        'dateTime' => Carbon::now()->toIso8601String()
      ]);
    } catch (Exception $e) {
      DB::rollback();
      // return ApiResponse::make('Attendance failed '.$e->getMessages());
      $exception = new ApiException('Get server time failed ' . $e->getMessages(), null, 403, 403, 2001);
      return ApiResponse::exception($exception);
    }
  }
  public function getOffice(APIStoreAttendance $request)
  {

    try {
      DB::beginTransaction();
      $user = auth()->user();


      $office = Office::where('code', $request->code)->first();
      if (empty($office)) {
        return ApiResponse::make('Office not found', []);
      }

      return ApiResponse::make('An office found', [
        'office' => $office
      ]);
    } catch (Exception $e) {
      DB::rollback();
      // return ApiResponse::make('Attendance failed '.$e->getMessages());
      $exception = new ApiException('Attendance failed ' . $e->getMessages(), null, 403, 403, 2001);
      return ApiResponse::exception($exception);
    }
  }
  public function getHistoryAttendance(APIStoreAttendance $request)
  {
    try {
      DB::beginTransaction();
      $user = auth()->user();
      $this->global = $this->company = Company::withoutGlobalScope('active')->where('id', $user->company_id)->first();



      $attendance = Attendance::where('user_id', $user->id);

      if (isset($request->start_date)) {
        $date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        // $attendance = $attendance->where(DB::raw('DATE(`clock_in_time`)'), ">=", $date);
        $attendance = $attendance->where(DB::raw('DATE(`clock_in_after_timezone`)'), ">=", $date);
      }
      if (isset($request->end_date)) {
        $date = Carbon::createFromFormat($this->global->date_format, $request->end_date)->format('Y-m-d');
        // $attendance = $attendance->where(DB::raw('DATE(`clock_in_time`)'), "<=", $date);
        $attendance = $attendance->where(DB::raw('DATE(`clock_in_after_timezone`)'), "<=", $date);
      }
      $attendance = $attendance->orderBy("clock_in_time", "desc")->get();
      foreach ($attendance as $data) {
        // check with leave
        if (isset($data->clock_out_after_timezone) && !empty($data->clock_out_after_timezone)){  
          $checkLeave= Leave::where('user_id', $data->user_id)
            ->whereDate('leave_date',$data->clock_out_after_timezone)
            ->where('approved_by','system')
            ->first();
          if (isset($checkLeave) && !empty($checkLeave)){ 
            $data->is_cut_off = true;
            $data->cut_off_leave_id = $checkLeave->id;
          }else{
            $data->is_cut_off = false;
            $data->cut_off_leave_id = null;
          }
        }else{
            $data->is_cut_off = false;
            $data->cut_off_leave_id = null;
        }
      }

      DB::commit();
      return ApiResponse::make('Get attendance success', [
        'user' => $user,
        'attendance' => $attendance
      ]);
    } catch (Exception $e) {
      DB::rollback();
      // return ApiResponse::make('Attendance failed '.$e->getMessages());
      $exception = new ApiException('Attendance failed ' . $e->getMessages(), null, 403, 403, 2001);
      return ApiResponse::exception($exception);
    }
  }


  public function storeAttendance(APIStoreAttendance $request)
  {
    try {
      DB::beginTransaction();
      $flagErrorMail = false;
      $user = auth()->user();
      $employee = EmployeeDetails::where("user_id", $user->id)->first();

      $this->global = $this->company = Company::withoutGlobalScope('active')->where('id', $user->company_id)->first();
      $this->attendanceSettings = AttendanceSetting::first();
      $date = Carbon::createFromFormat($this->global->date_format, $request->date)->format('Y-m-d');
      // dd($this->global->date_format,$request->date,$date);
      // dd($date);
      if (isset($request->clock_out_time) && $request->clock_out_time != '') {
        $clockOut = Carbon::createFromFormat($this->global->time_format, $request->clock_out_time, $this->global->timezone);
        // $clockOut->setTimezone('UTC');
        if (isset($request->clock_out_timezone) && !empty($request->clock_out_timezone)){ 
          $timezone_clock_out = $request->clock_out_timezone;
        }else{
          $timezone_clock_out = 7;
        }
        if ($timezone_clock_out>=0) {
          $clockOut = $clockOut->subHours($timezone_clock_out);
        }else{
          $clockOut = $clockOut->addHours(abs($timezone_clock_out));
        }
        $clockOut = $clockOut->format('H:i:s');
        
        $limitTime = date("H:i:s", strtotime("17:00:00"));
        if($clockOut > $limitTime){
          $date = date("Y-m-d", strtotime($date." -1 DAY"));
        }
        //dd($clockIn." ------------ ".$date);

        $clockOut = $date . ' ' . $clockOut;
      } else {
        $clockOut = null;
      }

      $attendance = Attendance::where('user_id', $user->id)
        ->where(DB::raw('DATE(`clock_in_after_timezone`)'), $date)
        //->whereNull('clock_out_time')
        // ->orderBy('created_at', 'desc')
        ->orderBy('clock_in_after_timezone', 'desc')
        ->first();
      $clockInCount = Attendance::getTotalUserClockIn($date, $user->id);
      if (!isset($clockInCount) && empty($clockInCount)){ 
        $clockInCount = 0;
      }

      $isLate = "no";
      $clock_in_image = "";
      if ($request->hasFile('clock_in_image')) {
        $clock_in_image = Files::uploadLocalOrS3($request->clock_in_image, 'attendance', 300);
      }

      $clock_out_image = "";
      if ($request->hasFile('clock_out_image')) {
        $clock_out_image = Files::uploadLocalOrS3($request->clock_out_image, 'attendance', 300);
      }

      $office = Office::where("name", $request->working_from)->first();
      // 16/12 cleming kasih pengecekan office ketika absen keluar
      if(isset($request->clock_out_from) && !empty($request->clock_out_from)){
        $office = Office::where("name", $request->clock_out_from)->first();
      }

      if (isset($office) && !empty($office)) {
        if($office->is_kapal == 0){
          if($employee->is_abk == 1){
                return ApiResponse::make('Absen gagal. ABK tidak memiliki ijin untuk absen di kantor. Mohon menghubungi admin.', ['error' => $this->errorCode]);
          }

          $input_latitude = isset($request->clock_in_latitude)?$request->clock_in_latitude:"";
          $input_longitude = isset($request->clock_in_longitude)?$request->clock_in_longitude:"";

          if(empty($input_latitude) || empty($input_longitude)){
            // cek koordinat absen pulang 
            $input_latitude = isset($request->clock_out_latitude)?$request->clock_out_latitude:"";
            $input_longitude = isset($request->clock_out_longitude)?$request->clock_out_longitude:"";
            if(empty($input_latitude) || empty($input_longitude)){
              return ApiResponse::make('Posisi koordinat tidak ditemukan, silahkan memastikan GPS telah menyala dan mencoba beberapa saat lagi', ['error' => $this->errorCode]);
            }
          }

          // $distance = $this->distance($request->clock_in_latitude, $request->clock_in_longitude, $office->latitude, $office->longitude, "K");
          $distance = $this->distance($input_latitude, $input_longitude, $office->latitude, $office->longitude, "K");
          $distance = $distance * 1000;
          $radius = isset($office->radius) ? $office->radius : 10; //by default 10 meter
          if ($distance > $radius) {
            // cek bssid dulu
            // $request->bssid berupa array
            if (isset($request->bssid) && !empty($request->bssid)) {
              $setBSSID =  json_decode($request->bssid);
              $getOfficeWifi = OfficeWifi::whereIn('bssid', $setBSSID)
                ->first();
              if (isset($getOfficeWifi) && !empty($getOfficeWifi)) {
                // ganti office dengan office bssid
                $office = Office::find($getOfficeWifi->office_id);
                $request->clock_in_from = $office->name;
                $request->clock_out_from = $office->name;
              } else {
                $distance = number_format($distance, 2);
                // return ApiResponse::make('Wifi di sekitar anda tidak terdaftar di sistem. Hubungi admin untuk memastikan semua wifi di office sudah terdaftar.', ['error' => $this->errorCode, 'request_wifi' => $request->bssid, 'payload' => json_encode($request->all())]);
                return ApiResponse::make('GPS and tidak terdeteksi disekitar area WIFI. Lakukan Minimize aplikasi lalu matikan, nyalakan Kembali GPS dan coba absen lagi.', ['error' => $this->errorCode, 'request_wifi' => $request->bssid, 'payload' => json_encode($request->all())]);
              }
            } else {
              $distance = number_format($distance, 2);
              return ApiResponse::make('List wifi tidak didapatkan. Mohon menunggu sebentar, lalu mencoba lagi', ['error' => $this->errorCode, 'request_wifi' => $request->bssid, 'payload' => json_encode($request->all())]);
              //return ApiResponse::make('Absen gagal. Anda tidak berada dalam radius kantor (distance : ' . $distance . ' meter)', []);
            }

            // cek bssid dulu
          }
          
        }
        else{
          if($employee->is_abk == 0){
                return ApiResponse::make('Absen gagal. Karyawan kantor tidak memiliki ijin untuk absen di kapal. Mohon menghubungi admin.', ['error' => $this->errorCode]);
          }
            $scheduleExist = ScheduleKapal::where("user_id", $user->id)->where("kapal_id", $office->id)->where("date_start", "<=", $date)->where("date_end", ">=", $date)->where("status", "approved")->first();    
            if(isset($scheduleExist) && !empty($scheduleExist)){
              $kapal = Office::find($scheduleExist->kapal_id);
              //if($kapal->name != $request->working_from){
                

              

                //return ApiResponse::make('Jadwal ABK untuk kapal '.$request->working_from.' tidak tersedia. Harap menghubungi PC untuk mengedit penjadwalan kapal (Saat ini anda terassign di '.$kapal->name.' )', ['payload' => json_encode($request->all())]);
              //}
            }
            else{
                //return ApiResponse::make('Kapal tidak ditemukan. Harap menghubungi PC untuk mengedit penjadwalan kapal', ['payload' => json_encode($request->all())]);
              
                //notif atasan
                $loginEmployee = EmployeeDetails::where('user_id', '=', $user->id)->first();
                $json = json_decode($loginEmployee->permission_require);
                if(isset($json[0]) && !empty($json[0])){
                  $atasan = User::find($json[0]);
                  $message = "ABK ".$user->name." tidak dapat absen di kapal ".$request->working_from.". Segera konfirmasi kepada ABK yang bersangkutan.";
                  try {
                    $atasan->notify(new CustomMessageNotif($user, $message, "TIDAK-DAPAT-ABSEN-ABK",$atasan,false));
                  } catch (\Throwable $th) {
                    $flagErrorMail = true;
                  }
                  // $atasan->notify(new CustomMessageNotif($user, $message, "TIDAK-DAPAT-ABSEN-ABK"));
                  //$task = Task::find(23);
                  //$atasan->notify(new TaskLate($task, 7));
                }
              
                if ($flagErrorMail) {
                  return ApiResponse::make(
                    'Absensi gagal. Jadwal ABK untuk kapal '.$request->working_from.' tidak tersedia. Harap menghubungi PC untuk cek penjadwalan kapal, Email error silahkan hubungi developer', 
                    ['error' => $this->errorCode, 
                    'payload' => json_encode($request->all())]
                  );
                }else{
                  return ApiResponse::make('Absensi gagal. Jadwal ABK untuk kapal '.$request->working_from.' tidak tersedia. Harap menghubungi PC untuk cek penjadwalan kapal', ['error' => $this->errorCode, 'payload' => json_encode($request->all())]);
                }
              
            }
        }
      }

      
      $wifi = null;
      //26/08 cleming, kasih pengecekan officenya ada gk
      if(isset($office->id) && !empty($office->id)){
        //$office->wifi = [];
        $wifi = OfficeWifi::where("office_id", $office->id)->get();
        $office->wifi = $wifi;
      }
      //26/08 cleming, kasih pengecekan officenya ada gk
      
      $cluster = DB::table('cluster_working_hours')->where('id', $employee->cluster_working_hour_id)->first();
      
      if ($request->working_from == "WFH") {
        $loginEmployee = EmployeeDetails::where('user_id', '=', $user->id)->first();
        $home_lat = $loginEmployee->latitude;
        $home_long = $loginEmployee->longitude;
        $distance = $this->distance($request->clock_in_latitude, $request->clock_in_longitude, $home_lat, $home_long, "K");
        $distance = $distance * 1000;
        $radius = 50;
        if ($distance > $radius) {
          $distance = number_format($distance, 2);
          return ApiResponse::make('Absen gagal. Anda terlalu jauh dari rumah/koordinat rumah belum diset. Silahkan hubungi admin (distance : ' . $distance . ' meter)', []);
        }
      }
      if (isset($attendance) && is_null($attendance->clock_out_time) && !empty($request->clock_out_time)) {
      // if (isset($attendance) && is_null($attendance->clock_out_time)) {
        $clock_out_from = isset($request->clock_out_from)?$request->clock_out_from:null; 
        // $datetime_request = Carbon::parse($request->clock_out_time);
        // 00:01
        $datetime_request = Carbon::createFromFormat('g:i A',$request->clock_out_time);
        // BUG DISINI
        $date_request = Carbon::parse($request->date);
        // cek hari ini apakah ada ijin
        // dd($datetime_request->copy()->format('Y-m-d'));
        $check_leave = Leave::where('user_id',$user->id)
          ->where(function($query) use ($date_request){
            $query->whereDate('leaves.leave_date','>=',$date_request->copy()->format('Y-m-d'))
            ->whereDate('leaves.leave_date_end','<=',$date_request->copy()->format('Y-m-d'));
          })->count();
        if ($timezone_clock_out>=0) {
          $date_after_timezone = Carbon::parse($clockOut)->addHours($timezone_clock_out);
        }else{
          $date_after_timezone = Carbon::parse($clockOut)->subHours(abs($timezone_clock_out));
        }
        if ($check_leave==0) {
          if ($cluster->type=='daily') {
            // get date by current request
            $datetime_request_day = $date_request->copy()->format('l');
            $datetime_request_day = getDayInIndonesia($datetime_request_day);
            // $json = json_decode($cluster->json,true);

            // get cluster function from other controller
            $clusterController = new ClusterWorkingHourController;
            $json = $clusterController->getJsonDaily($cluster->json, true);
  
            $jam_pulang = $json[$datetime_request_day]['jam_pulang'];
            $datetime_jam_pulang_request = $datetime_request->copy()->format('H:i');
            $datetime_jam_pulang_cluster = Carbon::parse($jam_pulang);
            // get general setting
            $attendanceSetting = AttendanceSetting::where('company_id',$user->company_id)->first();
            $datetime_jam_pulang_cluster_minus_setting = $datetime_jam_pulang_cluster->copy()->subMinutes($attendanceSetting->toleransi_absen_pulang)->format('H:i');
            // dd($datetime_jam_pulang_request<=$datetime_jam_pulang_cluster_minus_setting,"$datetime_jam_pulang_request<=$datetime_jam_pulang_cluster_minus_setting");
            $datetime_jam_pulang_request_new = $date_after_timezone->copy()->format('H:i');
            // if ($datetime_jam_pulang_request<=$datetime_jam_pulang_cluster_minus_setting) {
              // dd($datetime_jam_pulang_request_new<=$datetime_jam_pulang_cluster_minus_setting,"$datetime_jam_pulang_request_new<=$datetime_jam_pulang_cluster_minus_setting");
            if ($datetime_jam_pulang_request_new<=$datetime_jam_pulang_cluster_minus_setting) {
              // jika absen pulang dibawah jadwal cluster
              // get leave type
              $leaveType = LeaveType::where('type_name','Ijin')->first();
              // insert into leave
              $insertLeave = new Leave;
              $insertLeave->company_id = $user->company_id;
              $insertLeave->user_id = $user->id;
              $insertLeave->leave_type_id = $leaveType->id;
              $insertLeave->duration = 'single';
              $insertLeave->leave_date = $date_request;
              $insertLeave->leave_date_end = $date_request;
              $insertLeave->reason = 'pulang tidak ijin';
              $insertLeave->status = 'approved_atasan_dua';
              $insertLeave->masking_status = 'done';
              $insertLeave->approved_by = 'system';
              $insertLeave->save();
  
              // insert into leave ijin
              $leaveIjin = new LeaveIjin;
              $leaveIjin->leave_id = $insertLeave->id;
              $leaveIjin->alasan_ijin = 'pulang-awal';
              $leaveIjin->is_approved_hrd = 1;
              $leaveIjin->approved_by = 'system';
              $leaveIjin->approved_at = $date_request;
              $leaveIjin->approved_at = $date_request;
              $leaveIjin->save();
            }
          }else{
  
            $datetime_jam_pulang_request = $datetime_request->copy()->format('H:i');
            $datetime_jam_pulang_cluster = Carbon::parse($cluster->end_hour);
            // get general setting
            $attendanceSetting = AttendanceSetting::where('company_id',$user->company_id)->first();
            $datetime_jam_pulang_cluster_minus_setting = $datetime_jam_pulang_cluster->copy()->subMinutes($attendanceSetting->toleransi_absen_pulang)->format('H:i');
            $datetime_jam_pulang_request_new = $date_after_timezone->copy()->format('H:i');
            // if ($datetime_jam_pulang_request<=$datetime_jam_pulang_cluster_minus_setting) {
            if ($datetime_jam_pulang_request_new<=$datetime_jam_pulang_cluster_minus_setting) {
              // jika absen pulang dibawah jadwal cluster
              // get leave type
              $leaveType = LeaveType::where('type_name','Ijin')->first();
              // insert into leave
              $insertLeave = new Leave;
              $insertLeave->company_id = $user->company_id;
              $insertLeave->user_id = $user->id;
              $insertLeave->leave_type_id = $leaveType->id;
              $insertLeave->duration = 'single';
              $insertLeave->leave_date = $datetime_request;
              $insertLeave->leave_date_end = $datetime_request;
              $insertLeave->reason = 'pulang tidak ijin';
              $insertLeave->status = 'approved_atasan_dua';
              $insertLeave->is_final = 1;
              $insertLeave->masking_status = 'done';
              $insertLeave->approved_by = 'system';
              $insertLeave->save();
  
              // insert into leave ijin
              $leaveIjin = new LeaveIjin;
              $leaveIjin->leave_id = $insertLeave->id;
              $leaveIjin->alasan_ijin = 'pulang-awal';
              $leaveIjin->is_approved_hrd = 1;
              $leaveIjin->approved_by = 'system';
              $leaveIjin->approved_at = $datetime_request;
              $leaveIjin->approved_at = $datetime_request;
              $leaveIjin->save();
            }
          }
        }
        // absen pulang
        
        $attendance->update([
          'user_id' => $user->id,
          'clock_out_time' => $clockOut,
          'clock_out_timezone' => $timezone_clock_out,
          'clock_out_after_timezone' => $date_after_timezone,
          'clock_out_from' => $clock_out_from,
          'clock_out_ip' => $request->clock_out_ip,
          'clock_out_image' => $clock_out_image,
          'clock_out_latitude' => $request->clock_out_latitude,
          'clock_out_longitude' => $request->clock_out_longitude,
          'json_clock_out' => json_encode($request->all())
        ]);
        
      } else {
        // check clock out emty or not
        // if empty then the user need to clock out first 
        if (!empty($attendance)) {
          if (empty($attendance->clock_out_time)) {
            return ApiResponse::make('Anda telah melakukan absen masuk hari ini', ["payload" => $request->all()]);
          }
  
          if(!isset($request->clock_in_time)){
            return ApiResponse::make('Absensi masuk tanpa membawa clock in time', ["payload" => $request->all()]);
          }
        }

        // Check maximum attendance in a day
        // dd($clockInCount < $this->attendanceSettings->clockin_in_day,$clockInCount ."<". $this->attendanceSettings->clockin_in_day);
        if ($clockInCount < $this->attendanceSettings->clockin_in_day) {
          if (empty($request->clock_in_time) && $clockInCount==0) {
            return ApiResponse::make('Anda belum absensi masuk untuk hari ini', ["payload" => $request->all()]);
          }
          $clockIn = Carbon::createFromFormat($this->global->time_format, $request->clock_in_time, $this->global->timezone);
          if (isset($request->clock_in_timezone) && !empty($request->clock_in_timezone)){ 
            $timezone = $request->clock_in_timezone;
          }else{
            $timezone = 7;
          }
          if ($timezone>=0) {
            $clockIn = $clockIn->subHours($timezone);
          }else{
            $clockIn = $clockIn->addHours(abs($timezone));
          }
          // dd($timezone);
          // $clockIn->setTimezone($timezone);
          $clockIn = $clockIn->format('H:i:s');
      
          $limitTime = date("H:i:s", strtotime("17:00:00"));
          if($clockIn > $limitTime){
            $date = date("Y-m-d", strtotime($date." -1 DAY"));
          }
          //dd($clockIn." ------------ ".$date);
          
          //01.02 == Handling double input attendance when clockin
          $attendanceJustClockIn = Attendance::where("user_id", $user->id)->where("clock_in_time", $date . ' ' . $clockIn)->first();
          if(!empty($attendanceJustClockIn)){
            return ApiResponse::make('Attendance already exist!', [
              'user' => $user,
              'attendance' => $attendanceJustClockIn,
              'employee' => $employee,
              'office' => $office,
              'wifi' => $wifi,
              'cluster' => $cluster
            ]);
          }
          //01.02 == Handling double input attendance when clockin
          if ($timezone>=0) {
            $date_after_timezone = Carbon::parse($date . ' ' . $clockIn)->addHours($timezone);
          }else{
            $date_after_timezone = Carbon::parse($date . ' ' . $clockIn)->subHours(abs($timezone));
          }
          // pengecekan update or created
          $attendance = Attendance::updateOrCreate([
            'user_id' => $user->id,
            'clock_in_time' => $date . ' ' . $clockIn,
            'clock_in_timezone' => $timezone,
            'clock_in_after_timezone' => $date_after_timezone,
          ],[
            'user_id' => $user->id,
            'clock_in_image' => $clock_in_image,
            'clock_in_time' => $date . ' ' . $clockIn,
            'clock_in_timezone' => $timezone,
            'clock_in_after_timezone' => $date_after_timezone,
            'clock_in_ip' => $request->clock_in_ip,
            'working_from' => $request->working_from,
            'late' => $isLate,
            'half_day' => "no",
            'your_body_temperature' => isset($request->your_body_temperature) ? $request->your_body_temperature : "-",
            'is_pcr_test' => isset($request->is_pcr_test) ? $request->is_pcr_test : "-",
            'is_wash_your_hands_before_work' => isset($request->is_wash_your_hands_before_work) ? $request->is_wash_your_hands_before_work : "-",
            'clock_in_latitude' => $request->clock_in_latitude,
            'clock_in_longitude' => $request->clock_in_longitude,
            'cluster_meta' => json_encode($cluster),
            'json_clock_in' => json_encode($request->all())
          ]);
          // insert into attendance_pertanyaans
          $listPertanyaan = json_decode($request->pertanyaan);
          $listJawaban = json_decode($request->jawaban);
          if (isset($listPertanyaan) && !empty($listPertanyaan)) {
            for ($i = 0; $i < count($listPertanyaan); $i++) {
              $attendancePertanyaan = new AttendancePertanyaan;
              $attendancePertanyaan->attendance_id = $attendance->id;
              $attendancePertanyaan->pertanyaan_id = $listPertanyaan[$i];
              $attendancePertanyaan->jawaban = $listJawaban[$i];
              $attendancePertanyaan->save();
            }
          }
        } else {
          $attendance = Attendance::where('user_id', $user->id)
            ->where(DB::raw('DATE(`clock_in_time`)'), $date)
            ->first();

          // return ApiResponse::make('Already checkin on this day', [
          return ApiResponse::make('Anda sudah melakukan Absensi pada hari ini, Cek history absensi untuk melihat absensi masuk/pulang yang sudah dilakukan.', [
            'user' => $user,
            'attendance' => $attendance,
            'employee' => $employee,
            'office' => $office,
            'wifi' => $wifi,
            'cluster' => $cluster
          ]);
        }
      }
      DB::commit();
      return ApiResponse::make('Attendance success', [
        'user' => $user,
        'attendance' => $attendance,
        'employee' => $employee,
        'office' => $office,
        'wifi' => $wifi,
        'cluster' => $cluster
      ]);
    } catch (Exception $e) {
      DB::rollback();
      // return ApiResponse::make('Attendance failed '.$e->getMessages());
      $exception = new ApiException('Attendance failed ' . $e->getMessages(), null, 403, 403, 2001);
      return ApiResponse::exception($exception);
    }
  }

  function distance($lat1, $lon1, $lat2, $lon2, $unit)
  {
    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
      return 0;
    } else {
      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      $unit = strtoupper($unit);

      if ($unit == "K") {
        return ($miles * 1.609344);
      } else if ($unit == "N") {
        return ($miles * 0.8684);
      } else {
        return $miles;
      }
    }
  }

  public function checkAttendance(APIStoreAttendance $request)
  {
    try {
      DB::beginTransaction();
      $user = auth()->user();

      $this->global = $this->company = Company::withoutGlobalScope('active')->where('id', $user->company_id)->first();
      $this->attendanceSettings = AttendanceSetting::first();
      $date = Carbon::createFromFormat($this->global->date_format, $request->date)->format('Y-m-d');
      if ($request->clock_out_time != '') {
        $clockOut = Carbon::createFromFormat($this->global->time_format, $request->clock_out_time, $this->global->timezone);
        $clockOut->setTimezone('UTC');
        $clockOut = $clockOut->format('H:i:s');
        $clockOut = $date . ' ' . $clockOut;
      } else {
        $clockOut = null;
      }

      // dicek tgl hari ini
      // diasumsikan server +7
      $now = Carbon::now()->addHours(7);
      $attendance = Attendance::where('user_id', $user->id)
        //->where(DB::raw('DATE(`clock_in_time`)'), $date)
        //->whereNull('clock_out_time')
        ->whereDate('clock_in_after_timezone', $now->copy()->format('Y-m-d'))
        ->orderBy('created_at', 'desc')
        ->first();
      if (isset($attendance) && is_null($attendance->clock_out_time)) {
        

        //cek sudah ngelog hari ini?
        $logExist = ProjectTimeLog::where("user_id", $user->id)
          ->where('end_time', '>', $attendance->clock_in_time)
          ->orWhere('end_time', '>', date("Y-m-d 00:00:00", strtotime($date)))
          ->get();
        if (count($logExist) > 0)
          return ApiResponse::make('Belum clock out, sudah mengerjakan tugas', [
            'status' => 1,
            'user' => $user,
            'attendance' => $attendance
          ]);
        else
          return ApiResponse::make('Belum clock out, belum mengerjakan tugas sejak clock in', [
            'status' => 4,
            'user' => $user,
            'attendance' => $attendance
          ]);
        
        /*
        $attendance = Attendance::where('user_id', $user->id)
          ->where(DB::raw('DATE(`clock_in_time`)'), $date)
          ->whereNotNull('clock_out_time')
          ->first();
        if (!is_null($attendance)) {
          return ApiResponse::make('Already clock in and clock out this day', [
            'status' => 2,
            'user' => $user,
            'attendance' => $attendance
          ]);
        }
        return ApiResponse::make('Haven\'t clock in on this day', [
          'status' => 0,
          'user' => $user,
        ]);
        */
      } else {
          // TODO:
          // dikasi pengecekan apakah tugas sudah di review 
          // status = in_review dan checker_user_id tidak null berarti sudah di review
          // klo gak di review selama 2x24 jam gak bisa login
          // 2 x 24 jam dilihat dari updated at di project time log
          $checkTaskInReview = ProjectTimeLog::where("user_id", $user->id)
            ->where('end_time', '>', $now)
            ->where('status','in_review')
            ->where('checker_user_id',null)
            ->first();
            // dd($checkTaskInReview);
            if ($checkTaskInReview) {
              // masuk sini berarti belum di review
              // check apakah tidak direview selama 2x24 jam dari updated at
              // created at dan updated at always gmt 0
              // add 7 hours to make gmt+7
              $updated_at = Carbon::parse($checkTaskInReview->updated_at)->addHours(7);
              // get diff in hours
              $diffInHour = $now->diffInHours($updated_at);
              if ($diffInHour>48) {
                // if more than 48 hours user cant clock in
                return ApiResponse::make('Clock in tidak tersedia, tugas belum direview lebih dari 2x24 jam (48 jam)', [
                  'status' => 3,
                  'user' => $user,
                  'attendance' => $attendance
                ]);
              }
            }
          return ApiResponse::make('Clock in tersedia', [
            'status' => 2,
            'user' => $user,
            'attendance' => $attendance
          ]);
        /*
        //cek sudah ngelog hari ini?
        $logExist = ProjectTimeLog::where("user_id", $user->id)
          ->where(DB::raw('DATE(`end_time`)'), '>', $attendance->clock_in_time)
          ->get();
        if (count($logExist) > 0)
          return ApiResponse::make('Already clock in this day', [
            'status' => 1,
            'user' => $user,
            'attendance' => $attendance
          ]);
        else
          return ApiResponse::make('Already clock in this day but no task logged', [
            'status' => 4,
            'user' => $user,
            'attendance' => $attendance
          ]);
          */
      }

      DB::commit();
      return ApiResponse::make('Attendance success', [
        'user' => $user,
        'attendance' => $attendance
      ]);
    } catch (Exception $e) {
      DB::rollback();
      // return ApiResponse::make('Attendance failed '.$e->getMessages());
      $exception = new ApiException('Attendance failed ' . $e->getMessages(), null, 403, 403, 2001);
      return ApiResponse::exception($exception);
    }
  }
  public function checkPosition(APIRequest $request)
  {
    $request->validate([
      'bssid' => 'required',
    ]);
    $user = auth()->user();
    try {
      // check sudah absen atau belum
      $now = Carbon::now()->format('Y-m-d');
      $attendance = Attendance::where('user_id', $user->id)
        // ->where(DB::raw('DATE(`clock_in_time`)'), $now)
        ->where(DB::raw('DATE(`clock_in_after_timezone`)'), $now)
        ->whereNull('clock_out_time')
        ->first();
      if (isset($attendance) && !empty($attendance)) {
        // sudah absen
        // check last absen office
        $office = Office::where("name", $attendance->working_from)->first();
        $setBSSID =  json_decode($request->bssid);
        $getOfficeWifi = OfficeWifi::where('office_id', $office->id)
          ->whereIn('bssid', $setBSSID)
          ->first();
        if (isset($getOfficeWifi) && !empty($getOfficeWifi)) {
          return ApiResponse::make('result', [
            'in_radius' => true,
            'office' => $office
          ]);
        } else {
          return ApiResponse::make('result', [
            'in_radius' => false,
            'office' => $office
          ]);
        }
      } else {
        // belum absen
        // check bssid exist
        $getOfficeWifi = OfficeWifi::whereIn('bssid', $request->bssid)
          ->first();
        if (isset($getOfficeWifi) && !empty($getOfficeWifi)) {
          // get office
          $office = Office::find($getOfficeWifi->office_id);
          return ApiResponse::make('result', [
            'in_radius' => true,
            'office' => $office
          ]);
        } else {
          return ApiResponse::make('result', [
            'in_radius' => false,
            'office' => null,
          ]);
        }
      }
    } catch (\Throwable $e) {
      // return ApiResponse::make('Attendance failed '.$e->getMessages());
      $exception = new ApiException('Attendance failed ' . $e->getMessage(), null, 403, 403, 2001);
      return ApiResponse::exception($exception);
    }
  }
  
  
  public function fixAttendancesTime(){

    DB::beginTransaction();
    try {
      $attendances = Attendance::all();
      foreach ($attendances as $attendance) {
        # code...
        if(isset($attendance->clock_in_time) && !empty($attendance->clock_in_time)){
          $time = date("H:i:s", strtotime($attendance->clock_in_time));

          $limitTime = date("H:i:s", strtotime("17:00:00"));
          if($time > $limitTime){
            $attendance->clock_in_time = date("Y-m-d H:i:s", strtotime($attendance->clock_in_time." -1 DAY"));
          }
        }

        if(isset($attendance->clock_out_time) && !empty($attendance->clock_out_time)){
          $time = date("H:i:s", strtotime($attendance->clock_out_time));

          $limitTime = date("H:i:s", strtotime("17:00:00"));
          if($time > $limitTime){
            $attendance->clock_out_time = date("Y-m-d H:i:s", strtotime($attendance->clock_out_time." -1 DAY"));
          }
        }
        $attendance->save();

      }
      
      //DB::commit();
    } catch (Exception $e) {
      DB::rollback();
    }
  }
  public function getAllOffice(APIStoreAttendance $request)
  {

    try {
      DB::beginTransaction();
      $user = auth()->user();


      $office = Office::where("company_id", $user->company_id)->get();
      if (empty($office)) {
        return ApiResponse::make('Office not found', []);
      }

      return ApiResponse::make('Offices found', [
        'office' => $office
      ]);
    } catch (Exception $e) {
      DB::rollback();
      // return ApiResponse::make('Attendance failed '.$e->getMessages());
      $exception = new ApiException('Attendance failed ' . $e->getMessages(), null, 403, 403, 2001);
      return ApiResponse::exception($exception);
    }
  }
}
