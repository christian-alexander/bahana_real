<?php

namespace Modules\RestAPI\Http\Controllers;


use App\User;
use Carbon\Carbon;
use Froiden\RestAPI\ApiController;
use Froiden\RestAPI\ApiResponse;
use Froiden\RestAPI\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\RestAPI\Http\Requests\Auth\EmailVerifyRequest;
use Modules\RestAPI\Http\Requests\Auth\ForgotPasswordRequest;
use Modules\RestAPI\Http\Requests\Auth\LoginRequest;
use Modules\RestAPI\Http\Requests\Auth\LogoutRequest;
use Modules\RestAPI\Http\Requests\Auth\RefreshTokenRequest;
use Modules\RestAPI\Http\Requests\Auth\ResendVerificationMailRequest;
use Modules\RestAPI\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\User\UpdateProfile;
use Illuminate\Support\Facades\File;
use App\Helper\Files;
use App\EmployeeDetails;
use Modules\RestAPI\Http\Requests\Auth\UpdateProfileRequest;
use Illuminate\Support\Facades\Password;
use App\Traits\SmtpSettings;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Wilayah;
use App\SubCompany;
use App\Designation;
use App\Team;
use App\Cabang;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\API\APIRequest;
use App\Leave;
use App\LeaveType;
use Modules\RestAPI\Entities\Attendance;
use App\ProjectTimeLog;
use App\Notifications\TestPush;
use Tymon\JWTAuth\JWTAuth;
use App\Office;
use App\OfficeWifi;
use App\GeneralSetting;
use App\Notification;
use App\ScheduleKapal;
use App\Notifications\CustomMessageNotif;

class AuthController extends ApiBaseController
{

    use SendsPasswordResetEmails, SmtpSettings;

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function login(LoginRequest $request)
    {
        // Modifications to this function may also require modifications to
        $email = $request->get('email');
        $password = $request->get('password');

        $claims = ['exp' => (int) Carbon::now()->addYear()->getTimestamp(), 'remember' => 1, 'type' => 1];

        $cuser = User::where([
            ['email', '=', $email],
            ['login', '!=', 'disable']
        ])->first();
        if($cuser){
            $token = auth()->claims($claims)->attempt(['email' => $email, 'password' => $password]);
            if ($token) {
                $user = User::where([
                    ['email', '=', $email]
                ])->first();
                //$a['lastToken'] = auth()->tokenById($user->id);
                //$a['token'] = $token;
                //dd($a);
                //auth()->setToken($token);
                //auth()->invalidate();

                if ($user && $user->status === 'deactive') {
                    $exception = new ApiException('User account disabled', null, 403, 403, 2015);
                    return ApiResponse::exception($exception);
                }

                /** @var Admin $user */
                $user = auth()->user();
                if (isset($request->player_id) && !empty($request->player_id)) {
                    $loginUser = User::find($user->id);
                    $loginUser->onesignal_player_id = $request->player_id;
                    $loginUser->save();

                    // check player_id duplicate with other user
                    $checkPlayerId = User::where('onesignal_player_id', $request->player_id)
                        ->where('id', '!=', $user->id)
                        ->get();
                    if (count($checkPlayerId) > 0) {
                        foreach ($checkPlayerId as $val) {
                            $val->onesignal_player_id = null;
                            $val->save();
                        }
                    }
                }
                $payload = auth()->payload();

                $expire = Carbon::createFromTimestamp($payload('exp'))->format('Y-m-d\TH:i:sP');
                $employee = EmployeeDetails::leftjoin('designations as d', 'd.id', 'employee_details.designation_id')
                    ->where("user_id", $user->id)
                    ->selectRaw('employee_details.*,d.name as jabatan')
                    ->first();
                $wilayah = Wilayah::find($employee->wilayah_id)->first();
                $subcompany = SubCompany::find($employee->sub_company_id)->first();
                $designation = Designation::find($employee->designation_id)->first();
                $team = Team::find($employee->department_id)->first();
                $cabang = Cabang::find($employee->cabang_id)->first();

                $date = date("Y-m-d", strtotime("NOW"));
                $attendance = Attendance::where('user_id', $user->id)
                    // ->where(DB::raw('DATE(`clock_in_time`)'), $date)
                    ->where(DB::raw('DATE(`clock_in_after_timezone`)'), $date)
                    ->whereNull('clock_out_time')
                    ->orderBy('id', 'desc')
                    ->first();
                $office = null;
                $wifi = null;
                if (isset($attendance->working_from) && !empty($attendance->working_from)) {
                    $office = Office::where("name", $attendance->working_from)->first();
                    if (isset($office->id) && !empty($office->id)) {
                        $office->wifi = [];
                        $wifi = OfficeWifi::where("office_id", $office->id)->get();
                        $office->wifi = $wifi;
                    }
                }
                // insert token into users
                $setToken = User::find($user->id);
                $setToken->token = $token;
                $setToken->save();
                $cluster = DB::table('cluster_working_hours')->where('id', $employee->cluster_working_hour_id)->first();

                return ApiResponse::make('Logged in successfully', [
                    'token' => $token,
                    'user' => $user->load('roles', 'roles.perms', 'roles.permissions'),
                    'expires' => $expire,
                    'expires_in' => auth()->factory()->getTTL() * 60,
                    'employee' => $employee,
                    'wilayah' => $wilayah,
                    'subcompany' => $subcompany,
                    'designation' => $designation,
                    'team' => $team,
                    'cabang' => $cabang,
                    'attendance' => $attendance,
                    'office' => $office,
                    'wifi' => $wifi,
                    'cluster' => $cluster
                ]);
            }

            $exception = new ApiException('Wrong credentials provided', null, 403, 403, 2001);
        }else{
            $exception = new ApiException('Email tidak terdaftar.', null, 403, 403, 2001);
        }


        return ApiResponse::exception($exception);
    }
    public function getProfile(APIRequest $request)
    {
        $user = auth()->user();
        $employee = EmployeeDetails::where("user_id", $user->id)->first();
        $wilayah = Wilayah::where('id',$employee->wilayah_id)->first();
        $subcompany = SubCompany::where('id',$employee->sub_company_id)->first();
        $designation = Designation::where('id',$employee->designation_id)->first();      
        $team = Team::where('id',$employee->department_id)->first();
        $cabang = Cabang::where('id',$employee->cabang_id)->first();

        return ApiResponse::make('Get profile success', [
            'user' => $user,
            'employee' => $employee,
            'wilayah' => $wilayah,
            'subcompany' => $subcompany,
            'designation' => $designation,
            'team' => $team,
            'cabang' => $cabang,
        ]);
    }

