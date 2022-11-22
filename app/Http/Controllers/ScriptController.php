<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\AttendanceSetting;
use App\Designation;
use App\EmployeeDetails;
use App\Http\Controllers\Admin\ClusterWorkingHourController;
use App\Http\Controllers\Controller;
use App\Leave;
use App\LeaveIjin;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Notifications\TaskLate;
use App\Project;
use Illuminate\Support\Facades\DB;

class ScriptController extends Controller
{
    public function fillEmptyTeamIdProject()
    {
        DB::beginTransaction();
        $row=0;
        try {
            $getProject = Project::all();
            foreach ($getProject as $val) {
                // get team id
                $getTeam = User::find($val->project_admin);
                if (isset($getTeam) && !empty($getTeam)) {
                    $val->team_id = $getTeam->employeeDetail->department_id;
                    $val->save();
                    $row++;
                }
            }
            DB::commit();
            return "$row updated";
        } catch (\Throwable $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
    public function removeDoubleTask(){
      ini_set('max_execution_time', 999999);
      set_time_limit(999999);
      
      //phpinfo();die;
      	
        DB::beginTransaction();
        try {
          	$data_deleted= 0;
          	$data_deleted_trackers = 0;
          
          	// duplicate tasks
          	$start = microtime(true);
            $duplicates = DB::table('tasks')->whereIn('id', function ( $query ) {
                $query->select('id')->from('tasks')
                // ->where('board_column_id',3)
                ->groupBy('heading','description','assignee_user_id','start_date','due_date','project_id')
                ->havingRaw('count(*) > 1');
            })
            ->orderBy('heading','asc')
            ->get();
            // dd($duplicates);
            
            foreach ($duplicates as $task) {
                // get data
                $duplicate_to_delete =  DB::table('tasks')
                    ->where('heading', $task->heading)
                    // ->where('board_column_id',3)
                    ->where('description',$task->description)
                    ->where('assignee_user_id',$task->assignee_user_id)
                    ->orderBy('board_column_id','desc')
                    ->get();
                $idx=0;
                foreach ($duplicate_to_delete as $task_to_delete) {
                    if ($idx>=1) {
                        $data_deleted++;
                        // delete data
                        $detele_action = DB::table('tasks')->delete($task_to_delete->id);
                        // $task_to_delete->delete();
    
                    }
                    $idx++;
                }
            }
          	$time_elapsed_secs_task = microtime(true) - $start;
          
          	DB::commit();
          
	        // duplicate trackers
          	$start = microtime(true);
          
          	$date = \Carbon\Carbon::today()->subDays(30);
          
          	// delete all records more than 30 days
          	$old_records = DB::table('trackers')->where('created_at', '<', $date)->delete();
          	//dd($old_records);
          
          	$duplicates = DB::table('trackers')
            ->select(DB::raw('max(id) as lastId, user_id, created_at'))
            //->where('user_id', 352)
            ->orderBy('user_id','asc')
            ->havingRaw('count(created_at) > 1')
            ->groupBy(['created_at'])
            //->select('id','user_id')
            ->get();
          	//dd($duplicates);
          
          	//$data_deleted_trackers = count(explode(", ",$duplicates));
          
          	/*
            // not working
          	$arr = [];
          	foreach ($duplicates as $data) {
              $arr[] = $data->id;
            }

          
          	if($duplicates && !empty($duplicates)){
              $noOfDeletedRecords = DB::table('trackers')
                //->where('user_id', 352)
                  ->whereNotIn('id',$arr)
                  ->delete();
              //print_r($noOfDeletedRecords);
              
              $data_deleted_trackers = count(explode(", ",$noOfDeletedRecords));
            }
            */
          
            foreach ($duplicates as $tracker) {
                
              	if(isset($_GET['mode']) && $_GET['mode'] == 'slow'){
                  // working but slow because more throughly
                  $duplicate_to_delete =  DB::table('trackers')
                      ->where('created_at',$tracker->created_at)
                      ->where('user_id', $tracker->user_id)
                      ->where('id', '!=', $tracker->lastId)
                      ->get();
                  foreach($duplicate_to_delete as $item){
					DB::table('trackers')->delete($item->id);
                    $data_deleted_trackers++;
                  }
                  
                }else{              
                  $duplicate_to_delete = DB::table('trackers')->delete($tracker->lastId);
                  $data_deleted_trackers++;
                }
              
              	DB::commit();
            }
          
          	//DB::commit();
          
          	$time_elapsed_secs = microtime(true) - $start;
            return 'Elapsed Time Task: '.$time_elapsed_secs_task.'s<br />Elapsed Time Tracker: '.$time_elapsed_secs.'s<br />Tasks deleted: '.$data_deleted. '<br />Trackers deleted: '.$data_deleted_trackers;
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }

    public function removeLeaveBySystem(){
        DB::beginTransaction();
        try {
            // get leave by system
            $leaves = Leave::where('approved_by','system')->get();
            $count =0;
            foreach ($leaves as $leave) {
                //get cluster user
                $employee = EmployeeDetails::join('cluster_working_hours as cwh','cwh.id','employee_details.cluster_working_hour_id')
                    ->where('employee_details.user_id',$leave->user_id)
                    ->select('employee_details.*','employee_details.user_id as id','cwh.type as cluster_type','cwh.json as cluster_json','cwh.start_hour as cluster_start_hour','cwh.end_hour as cluster_end_hour')
                    ->first();
                $checkAttendance = Attendance::checkClockInAndClockOut($employee,$leave->date);
                if ($checkAttendance) {
                    // get child
                    $child = LeaveIjin::where('leave_id',$leave->id)->first();
                    if (isset($child) && !empty($child)){ 
                        // delete child if exist
                        $child->delete();
                    }
                    // jika true/hadir maka leave ijin pulang dihapus
                    $leave->delete();
                    $count++;
                }
            }
            DB::commit();
            return "Leave deleted :$count";
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }
    public function fixAttendanceByCluster(){
        DB::beginTransaction();
        try {
            // get all attendance who have leave
            $attendance = DB::table('attendances')
                // ->join('leaves as l','l.user_id','attendances.user_id')
                ->join('leaves as l',function($q){
                    $q->on('l.user_id', '=', 'attendances.user_id');
                    // ->on('l.leave_date',\DB::raw('DATE_ADD(attendances.clock_in_time, INTERVAL 7 HOUR)'));
                })
                ->where('l.approved_by','system')
                // ->where('l.user_id','114')
                // ->where('l.leave_date',\DB::raw('DATE(DATE_ADD(attendances.clock_in_time, INTERVAL 7 HOUR))'))
                ->where('l.leave_date','attendances.clock_in_after_timezone')
                ->select('attendances.*','l.id as leave_id','l.leave_date')
                // ->groupBy('attendances.id')
                ->get();
            $idx= 0;
            foreach ($attendance as $val) {
                // get user info
                $user = DB::table('users')->where('id', $val->user_id)->first();
                // bisa dipindah menjadi global fungsi
                // check from cluster_meta
                $cluster_meta = json_decode($val->cluster_meta, true);
                if (isset($cluster_meta) && !empty($cluster_meta)){ 
                    // add 7 hour to date
                    // $date_attendance_clock_in = Carbon::parse($val->clock_in_time)->addHours(7);
                    // $date_attendance_clock_out = Carbon::parse($val->clock_out_time)->addHours(7);
                    $date_attendance_clock_in = Carbon::parse($val->clock_in_after_timezone);
                    $date_attendance_clock_out = Carbon::parse($val->clock_out_after_timezone);
                    $date_in_day = $date_attendance_clock_in->copy()->format('l');
                    $date_in_day_indo = getDayInIndonesia($date_in_day);
                    if ($cluster_meta['type']=='daily') {
                        // this function to give default value when schedule is empty
                        $clusterController = new ClusterWorkingHourController;
                        $json_jadwal = $clusterController->getJsonDaily($cluster_meta['json'], true);
                        // $json_jadwal = json_decode($cluster_meta['json'], true);

                        $jam_masuk_cluster = $json_jadwal[$date_in_day_indo]['jam_masuk'];
                        $carbon_jam_masuk_cluster = Carbon::parse($jam_masuk_cluster)->format('H:i');
                        $jam_pulang_cluster = $json_jadwal[$date_in_day_indo]['jam_pulang'];
                        $carbon_jam_pulang_cluster = Carbon::parse($jam_pulang_cluster)->format('H:i');

                        // get jam pulang from attendance
                        $jam_pulang_attendance = $date_attendance_clock_out->copy()->format('H:i');

                        $attendanceSetting = AttendanceSetting::where('company_id',$user->company_id)->first();
                        $datetime_jam_pulang_cluster_minus_setting = Carbon::parse($carbon_jam_pulang_cluster)->subMinutes($attendanceSetting->toleransi_absen_pulang)->format('H:i');
                        // dd($jam_pulang_attendance<$datetime_jam_pulang_cluster_minus_setting,"$jam_pulang_attendance<$datetime_jam_pulang_cluster_minus_setting");
                        if ($jam_pulang_attendance<$datetime_jam_pulang_cluster_minus_setting) {
                            // jika absen masuk dan pulang dibawah jadwal cluster
                            // kondisi true;
                            //   $tidak_hadir++;
                        }else{
                            // kondisi false;
                            //   $hadir++;
                            // jika false hapus leave
                            // yg masuk sini seperti data ini "15:58<15:15"
                            $leave_to_delete = Leave::find($val->leave_id);
                            // get child
                            $child = LeaveIjin::where('leave_id',$val->leave_id)->first();

                            if (isset($child) && !empty($child)){ 
                                // delete child
                                $child->delete();
                            }
                            // $idx++;

                            if (isset($leave_to_delete) && !empty($leave_to_delete)){ 
                                // delete parent
                                $leave_to_delete->delete();
                                $idx++;
                            }
                        }
                    }else{
                        $jam_masuk_cluster = $val->start_hour;
                        $carbon_jam_masuk_cluster = Carbon::parse($jam_masuk_cluster)->format('H:i');
                        $jam_pulang_cluster = $val->end_hour;
                        $carbon_jam_pulang_cluster = Carbon::parse($jam_pulang_cluster)->format('H:i');

                        // get jam pulang from attendance
                        $jam_pulang_attendance = $date_attendance_clock_out->copy()->format('H:i');

                        $attendanceSetting = AttendanceSetting::where('company_id',$user->company_id)->first();
                        $datetime_jam_pulang_cluster_minus_setting = Carbon::parse($carbon_jam_pulang_cluster)->subMinutes($attendanceSetting->toleransi_absen_pulang)->format('H:i');
                        // dd($jam_pulang_attendance<$datetime_jam_pulang_cluster_minus_setting,"$jam_pulang_attendance<$datetime_jam_pulang_cluster_minus_setting");
                        if ($jam_pulang_attendance<$datetime_jam_pulang_cluster_minus_setting) {
                            // jika absen masuk dan pulang dibawah jadwal cluster
                            // return false;
                            //   $tidak_hadir++;
                        }else{
                            // return true;
                            //   $hadir++;
                            // jika false hapus leave
                            // yg masuk sini seperti data ini "15:58<15:15"
                            $leave_to_delete = Leave::find($val->leave_id);
                            // get child
                            $child = LeaveIjin::where('leave_id',$val->leave_id)->first();
                            if (isset($child) && !empty($child)){ 
                                // delete child
                                $child->delete();
                            }
                            // $idx++;

                            if (isset($leave_to_delete) && !empty($leave_to_delete)){ 
                                // delete parent
                                $leave_to_delete->delete();
                                $idx++;
                            }
                        }
                    }
                }
            }
            DB::commit();
            return "Leave deleted :$idx";
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
        
    }
    public function fillClockInAfterTimezone(){
        DB::beginTransaction();
        try {
            // get all attendance
            $attendance = DB::table('attendances')
            ->where('clock_in_after_timezone', null)
            ->where('clock_out_after_timezone', null)
            ->select(
                'id',
                'clock_in_time',
                'clock_out_time',
                'clock_in_timezone',
                'clock_out_timezone',
                )->get();
            $count= 0;
            foreach ($attendance as $val) {
                // case data sebelumnya tidak ada data clock_in_timezone yang minus (-)
                $clock_in_after_timezone = Carbon::parse($val->clock_in_time)->addHours($val->clock_in_timezone);
                if (empty($val->clock_out_time)) {
                    DB::update('update attendances set clock_in_after_timezone = ? where id = ?', 
                    [$clock_in_after_timezone , $val->id]);
                    $count++;
                }else{
                    $clock_out_after_timezone = Carbon::parse($val->clock_out_time)->addHours($val->clock_out_timezone);
                    DB::update('update attendances set clock_in_after_timezone = ? , clock_out_after_timezone = ? where id = ?', 
                    [$clock_in_after_timezone , $clock_out_after_timezone , $val->id]);
                    $count++;
                }
                
            }
            DB::commit();
            return "Data updated :".$count;
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }
    public function fillCreatedAtAfterTimezone(){
        DB::beginTransaction();
        try {
            // get all attendance
            $tracker = DB::table('trackers')
            ->where('created_at_after_timezone', null)
            ->where('updated_at_after_timezone', null)
            ->select(
                'id',
                'timezone',
                'created_at',
                'updated_at',
                )->get();
            $count= 0;
            foreach ($tracker as $val) {
                // case data sebelumnya tidak ada data clock_in_timezone yang minus (-)
                $created_at_after_timezone = Carbon::parse($val->created_at)->addHours($val->timezone);
                $updated_at_after_timezone = Carbon::parse($val->updated_at)->addHours($val->timezone);
                DB::update('update trackers set created_at_after_timezone = ?,updated_at_after_timezone=? where id = ?', 
                [$created_at_after_timezone,$updated_at_after_timezone, $val->id]);
                $count++;
                
            }
            DB::commit();
            return "Data updated :".$count;
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }
    public function forceUpdateLateInAttendance(){
        DB::beginTransaction();
        try {
            $date = request()->date;
            // date format Y-m-d
            if (!isset($date) && empty($date)){ 
                return 'Date Required with format Y-m-d';
            }
            
            $attendances = Attendance::whereDate('clock_in_after_timezone',$date)
                ->select('id','user_id','cluster_meta','clock_in_after_timezone','clock_in_timezone')
                ->get();
            $counter = 0;
            foreach ($attendances as $attendance) {
                // get employee
                $employee = DB::table('employee_details')->where('user_id', $attendance->user_id)
                ->first();
                $jabatan = DB::table('designations')->find($employee->designation_id);
                $cluster_meta = json_decode($attendance->cluster_meta, true);
                $attendance_clock_in_time = Carbon::parse($attendance->clock_in_after_timezone)->format('H:i:s');
                $start_date_formated = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d');
                if ($cluster_meta['type'] == 'daily') {
                    $day = getDayInIndonesia(Carbon::parse($attendance->clock_in_after_timezone)->format('l'));

                    $clusterController = new ClusterWorkingHourController;
                    $json_cluster = $clusterController->getJsonDaily($cluster_meta['json'], true);
                    
                    if(isset($json_cluster[$day]['jam_masuk'])){
                        $office_start_time = date('H:i:s', strtotime($json_cluster[$day]['jam_masuk']));
                        $jam_masuk = $json_cluster[$day]['jam_masuk'];
                    }else{
                        $office_start_time = date('H:i:s', strtotime($json_cluster["senin"]['jam_masuk']));
                        $jam_masuk = $json_cluster["senin"]['jam_masuk'];
                    }
                }else{
                    // shift
                    $office_start_time = date('H:i:s', strtotime($cluster_meta['start_hour']));
                    $jam_masuk = $cluster_meta['start_hour'];
                }
                if ($office_start_time < $attendance_clock_in_time && $jabatan->check_late == 1) {
                    // masuk sini berarti telat
                    // force update
                    $getDate = Carbon::parse($attendance->clock_in_after_timezone)->format('Y-m-d');
                    $clock_in_after_timezone_update = Carbon::parse($getDate.' '.$jam_masuk);
                    $timezone = $attendance->clock_in_timezone;
                    if ($timezone>0) {
                        $clock_in_before_timezone_update = $clock_in_after_timezone_update->copy()->subHours($timezone);
                    }else{
                        $clock_in_before_timezone_update = $clock_in_after_timezone_update->copy()->addHours(abs($timezone));
                    }
                    DB::update('update attendances set clock_in_time = ?,clock_in_after_timezone=? where id = ?', 
                    [$clock_in_before_timezone_update,$clock_in_after_timezone_update, $attendance->id]);
                    $counter++;
                }
            }
            DB::commit();
            return "Data attendance updated: $counter";
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
        
    }
    public function forceUpdateAttendanceClusterMeta(){
        
        DB::beginTransaction();
        try {
            $date = request()->date;
            if (!isset($date) && empty($date)){ 
                return "date diperlukan dengan format Y-m-d";
            }
            $jam_pulang = request()->jam_pulang;
            if (!isset($jam_pulang) && empty($jam_pulang)){ 
                return "jam_pulang diperlukan dengan format g:i A, ex(07:00 AM)";
            }
            $getAttendances = DB::table('attendances')->whereDate('clock_in_after_timezone',$date)
                ->select('id','cluster_meta','clock_in_after_timezone','user_id')
                ->get();
            $count=0;
            foreach ($getAttendances as $attendance) {
                $cluster = json_decode($attendance->cluster_meta);
                if ($cluster->type=='daily') {
                    $json = json_decode($cluster->json,true);
                    // get day
                    $carbonDate = Carbon::parse($date);
                    $date_in_day = $carbonDate->copy()->format('l');
                    $date_in_day_indo = getDayInIndonesia($date_in_day);
                    $json[$date_in_day_indo]['jam_pulang']=$jam_pulang;
                    $cluster->json = json_encode($json);
                    // update data
                    DB::update('update attendances set cluster_meta = ? where id = ?', 
                    [json_encode($cluster),$attendance->id]);
                    $count++;
                    
                }else{
                    // shift
                    $cluster->end_hour =$jam_pulang;
                    // update data
                    DB::update('update attendances set cluster_meta = ? where id = ?', 
                    [json_encode($cluster),$attendance->id]);
                    $count++;
                }
                // delete leave if exist
                // get leave
                $leave = Leave::where('user_id', $attendance->user_id)
                    ->whereDate('leave_date',$date)
                    ->where('approved_by','system')
                    ->first();
                if (isset($leave) && !empty($leave)){  
                    $child = LeaveIjin::where('leave_id',$leave->id)->first();
                    if (isset($child) && !empty($child)){ 
                        // delete child if exist
                        $child->delete();
                    }
                    $leave->delete();
                }
            }
            DB::commit();
            return "Data attendance updated: $count";
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }
    public function fixTrackerDataCreatedAtNotGmtZero(){
        DB::beginTransaction();
        try {
            $from = request()->from;
            $plus = request()->plus;
            if (empty($plus)) {
                $plus='false';
            }
            // to make sure user enter valid date
            $from = Carbon::createFromFormat('Y-m-d H:i:s',$from)->format('Y-m-d H:i:s');
            // $from = Carbon::createFromFormat('Y-m-d','2021-07-21')->format('Y-m-d');
            
            // get data to update
            $data = DB::table('trackers')
                ->where('created_at','>=',$from)
                ->get();

            $idx=0;
            // logic to update created at and dll
            foreach ($data as $val) {
                // created at diasumsikan sudah dengan timezone
                $created_at = Carbon::parse($val->created_at);
                $created_at_after_timezone = Carbon::parse($val->created_at_after_timezone);
                $updated_at_after_timezone = Carbon::parse($val->updated_at_after_timezone);
                $timezone = $val->timezone;
                if ($plus=='false') {
                    // ketika false
                    if ($timezone>0) {
                        $created_at->subHours(abs($timezone));
                        $created_at_after_timezone->subHours(abs($timezone));
                        $updated_at_after_timezone->subHours(abs($timezone));
                    }else{
                        $created_at->addHours($timezone);
                        $created_at_after_timezone->addHours($timezone);
                        $updated_at_after_timezone->addHours($timezone);
                    }
                }else{
                    // ketika plus = true
                    if ($timezone>0) {
                        $created_at->addHours($timezone);
                        $created_at_after_timezone->addHours($timezone);
                        $updated_at_after_timezone->addHours($timezone);
                    }else{
                        $created_at->subHours(abs($timezone));
                        $created_at_after_timezone->subHours(abs($timezone));
                        $updated_at_after_timezone->subHours(abs($timezone));
                    }
                }
                DB::update('update trackers set created_at = ?, updated_at = ?, created_at_after_timezone = ?, updated_at_after_timezone = ? where id = ?', 
                [$created_at,$created_at,$created_at_after_timezone,$updated_at_after_timezone, $val->id]);

                $idx++;
            }
            DB::commit();
            return "Data updated: ".$idx;
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }
    public function removeLatLongUser(){
        DB::beginTransaction();
        try {
            // get all employee_details
            $employee_detail = DB::table('employee_details')->select('id','additional_field')->get();
            foreach ($employee_detail as $val) {
                $additional_field = json_decode($val->additional_field, true);
                if (!empty($additional_field)) {
                    if (isset($additional_field['edit_lat_long']) && !empty($additional_field['edit_lat_long'])){ 
                        if ($additional_field['edit_lat_long']==1) {
                            $additional_field['edit_lat_long'] = '0';
                            // update employee_details
                            $additional_field = json_encode($additional_field);
                            DB::update('update employee_details set additional_field = ? where id = ?',
                            [$additional_field,$val->id]);
                        }
                    }
                }
            }
            // set null all lat long
            DB::update('update employee_details set latitude = ?, longitude = ? where latitude IS NOT NULL and longitude IS NOT NULL',
            [null,null]);
            DB::commit();
            return "Data updated";
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
        }
    }
}
