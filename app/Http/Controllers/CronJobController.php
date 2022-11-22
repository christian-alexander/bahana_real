<?php

namespace App\Http\Controllers;

use App\EmployeeDetails;
use App\Http\Controllers\Controller;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Notifications\TaskLate;
use App\Attendance;
use App\ClusterWorkingHour;
use App\Notifications\Gps;
use App\Notifications\SendEmail;
use Illuminate\Support\Facades\DB;

class CronJobController extends Controller
{
    //
    public function notifTelatKeAtasan()
    {
        $flagErrorMail = false;
        // get now
        $now = Carbon::now();
        // get tugas
        $tugas = Task::join('projects as p', 'p.id', 'tasks.project_id')
            ->join('task_users as tu', 'tu.task_id', 'tasks.id')
            ->where('tasks.status', 'incomplete')
            ->selectRaw('tasks.id,tasks.due_date,p.alert_late_yellow,p.alert_late_red,tu.user_id')
            ->get();
        // TODO: implement notif to atasan
        foreach ($tugas as $val) {
            $due_date = Carbon::createFromFormat('d F, y', $val->due_on);
            $day = $due_date->diffInDays($now);
            // get project late
            // if late 2 days
            if ($val->alert_late_yellow != 0 && $val->alert_late_red != 0) {
                if ($day == $val->alert_late_yellow) {
                    // yellow notif atasan 1
                    $loginEmployee = EmployeeDetails::where('user_id', '=', $val->user_id)->first();
                    $json = json_decode($loginEmployee->permission_require);
                    if (isset($json[0]) && !empty($json[0])) {
                        $atasan = User::find($json[0]);
                        $task = Task::find($val->id);
                        try {
                            $atasan->notify(new TaskLate($task,$atasan, $day));
                        } catch (\Throwable $th) {
                            $flagErrorMail = true;
                        }
                        // $atasan->notify(new TaskLate($task, $day));
                    }
                } elseif ($day > $val->alert_late_yellow && $day <= $val->alert_late_red) {
                    // red
                    $loginEmployee = EmployeeDetails::where('user_id', '=', $val->user_id)->first();
                    $json = json_decode($loginEmployee->permission_require);
                    if (isset($json[1]) && !empty($json[1])) {
                        $atasan = User::find($json[1]);
                        $task = Task::find($val->id);
                        try {
                            $atasan->notify(new TaskLate($task,$atasan, $day));
                        } catch (\Throwable $th) {
                            $flagErrorMail = true;
                        }
                        // $atasan->notify(new TaskLate($task, $day));
                    }
                } elseif ($day > $val->alert_late_red) {
                    // more than 1 week (7 days)
                    // atasan 3
                    $loginEmployee = EmployeeDetails::where('user_id', '=', $val->user_id)->first();
                    $json = json_decode($loginEmployee->permission_require);
                    if (isset($json[2]) && !empty($json[2])) {
                        $atasan = User::find($json[2]);
                      	$task = Task::find($val->id);
                        try {
                            $atasan->notify(new TaskLate($task,$atasan, $day));
                        } catch (\Throwable $th) {
                            $flagErrorMail = true;
                        }
                        // $atasan->notify(new TaskLate($task, $day));
                    }
                }
            }
        }
        if ($flagErrorMail) {
            return 'success, Email error silahkan hubungi developer';
        }else{
            return 'success';
        }
    }
    public function signatureGet(){
        return view('signature');
    }
    public function signaturePost(request $request){
        // return $request->all();
        $folderPath = public_path('user-uploads/signature/');

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }
       
        $image_parts = explode(";base64,", $request->signed);
             
        $image_type_aux = explode("image/", $image_parts[0]);
           
        $image_type = $image_type_aux[1];
           
        $image_base64 = base64_decode($image_parts[1]);
 
        $signature = uniqid() . '.'.$image_type;
           
        $file = $folderPath . $signature;
 
        file_put_contents($file, $image_base64);
 