    public function logout(LogoutRequest $request)
    {
        $flagErrorMail = false;
        // update token
        $user = User::find(user()->id);

        //26.02.21 -- notif atasan kalau logout sebelum jam kantor berakhir
        $loginEmployee = EmployeeDetails::where('user_id', '=', $user->id)->first();
        $dayOfToday = getDayInIndonesia(date('l', strtotime("NOW")));

        // get json
        $timeNow = date('H:i:s', strtotime("NOW +7 HOURS"));
      	$cluster_working_hours = DB::table('cluster_working_hours')->where('id', $loginEmployee->cluster_working_hour_id)->first();
        $json_cluster = json_decode($cluster_working_hours->json, true);
        // $office_start_time = date('H:i:s', strtotime($json_cluster[$dayOfToday]['jam_masuk']));
        $office_end_time = date('H:i:s', strtotime($json_cluster[$dayOfToday]['jam_pulang']));
      
        if($timeNow < $office_end_time){
            $json = json_decode($loginEmployee->permission_require);
            if(isset($json[0]) && !empty($json[0])){
                $message = "User ".$user->name." logout sebelum jam pulang cluster. (".$timeNow.")";
                $atasan = User::find($json[0]);
                try {
                    $atasan->notify(new CustomMessageNotif($user, $message, "LOGOUT",$atasan));
                } catch (\Throwable $th) {
                    $flagErrorMail = true;
                }
                // $atasan->notify(new CustomMessageNotif($user, $message, "LOGOUT"));
              
            }    
        }
        //26.02.21 -- notif atasan kalau logout sebelum jam kantor berakhir

            
        $user->token = null;
        $user->onesignal_player_id  = null;
        $user->save();

        auth()->invalidate();
        if ($flagErrorMail) {
            return ApiResponse::make('Token invalidated successfully, Email error silahkan hubungi developer');
        }else{
            return ApiResponse::make('Token invalidated successfully');
        }
        // return ApiResponse::make('Token invalidated successfully');
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $email = $request->email;
        // $smtp = new SmtpSettings;
        $this->setMailConfigs();

        $user = User::where('email', $email)->first();
        if ($user) {
            // $code = Str::random(60);
            // $user->password_reset_token = $code;
            // $user->save();

            // dispatch(new SendForgotPasswordEmail($user));


            $response = Password::broker()->sendResetLink(
                $request->only('email')
            );
            if ($response == Password::RESET_LINK_SENT) {
                return ApiResponse::make('Password reset url delivered to ' . $email . ' successfully', [
                    'user' => $user
                ]);
            }
        }

        $exception = new ApiException('Wrong credentials provided', null, 403, 403, 2001);
        return ApiResponse::exception($exception);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $passwordResetToken = $request->password_reset_token;
        $password = $request->password;

        $user = Employee::where('password_reset_token', $passwordResetToken)->first();
        $hash = \Hash::make($password);
        $user->password = $hash;
        $user->password_reset_token = null;
        $user->save();

        return ApiResponse::make('Password reset successful');
    }

    public function refresh(RefreshTokenRequest $request)
    {
        config([
            'jwt.blacklist_enabled' => false
        ]);
        try {
            $newToken = auth()->refresh();
            $payload = auth()->payload();
            $user = auth()->user();
            $expire = Carbon::createFromTimestamp($payload('exp'))->format('Y-m-d\TH:i:sP');


            if ($user->status === 'deactive') {
                throw new ApiException('User account disabled', null, 403, 403, 2015);
            }

            return ApiResponse::make('Token refreshed successfully', [
                'token' => $newToken,
                'expires' => $expire
            ]);
        } catch (ApiException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ApiException('Provided token is invalid', $e, 403, 403, 2003);
        }
    }


    public function verify(EmailVerifyRequest $request)
    {
        $user = Employee::where('email_verification_token', $request->token)->whereNotNull('email_verification_token')->first();

        if ($user) {
            DB::beginTransaction();

            $user->email_verification_token = null;
            $user->email_verified = 'yes';
            $user->save();

            $user->company->company_email_verified = 'yes';
            $user->company->save();

            event(new EmailVerificationSuccessEvent($user->company, $user));
            DB::commit();

            return ApiResponse::make('Success', ['status' => 'success']);
        }

        return ApiResponse::make('Token is expired', ['status' => 'fail']);
    }