        return back()->with('success', 'Form successfully submitted with signature');
    }
  
    public function autoClockOut()
    {
        try {
            // get attendance yg belum pulang
            $getAttendance = Attendance::whereNull('clock_out_time')
                        ->get();
    
            foreach ($getAttendance as $attendance) {
                // if ($attendance->id == '21544') {
                //     // jam server
                //     $now = Carbon::now()->addHours(7)->format('Y-m-d');
                //     $datetimeNow = Carbon::now()->addHours(7)->format('Y-m-d H:i:s');
                //     $flagStart = ($now." 23:50:00");
                //     $flagEnd = ($now. " 23:59:00");
                //     if($datetimeNow >= $flagStart && $datetimeNow <= $flagEnd) {
                //         // run script
                //         $now = Carbon::now()->format('Y-m-d');
                //         $clock_in_time = Carbon::parse($attendance->clock_in_time)->addHours(7)->format('Y-m-d');
                //         if ($clock_in_time == $now) {
                //             $clock_out = date("Y-m-d 16:50:00", strtotime($attendance->clock_in_time));
                //             $attendance->clock_out_time = $clock_out;
                //             $attendance->cron_clock_out = 1;
                //             $attendance->save();
                //         }
                //     }  
                // }
                // dikasi pengecekan apakah jam skrng itu 23:50 jika ya
                // jika ya baru jalankan
                // 23.50 - 7hour = 16.50
    
                // jam server
                $now = Carbon::now()->addHours(7)->format('Y-m-d');
                $datetimeNow = Carbon::now()->addHours(7)->format('Y-m-d H:i:s');
                $flagStart = ($now." 23:50:00");
                $flagEnd = ($now. " 23:59:00");
                if($datetimeNow >= $flagStart && $datetimeNow <= $flagEnd) {
                    // run script
                    $now = Carbon::now()->format('Y-m-d');
                    // $clock_in_time = Carbon::parse($attendance->clock_in_time)->addHours(7)->format('Y-m-d');
                    $clock_in_time = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d');
                    if ($clock_in_time == $now) {
                        // $clock_out = date("Y-m-d 16:50:00", strtotime($attendance->clock_in_time));
                        // clock_out udah gak dipake seharusnya aman
                        // $new_clock_out = Carbon::parse($attendance->clock_in_time->copy()->format('Y-m-d').' 23:50:00');
                        $new_clock_out = Carbon::parse($attendance->clock_in_after_timezone->copy()->format('Y-m-d').' 23:50:00')->subHours($attendance->clock_in_timezone);
                        // $clock_out = date("Y-m-d 16:50:00", strtotime($attendance->clock_in_time));
                        // $attendance->clock_out_time = $clock_out;
                        $attendance->clock_out_time = $new_clock_out;
                        $attendance->cron_clock_out = 1;
                        // 
                        $attendance->clock_out_timezone=$attendance->clock_in_timezone;
                        $clock_out_after_timezone = date("Y-m-d 23:50:00", strtotime($attendance->clock_in_after_timezone));
                        $attendance->clock_out_after_timezone=$clock_out_after_timezone;
                        $attendance->save();
                    }
                }   
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public function autoClockOut2()
    {	
        // get attendance yg belum pulang
        $getAttendance = Attendance::whereNull('clock_out_time')
          			->where('clock_in_time', '<', date("Y-m-d 00:00:00", strtotime("NOW")))
                    ->get();

        foreach ($getAttendance as $attendance) {
            // 23.50 - 7hour = 16.50
            $new_clock_out = Carbon::parse($attendance->clock_in_time->copy()->format('Y-m-d').' 23:50:00')->subHours($attendance->clock_in_timezone);
            // $clock_out = date("Y-m-d 16:50:00", strtotime($attendance->clock_in_time));
            // $attendance->clock_out_time = $clock_out;
            $attendance->clock_out_time = $new_clock_out;
            $attendance->cron_clock_out = 1;
            // 
            $attendance->clock_out_timezone=$attendance->clock_in_timezone;
            $clock_out_after_timezone = date("Y-m-d 23:50:00", strtotime($attendance->clock_in_after_timezone));
            $attendance->clock_out_after_timezone=$clock_out_after_timezone;
            $attendance->save();
        }

    }
    public function sendEmail(){
        try {
            $flagErrorMail = false;
            //di asumsikan jam server gmt + 7
            $now = Carbon::now()->addHours(7);
            $now_string = $now->copy()->format('Y-m-d H:i:s');
            $now_minus_one_hour = $now->copy()->subHour()->format('Y-m-d H:i:s');
            $data = DB::table('email_to_send')
                ->join('email_to_send_detail','email_to_send_detail.email_to_send_id','email_to_send.id')
                ->whereBetween(\DB::raw('DATE_ADD(email_to_send_detail.created_at, INTERVAL 7 HOUR)'),[$now_minus_one_hour,$now_string])
                ->groupBy('email_to_send.id')
                ->select('email_to_send.*')
                ->get();
            foreach ($data as $val) {
                // get detail
                $details =  DB::table('email_to_send_detail')->where('email_to_send_id',$val->id)->get()->toArray();
                // $html='<table><thead><tr><td>No</td><td>Activity</td></tr></thead>';
                // $idx=0;
                // foreach ($details as $detail) {
                //     $activity = $detail->text;
                //     $html="<tbody><tr><td>$idx</td><td>$activity</td></tr></tbody>";
                //     $idx++;
                // }
                // $html='</table>';
                // send notif to user
                $user = User::find($val->to_user_id);
                try {
                    $user->notify(new SendEmail($details));
                } catch (\Throwable $th) {
                    $flagErrorMail = true;
                }
            }
            return 'Success';
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }
    public function sendNotifGps(){
        try {
            // asume server time is gmt+7
            $now_original = Carbon::now();
            $now = Carbon::now()->addHours(7);
            // get user from tracker
            $users = DB::table('trackers')
                ->select('user_id as id','company_id')
                // ->where('user_id',81)
                ->groupBy('user_id')
                ->get();
            $idx= 0;
            foreach ($users as $user) {
                // get last tracker
                $tracker = DB::table('trackers')
                    ->where('user_id', $user->id)
                    ->orderBy('created_at_after_timezone','desc')
                    ->first();
                $created_at_after_timezone = $tracker->created_at_after_timezone;
                if (isset($created_at_after_timezone) && !empty($created_at_after_timezone)){
                    // check date in range clock in and clock out in this day
                    $check = ClusterWorkingHour::isNowWorkingHour($now_original,$user->id);

                    if ($check) {
                        // data exist
                        $created_at_after_timezone = Carbon::parse($created_at_after_timezone);
                        $diff = $now->diffInMinutes($created_at_after_timezone);
                        // when diff more than 30 minutes 
                        if ($diff>30) {
                            // send notif to user
                            $user_notif = User::find($user->id);
                            try {
                                $user_notif->notify(new Gps());
                                $idx++;
                            } catch (\Throwable $th) {
                                $flagErrorMail = true;
                            }
                        }
                    }
                }
            }
            return "$idx notif send";
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