    public function resendVerifyMail(ResendVerificationMailRequest $request)
    {
        $user = Employee::where('email', $request->email)->first();

        if ($user) {
            $user->email_verification_token = str_random(40);
            $user->save();

            event(new ResendVerificationEmailEvent($user->company, $user));

            return ApiResponse::make('Verification mail successfully send', ['status' => 'success']);
        }

        throw new ApiException('Your provided email does not exists.', null, 403, 403, 2001);
    }
    public function updateProfile(UpdateProfileRequest $request)
    {

        $user = auth()->user();

        // if(empty($request->address)){
        //     return ApiResponse::make('Address is required');
        // }
        config(['filesystems.default' => 'local']);

        $user = User::withoutGlobalScope('active')->findOrFail($user->id);
        //$user->name = $request->name;
        //$user->gender = $request->gender;
        if ($request->email != '') {
            $user->email = $request->email;
        }
        if ($request->password != '') {
            $user->password = Hash::make($request->password);
        }
        $user->mobile = $request->mobile;

        if ($request->hasFile('image')) {
            Files::deleteFile($user->image, 'avatar');
            $user->image = Files::upload($request->image, 'avatar', 300);
        }

        $user->save();

        $employee = EmployeeDetails::where('user_id', '=', $user->id)->first();
        if (empty($employee)) {
            $employee = new EmployeeDetails();
            $employee->user_id = $user->id;
        }
        $employee->address = $request->address;
        if (isset($request->latitude) && !empty($request->latitude)) {
            $employee->latitude = $request->latitude;
            $arr = json_decode($employee->additional_field);
            $arr->edit_lat_long = 0;
            $newArr = json_encode($arr);
            $employee->additional_field = $newArr;
        }
        if (isset($request->longitude) && !empty($request->longitude)) {
            $employee->longitude = $request->longitude;
            $arr = json_decode($employee->additional_field);
            $arr->edit_lat_long = 0;
            $newArr = json_encode($arr);
            $employee->additional_field = $newArr;
        }
        $employee->save();

        return ApiResponse::make('Update profile success', [
            'user' => $user
        ]);
    }
    public function changePassword(UpdateProfileRequest $request)
    {

        $user = auth()->user();

        config(['filesystems.default' => 'local']);

        $user = User::withoutGlobalScope('active')->findOrFail($user->id);
        if ($request->password != '') {
            $pass = Hash::make($request->password);
            $user->password = $pass;
        }

        $user->save();

        return ApiResponse::make('Update password success', [
            'user' => $user
        ]);
    }
    public function getEmployeePermission(APIRequest $request)
    {

        $user = auth()->user();
        $permission_json = EmployeeDetails::where('user_id', '=', $user->id)->select("additional_field")->first();
        $employee = EmployeeDetails::where("user_id", $user->id)->first();


        $date = date("Y-m-d", strtotime("NOW"));
        $attendance = Attendance::where('user_id', $user->id)
            //->where(DB::raw('DATE(`clock_in_time`)'), $date)
            ->whereNull('clock_out_time')
            ->orderBy('id', 'desc')
            ->first();
        $office = null;
        $wifi = null;
        if (isset($attendance->working_from) && !empty($attendance->working_from)) {
            $office = Office::where("name", $attendance->working_from)->first();
            if (isset($office->id) && !empty($office->id)) {
                $office->wifi = [];
                $wifi = OfficeWifi::where("office_id", $office->id)->get();
                $office->wifi = $wifi;
            }
        }

        $arr = [];
        if (isset($permission_json->additional_field) && !empty($permission_json->additional_field)) {
            $decoded = json_decode($permission_json->additional_field);
            $is_required_absence_exist = false;
            foreach ($decoded as $key => $value) {
                if ($key=='is_required_absence') {
                    $is_required_absence_exist = true;
                }
                $arr[$key] = $value;
            }
            if (!$is_required_absence_exist) {
                $arr['is_required_absence'] = "1";
            }
        }
      	//$cluster = DB::table('cluster_working_hours')->where('id', $employee->cluster_working_hour_id)->first();
      
      	$day_today = Carbon::now()->isoFormat('dddd');
        $cluster = DB::table('cluster_working_hours')->where('id', $employee->cluster_working_hour_id)->first();
        if (isset($cluster) && !empty($cluster)) {
            if ($cluster->type=='daily' && $cluster->json != null) {
                // json decode
                $full_jadwal = json_decode($cluster->json);
                $day_in_indo = getDayInIndonesia($day_today);
                $jadwal_today = isset($full_jadwal->$day_in_indo) && !empty($full_jadwal->$day_in_indo)?$full_jadwal->$day_in_indo:'';

                // asign to collection
                $cluster->full_jadwal = $full_jadwal;
                $cluster->jadwal_today = $jadwal_today;
            }
            if($cluster->type == "shift"){
              //$arrJadwal['masuk'] = date("Y-m-d", strtotime("NOW"));
              $arrJadwal['jam_masuk'] = date("Y-m-d", strtotime("NOW"))." ".date("H:i:s", strtotime($cluster->start_hour));
              //$arrJadwal['pulang'] = date("Y-m-d", strtotime("NOW +1 DAY"));
              $arrJadwal['jam_pulang'] = date("Y-m-d", strtotime("NOW +1 DAY"))." ".date("H:i:s", strtotime($cluster->end_hour));
              $cluster->jadwal_today = $arrJadwal;
            }
          	$datenow = date("Y-m-d", strtotime("NOW"));
          	$todayHoliday = DB::table("holidays")->where("date", $datenow)->where("company_id", $user->company_id)->first();
            if(isset($todayHoliday) && !empty($todayHoliday)){
              $cluster->jadwal_today = null;
              
            }
              
        }
      
      	// get general setting
        $setting = GeneralSetting::where('company_id',$user->company_id)->first();
        if (isset($setting) && !empty($setting)) {
            $getSetting = json_decode($setting->json);
            $setting->general_setting = $getSetting;
        }
      	$designation = DB::table('designations')->where('id', $employee->designation_id)->first();
      
      	$arr_atasan = [null,null,null];
      	$atasan = [null,null,null];
        $json = json_decode($employee->permission_require);
        if(isset($json[0]) && !empty($json[0])){
            $atasan_1 = User::find($json[0]);
            $arr_atasan[0] = $atasan_1;
            $atasan[0] = $atasan_1;
        }
        if(isset($json[1]) && !empty($json[1])){
            $atasan_2 = User::find($json[1]);
            $arr_atasan[1] = $atasan_2;
            $atasan[1] = $atasan_2;
        }
        if(isset($json[2]) && !empty($json[2])){
            $atasan_3 = User::find($json[2]);
            $arr_atasan[2] = $atasan_3;
            $atasan[2] = $atasan_3;
        }

      	$scheduleKapal = ScheduleKapal::leftJoin('office', 'office.id', 'schedule_kapal.kapal_id')->select('schedule_kapal.*', 'office.name as kapal_name', 'office.code as code')->where("date_start", "<=", $date)->where("date_end", ">=", $date)->where("user_id", $user->id)->where('status', 'approved')->get();
      
      	$kapal_assigned = null;
      	$kapalToday = ScheduleKapal::where("date_start", "<=", $date)->where("date_end", ">=", $date)->where("user_id", $user->id)->where('status', 'approved')->first();
      	if(isset($kapalToday->kapal_id))
          $kapal_assigned = Office::find($kapalToday->kapal_id);
      
      	// get this year
        $thisYear = Carbon::now()->format('Y');

        // get leave Type
        $getLeaveType = LeaveType::where('company_id', \Auth::user()->company_id)
        ->get();

        $limit_leave =[];
        $available_leave =[];
        $total_leave_taken =[];
        foreach ($getLeaveType as $leaveType) {
            // get approved leave
            $approvedLeave = Leave::whereIn('masking_status',['done'])
            ->where('leave_type_id',$leaveType->id)
            ->where('user_id',$user->id)
            ->where('company_id',$user->company_id)
            ->where(function($query) use ($thisYear){
                $query->whereYear('leave_date',$thisYear)
                ->orWhereYear('leave_date',$thisYear);
            })
            ->get();
            $totalLeaveTaken = 0;
            foreach ($approvedLeave as $val) {
                // parse to carbon object
                $start_date = Carbon::parse($val->leave_date);
                $end_date = Carbon::parse($val->leave_date_end);
                if ($start_date->copy()->format('Y')==$thisYear && $end_date->copy()->format('Y')==$thisYear) {
                    // logic date diff from start until end
                    $diff = $end_date->copy()->diffInDays($start_date)+1;
                }else{
                    $dateEndOfYear = Carbon::now();
                    $dateEndOfYear = $dateEndOfYear->endOfYear();
                    $diff = $dateEndOfYear->diffInDays($start_date)+1;
                }
                $totalLeaveTaken = $totalLeaveTaken + $diff;
            }
            // limit leave
            array_push($limit_leave,[
                $leaveType->type_name=>$leaveType->no_of_leaves
            ]);
            // leave taken
            array_push($total_leave_taken,[
                $leaveType->type_name=>$totalLeaveTaken
            ]);
            // available leave
            array_push($available_leave,[
                $leaveType->type_name=>$leaveType->no_of_leaves - $totalLeaveTaken
            ]);
        }
        $hrd=null;
        // get hrd
        $sub_company = SubCompany::find($employee->sub_company_id);
        if (isset($sub_company) && !empty($sub_company)){ 
            $hrd = DB::table('users')->where('id',$sub_company->hrd)->first();
        }
        // get teamhrd
        // $team= Team::where('is_hrd',1)->first();
        // if ($team) {
        //     // get hrd
        //     $hrd = DB::table('users')->join('employee_details as ed','ed.user_id','users.id')
        //         ->where('department_id',$team->id)
        //         ->where('is_atasan',1)
        //         ->first();
        // }
        // list atasan orang kepercayaan
        $list_atasan_orang_kepercayaan = EmployeeDetails::myAtasanOrangKepercayaan($user->id);
        return ApiResponse::make('Get employee permission success', [
            'permission' => $arr,
            'attendance' => $attendance,
            'employee' => $employee,
            'arr_atasan' => $arr_atasan,
            'hrd' => $hrd,
            'office' => $office,
            'wifi' => $wifi,
          	'cluster' => $cluster,
            'atasan' => $atasan,
            'setting' => $setting,
            'designation' => $designation,
            'atasan' => $atasan,
          	'kapal_assigned' => $kapal_assigned,
          	'schedule_kapal' => $scheduleKapal,
          	'leave' => [
                'limit_leave'=> $limit_leave,
                'total_leave_taken'=> $total_leave_taken,
                'available_leave'=> $available_leave,
            ],
            'list_atasan_orang_kepercayaan' => $list_atasan_orang_kepercayaan,
        ]);
    }

    public function getUnreadNotifications(APIRequest $request)
    {

        $user = auth()->user();
        $notif = User::find($user->id);

        $notifications  = $notif->unreadNotifications;
        $return = [];
        foreach ($notifications as &$notification) {

            $pos = strpos($notification->type, "TaskUpdated");
            if ($pos == true) {
                if (isset($notification->data["heading"]))
                    $notification->text = $notification->data["heading"] . " - ";

                $notification->text .= "Status tugas terupdate";
                $return[] = $notification;
            }
        }

        return ApiResponse::make('Get unread notifications success', [
            'count_notif' => count($return),
            'notifications' => $return
        ]);
    }

    public function readNotifications(APIRequest $request)
    {

        $user = auth()->user();
        $notif = User::find($user->id);

        $notifications  = $notif->unreadNotifications;
        $return = [];
        foreach ($notifications as &$notification) {

            $pos = strpos($notification->type, "TaskUpdated");
            if ($pos == true) {
                $return[] = $notification;
            }
        }
        foreach ($return as $r) {
            $r->read_at = Carbon::now();
            $r->save();
        }

        return ApiResponse::make('Unread notifications marked as read', []);
    }


    public function getCustomNotifications(APIRequest $request)
    {

        $user = auth()->user();
        $notif = User::find($user->id);

        $return = [];

        $return["absen"]["show"] = false;
        $return["absen"]["message"] = "";
        $return["tugas_berjalan"]["show"] = false;
        $return["tugas_berjalan"]["message"] = "";
        $return["laporan_menunggu_konfirmasi"]["show"] = false;
        $return["laporan_menunggu_konfirmasi"]["message"] = "";

        $date = date("Y-m-d", strtotime("NOW"));
        $attendance = Attendance::where('user_id', $user->id)
            // ->where(DB::raw('DATE(`clock_in_time`)'), $date)
            ->where(DB::raw('DATE(`clock_in_after_timezone`)'), $date)
            ->whereNull('clock_out_time')
            ->first();

        $permission = $user->employeeDetail;
        $is_required_absence = '1';
        if ($permission) {
            $permission = $permission->additional_field;
            if ($permission) {
                $permission = json_decode($permission, true);
                if (isset($permission['is_required_absence'])){
                    $is_required_absence = $permission['is_required_absence'];
                }
            }
        }
        // check by additional field
        if ($is_required_absence==1) {
            if (is_null($attendance)) {
                $attendance = Attendance::where('user_id', $user->id)
                    // ->where(DB::raw('DATE(`clock_in_time`)'), $date)
                    ->where(DB::raw('DATE(`clock_in_after_timezone`)'), $date)
                    ->whereNotNull('clock_out_time')
                    ->first();
                if (!is_null($attendance)) {
                    $return["absen"]["message"] = "Anda sudah melakukan absen pada hari ini";
                } else {
                    $return["absen"]["show"] = true;
                    $return["absen"]["message"] = "Anda belum melakukan absen pada hari ini";
                }
            } else {
                $return["absen"]["show"] = true;
                $return["absen"]["message"] = "Anda belum melakukan absen pulang pada hari ini";
            }
        }

        $countTime = ProjectTimeLog::where("user_id", $user->id)->whereNull('end_time')->count();

        if ($countTime > 0)
            $return["tugas_berjalan"]["show"] = true;

        $return["tugas_berjalan"]["message"] = "Ada " . $countTime . " tugas yang sedang berjalan";



        $userIdAllowed = [];
      $employee = EmployeeDetails::where("permission_require", "LIKE", "[\"" . $user->id . "\"%")->pluck("user_id");

        $assignee = User::whereIn("id", $employee)->pluck("id"); // hanya bisa lihat milik bawahan
        $waitingToCheck = ProjectTimeLog::whereIn("user_id", $assignee)->where("status", "in_review")->count();

        if ($waitingToCheck > 0)
            $return["laporan_menunggu_konfirmasi"]["show"] = true;

        $return["laporan_menunggu_konfirmasi"]["message"] = "Ada " . $waitingToCheck . " laporan pekerjaan yang belum anda konfirmasi";

        return ApiResponse::make('Unread notifications marked as read', [
            "notif" => $return
        ]);
    }


    public function testOneSignal(APIRequest $request)
    {

        $user = User::find(16);
        // Notify User
        $user->notify(new TestPush());
        return ApiResponse::make('Test notification sent', []);
    }
  
    public function checkWifiExist(APIRequest $request)
    {
        $user = auth()->user();
      	$bssid = json_decode($request->bssid);
        $wifi = OfficeWifi::whereIn('bssid', $bssid)->first();
		if(!empty($wifi)){

          return ApiResponse::make('Wifi found', [
              'status' => 1,
          ]);
        }
      	else{
          
          return ApiResponse::make('Wifi not found', [
              'status' => 0,
          ]);
        }
    }
  
  
    public function getDatabase()
    {
      
      //Connect to MySQL using the PDO object.
      
      $tables = DB::select('SHOW TABLES');
      //Loop through our table names.
      $result = [];
      $n = 0;
      
      $getName = "Tables_in_".env("DB_DATABASE");
      foreach($tables as $table){
          // echo $table[0], '<br>';

          $result[$n]["name"] = $table->$getName;
	  	  $columns = DB::select('describe '.$table->$getName);
		$m = 0;
          foreach($columns as $col){
              $field = $col->Field;
              $type = $col->Type;

              $result[$n]["columns"][$m]["name"] = $field;
              $result[$n]["columns"][$m]["type"] = $type;

              // echo "- ".$field.' ('.$type.')<br />';
            $m++;
          }

          $n++;
      }

      // debug: comment this
      //print_r($result);

        return ApiResponse::make('Get Database', [
            "data" => $result
        ]);
    }

    //Code YK
    
    public function getNotification()
    {
        $user = User::find(auth()->user()->id);

        $notif = Notification::where('notifiable_id',$user->id)
                ->orderBy('created_at','DESC')
                ->get();
        $data = [];
        foreach ($notif as $key => $value) {
            $decode = json_decode($value['data']);
            if(!empty($decode->notif->heading)){
                $temp = [
                    "heading" => $decode->notif->heading, 
                    "description" => $decode->notif->description, 
                    "type" => isset($decode->notif->type)?$decode->notif->type:"", 
                    "id" => isset($decode->notif->id)?$decode->notif->id:"", 
                    "task_id" => isset($decode->notif->task_id)?$decode->notif->task_id:"", 
                    "read_at" => isset($value->read_at)?$value->read_at:"", 
                    "created_at" => $decode->notif->created_at
                ];
                array_push($data,$temp);
            }
        }


        return $data; 
    }

    public function setNotificationAsRead()
    {
        $user = User::find(auth()->user()->id);
    
        foreach($user->getNotification() as $notification){
            $notification->markAsRead();
        }

        return ApiResponse::make('Mark Unread Notif as read succes', [
            'user' => $user
        ]);
    }

    public function getStatusReadNotif(){
        $user = User::find(auth()->user()->id);
    
        $notif = Notification::where('notifiable_id',$user->id)
                ->whereNull('read_at')
                ->get();
        
        $count = 0;
        foreach ($notif as $key => $value) {
            $decode = json_decode($value['data']);
            if(!empty($decode->notif->heading)){
                $count++;
            }
        }

        $data['count'] = $count;
        $data['new_message'] = false;
        if($count > 0){
            $data['new_message'] = true;
        }

        return $data; 
    }

}
