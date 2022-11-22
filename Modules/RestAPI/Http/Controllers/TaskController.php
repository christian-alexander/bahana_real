<?php

namespace Modules\RestAPI\Http\Controllers;

use App\TaskChatComment;
use App\Events\TaskReminderEvent;
use App\Helper\Reply;
use App\Notifications\TaskReminder;
use App\Role;
use App\TaskUser;
use App\User;
use Froiden\RestAPI\ApiController;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Support\Facades\Notification;
use Modules\RestAPI\Entities\Employee;
use Modules\RestAPI\Entities\Task;
use Modules\RestAPI\Http\Requests\Task\IndexRequest;
use Modules\RestAPI\Http\Requests\Task\CreateRequest;
use Modules\RestAPI\Http\Requests\Task\ShowRequest;
use Modules\RestAPI\Http\Requests\Task\UpdateRequest;
use Modules\RestAPI\Http\Requests\Task\DeleteRequest;
use Illuminate\Support\Facades\DB;
use App\EmployeeDetails;
use App\Http\Requests\API\APIRequest;
use App\TaskboardColumn;
use Carbon\Carbon;
use App\Company;
use App\TaskFile;
use App\Helper\Files;
use App\Notifications\CCTugas;
use App\Notifications\CCTugasOrangKepercayaan;
use App\Notifications\CCUser;
use App\Task as TaskApp;
use App\ProjectTimeLog;
use App\ProjectActivity;
use App\ProjectTimeLogFile;

use App\Notifications\TaskUpdated;
use Modules\RestAPI\Entities\Attendance;
class TaskController extends ApiBaseController
{

    protected $model = Task::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query)
    {
        // print_r($query->visibility()->toSql());die;

        return $query->visibility();
    }
    // public function modifyShow($query)
    // {

    // }

    public function stored(Task $task)
    {
        return $this->syncTaskUsers($task);
    }

    public function updated(Task $task)
    {
        return $this->syncTaskUsers($task);
    }

    private function syncTaskUsers(Task $task){
        // To add custom fields data
        if (request()->get('task_users')) {
            $ids = array_column(request()->get('task_users'),'id');
            $task->users()->sync($ids);
        }

        return $task;
    }

    public function remind($taskID)
    {
        $task = \App\Task::findOrFail($taskID);
        event(new TaskReminderEvent($task));
        return ApiResponse::make(__('messages.reminderMailSuccess'));
    }

    // get all tasks on related user
    public function task_users($user_id){

    }

    public function getAssignee(){
        try {
            DB::beginTransaction();
            $user = auth()->user();
        	$loginEmployee = EmployeeDetails::where('user_id', '=', $user->id)->first();
          if($loginEmployee->option_employee == 1){
              $option_employee_sub_company = json_decode($loginEmployee->option_employee_sub_company);
              if (empty($option_employee_sub_company)) {
                $option_employee_sub_company =[];
              }
              $employee = EmployeeDetails::whereIn('sub_company_id',$option_employee_sub_company)->pluck("user_id");
            }else{
              $employee = EmployeeDetails::where("permission_require", "LIKE", "%\"".$user->id."\"%")->pluck("user_id");              
            }
          	$assignee = DB::table('users')->select('id','name')->whereIn("id", $employee)->where('status','active')->orderBy("name")->get();
			
          	foreach($assignee as &$a){
              $a->last_attendance = Attendance::where('user_id', $a->id)->orderBy('created_at', 'desc')->first();
              $a->notif_setting = EmployeeDetails::where('user_id', $a->id)->select('no_notification','no_notification_start','no_notification_end','no_notification_updated_by','no_notification_updated_at')->first();
              




            }

            return ApiResponse::make('Assignee found', [
                        'assignee' => $assignee
            ]);
        } catch (Exception $e) {
            DB::rollback();
            // return ApiResponse::make('Attendance failed '.$e->getMessages());
            $exception = new ApiException('Attendance failed '.$e->getMessages(), null, 403, 403, 2001);
            return ApiResponse::exception($exception);
            
        }
    }
    public function getAtasan(){
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $assignee = DB::table('users')
              ->join('employee_details','employee_details.user_id','users.id')
              ->where('employee_details.is_atasan',1)
              ->select('users.id','users.name')->orderBy("users.name")
              ->get();
			
          	foreach($assignee as &$a){
              $a->last_attendance = Attendance::where('user_id', $a->id)->orderBy('created_at', 'desc')->first();
              $a->notif_setting = EmployeeDetails::where('user_id', $a->id)
              ->select('no_notification','no_notification_start','no_notification_end','no_notification_updated_by','no_notification_updated_at')
              ->first();
            }

            return ApiResponse::make('All Atasan found', [
                        'atasan' => $assignee
            ]);
        } catch (Exception $e) {
            DB::rollback();
            // return ApiResponse::make('Attendance failed '.$e->getMessages());
            $exception = new ApiException('Attendance failed '.$e->getMessages(), null, 403, 403, 2001);
            return ApiResponse::exception($exception);
            
        }
    }

    public function getListTugas(APIRequest $request){
        $user = auth()->user();
      	//$taskBoard = TaskboardColumn::where("slug", $request->slug)->where("company_id", $user->company_id)->first();
      	//$allMyTask = TaskUser::where("user_id", $user->id)->pluck("task_id");
      	$limit = isset($request->limit)?$request->limit:10;
      	$offset = isset($request->offset)?$request->offset:0;
      	$order = isset($request->order)?$request->order:"desc";
		$task = new TaskApp;
      // join to employee detail
      $task = $task->leftjoin('employee_details as ed','ed.user_id','tasks.assignee_user_id')
        ->select('tasks.*');
        if(isset($request->interval) && !empty($request->interval)){
          
          if($request->interval === true || $request->interval === "true"){
            $task = $task->whereNotNull('tasks.interval_report')->where('tasks.interval_report', '>', 0);
          }
          else{
            $task = $task->where(function ($query) {
                $query->whereNull('tasks.interval_report')
                      ->orWhere('tasks.interval_report', 0);
            });
          }
            
       
        }
      
        if(isset($request->on_going) && !empty($request->on_going)){
          
          if($request->on_going === true || $request->on_going === "true"){
      
          	$activeTimer = ProjectTimeLog::whereNull('end_time')
              ->pluck('task_id');
            $task = $task->whereIn('tasks.id', $activeTimer);
          }
            
       
        }
      
        if(isset($request->start_date) && !empty($request->start_date)){
          $task = $task->where('tasks.start_date','>=', $request->start_date);
       
        }
      
        if(isset($request->end_date) && !empty($request->end_date)){
          $task = $task->where('tasks.start_date','<=', $request->end_date);
       
        }
      
      
        if(isset($request->assignee_user_id) && !empty($request->assignee_user_id)){
          if($request->assignee_user_id == "all"){
            // - menampilkan tugas orang yang login
            // - menampilkan tugas bawahan
            // - TODO: debug 
            $employee = EmployeeDetails::where("permission_require", "LIKE", "%\"".$user->id."\"%")->pluck("user_id"); 
            //$task = $task->whereIn('assignee_user_id', $employee)->orWhere('assignee_user_id', $user->id);
            $task = $task->where(function ($query) use ($employee, $user) {
                $query->whereIn('tasks.assignee_user_id', $employee)
                      ->orWhere('tasks.assignee_user_id', $user->id)
                      ->orWhere('tasks.cc_user_id',"LIKE", "%\"".$user->id."\"%");
            });
          }
          else if($request->assignee_user_id == "diri_sendiri"){
            
            // $task = $task->where('assignee_user_id', $user->id);
            $task = $task->where(function($query) use($user){
              $query->where('tasks.assignee_user_id', $user->id)
                    ->orWhere('tasks.cc_user_id',"LIKE", "%\"".$user->id."\"%");
            }); 
          }
          else if($request->assignee_user_id == "all_bawahan"){
            $employee = EmployeeDetails::where("permission_require", "LIKE", "%\"".$user->id."\"%")->pluck("user_id"); 
            
            $task = $task->where(function($query) use($request, $user, $employee){
              $query->whereIn('tasks.assignee_user_id', $employee)
                    ->orWhere('tasks.cc_user_id',"LIKE", "%\"".$user->id."\"%");
            });
            
          }else if($request->assignee_user_id == "orang_kepercayaan"){
            // get orang kepercayaan
            $orang_kepercayaan = EmployeeDetails::where('user_id',$request->orang_kepercayaan_id)->first();
            // filter by sub company
            if ($orang_kepercayaan) {
              $task = $task->where(function($query) use($orang_kepercayaan){
                $query->where('tasks.created_by', $orang_kepercayaan->user_id)
                  ->whereIn('ed.sub_company_id', empty(json_decode($orang_kepercayaan->sub_company_orang_kepercayaan))?[]:json_decode($orang_kepercayaan->sub_company_orang_kepercayaan));
              });
            }
          }else{
            // $task = $task->where('assignee_user_id', $request->assignee_user_id);
            $task = $task->where(function($query) use($request, $user){
              $query->where('tasks.assignee_user_id', $request->assignee_user_id)
                    ->orWhere('tasks.cc_user_id',"LIKE", "%\"".$user->id."\"%");
            });
          }
        }
        // get orang kepercayaan
        $orang_kepercayaan = EmployeeDetails::where("user_orang_kepercayaan", "LIKE", "%\"".$user->id."\"%")->pluck("user_id");
        $task = $task->orWhere(function($query) use($orang_kepercayaan){
          $query->whereIn('tasks.created_by', $orang_kepercayaan);
        });
        if(isset($request->tugas_yang_saya_berikan) && !empty($request->tugas_yang_saya_berikan)){
          $task = $task->where('tasks.created_by',$user->id);
        }

        // $task = $task->orWhere('cc_user_id',"LIKE", "%\"".$user->id."\"%");
      
        if(isset($request->heading) && !empty($request->heading)){
          $task = $task->where('tasks.heading', 'like',"%".$request->heading."%");
        }
      
        if(isset($request->board_column_id) && !empty($request->board_column_id)){
        	$task = $task->where("tasks.board_column_id", $request->board_column_id);
        }
      
      	if(isset($request->is_meeting) && !empty($request->is_meeting)){
        	$task = $task->where("tasks.is_meeting", $request->is_meeting);
        }else{
          	$task = $task->where("tasks.is_meeting", 0);
        }
        
      	$task = $task->limit($limit)->offset($offset);
      	$task = $task->orderBy("tasks.start_date", $order);
        $task = $task->get();

      	foreach($task as &$t){
          $t->users = DB::table('users')->where('id', $t->assignee_user_id)->select('id','name')->get();
          $t->project = DB::table('projects')->where('id', $t->project_id)->select('id','project_name')->first();
          $t->category = DB::table('task_category')->where('id', $t->task_category_id)->select('id','category_name')->first();
          
          $t->create_by = DB::table('users')->where('id', $t->created_by)->select('id','name')->first();
          $t->history = DB::table('task_history')->where('task_id', $t->id)->select('id','user_id','details','board_column_id')->get();
          
          $t->files = DB::table('task_files')->where('task_id', $t->id)->select('id','filename')->get();
          $t->taskboardColumns = DB::table('taskboard_columns')->where('id', $t->board_column_id)->select('*')->first();
          
          $new_chat_orang_kepercayaan = false;  
          $new_chat_orang_biasa = false;  
          $check_new_chat_orang_kepercayaan = TaskChatComment::where('task_id',$t->id)->where('is_opened',0)->where('is_orang_kepercayaan',1)->count();
          if ($check_new_chat_orang_kepercayaan>0) {
            $new_chat_orang_kepercayaan=true;
          }
          $check_new_chat_orang_biasa = TaskChatComment::where('task_id',$t->id)->where('is_opened',0)->where('is_orang_kepercayaan',0)->count();
          if ($check_new_chat_orang_biasa>0) {
            $new_chat_orang_biasa=true;
          }
          $t->new_chat_orang_kepercayaan = $new_chat_orang_kepercayaan;
          $t->new_chat_orang_biasa = $new_chat_orang_biasa;
           //id,heading,description,start_date,due_date,board_column_id,users{id,name},project{id,project_name},category{id,category_name},priority,status,is_private,create_by{id,name},history{id,user_id,details,board_column_id},files{id,filename},board_column_id,taskboardColumns{*},interval_report
            
           
        }
        // return $task;
        return ApiResponse::make('Get users task success', [
            'task' => $task,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }
    public function startTask(APIRequest $request){
        $flagErrorMail = false;
        $user = auth()->user();
      	$taskBoard = TaskboardColumn::where("slug", "in_progress")->where("company_id", $user->company_id)->first();
		    $task = TaskApp::where('id', $request->task_id)->first();
      	if($task && $task->exists() && $task->count() > 0){
          $task->board_column_id = $taskBoard->id;
          $task->save();
          
          $activeTimer = ProjectTimeLog::with('user')
              ->whereNull('end_time')
              ->join('users', 'users.id', '=', 'project_time_logs.user_id')
              ->where('user_id', $user->id)
              ->where('task_id', $task->id)
              ->first();
          if (is_null($activeTimer)) {
              $timeLog = new ProjectTimeLog();
              $timeLog->project_id = $task->project_id;
              $timeLog->task_id = $task->id;

              $timeLog->user_id = $user->id;
              $timeLog->start_time = Carbon::now();

              $timezone_attendance = Attendance::where('user_id', $user->id)->orderBy('id','desc')->first();
              if ($timezone_attendance) {
                  $timezone = $timezone_attendance->clock_in_timezone;
              }else{
                  $timezone = 7;
              }

              if ($timezone>=0) {
                $date_now = Carbon::now()->addHours($timezone);
              }else{
                $date_now = Carbon::now()->subHours(abs($timezone));
              }
              $timeLog->start_at = Carbon::now();
              $timeLog->start_at_gmt = $date_now;

              $timeLog->memo = "";
              $timeLog->save();

              $this->logProjectActivity($task->project_id, __('messages.timerStartedBy') . ' ' . ucwords($timeLog->user->name));

              // $assignee = User::find($task->assignee_user_id);
              // $assignee->notify(new TaskUpdated($task));

              //notif atasan
              $loginEmployee = EmployeeDetails::where('user_id', '=', $user->id)->first();
              $json = json_decode($loginEmployee->permission_require);
              if(isset($json[0]) && !empty($json[0])){
                $atasan = User::find($json[0]);
                try {
                  $atasan->notify(new TaskUpdated($task,$atasan));
                } catch (\Throwable $th) {
                    $flagErrorMail = true;
                }
                // $atasan->notify(new TaskUpdated($task));
              }

              // send notif to cc user
              $arr_cc_user_id = json_decode($task->cc_user_id);
              foreach ($arr_cc_user_id as $cc_user) {
                $cc_user_notif = User::find($cc_user);
                if (isset($cc_user_notif) && !empty($cc_user_notif)) {
                   try {
                     $cc_user_notif->notify(new CCTugas($task,$cc_user_notif,'started'));
                   } catch (\Throwable $th) {
                       $flagErrorMail = true;
                   }
                }
              }

              // send notif to orang kepercayaan
              // get orang kepercayaan pembuat tugas
              $get_orang_kepercayaan = DB::table('employee_details')->where('user_id',$task->created_by)->select('user_orang_kepercayaan')->get();
              foreach ($get_orang_kepercayaan as $val) {
                $arr_orang_kepercayaan = json_decode($val->user_orang_kepercayaan, true);
                if (!empty($arr_orang_kepercayaan)) {
                  foreach ($arr_orang_kepercayaan as $row) {
                    // get user
                    $cc_orang_kepercayaan = User::find($row);
                    if (isset($cc_orang_kepercayaan) && !empty($cc_orang_kepercayaan)) {
                      try {
                        $cc_orang_kepercayaan->notify(new CCTugasOrangKepercayaan($task,$cc_orang_kepercayaan,'started'));
                      } catch (\Throwable $th) {
                          $flagErrorMail = true;
                      }
                    }
                  }
                }
              }

          }
          else{
            if ($flagErrorMail) {
              return ApiResponse::make('Tugas telah dimulai, Email error silahkan hubungi developer', [
                          'task' => $task,
                          'timeLog' => $activeTimer
              ]);
            }else{
              return ApiResponse::make('Tugas telah dimulai', [
                          'task' => $task,
                          'timeLog' => $activeTimer
              ]);
            }
          }

          if ($flagErrorMail) {
            return ApiResponse::make('Start task success, Email error silahkan hubungi developer', [
                        'task' => $task
            ]);
          }else{
            return ApiResponse::make('Start task success', [
                        'task' => $task
            ]);
          }
        }
        return ApiResponse::make('Task not found', [
            'task' => $task
        ]);
    }
    public function stopTask(APIRequest $request){
        $flagErrorMail = false;
        $user = auth()->user();
        $task = TaskApp::where('id', $request->task_id)->first(); 
      	//if(isset($request->task_done) && $request->task_done == 1){
          //$taskBoard = TaskboardColumn::where("slug", "completed")->where("company_id", $user->company_id)->first();
        //}
      	$activeTimer = ProjectTimeLog::with('user')
            ->whereNull('end_time')
            ->join('users', 'users.id', '=', 'project_time_logs.user_id')
            ->where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->select('project_time_logs.*')
          	->first();
      if (isset($activeTimer) && !empty($activeTimer)) {
        $timeLog = ProjectTimeLog::find($activeTimer->id);
        if(isset($request->start_time) && isset($request->end_time)){
          $timeLog->start_time = date("Y-m-d H:i:s", strtotime($request->start_time." -7 HOURS"));
          $timeLog->end_time = date("Y-m-d H:i:s", strtotime($request->end_time." -7 HOURS"));
          
        }
        else{
          $timeLog->end_time = Carbon::now();
        }
        
        
          	$upload_file = [];	
          	for($i=0;$i<30;$i++){
              	$name = "files".$i;
            	if(isset($request->$name) && !empty($request->$name)){
                  $upload_file[] = $request->$name;
                }  
            }
          	foreach($upload_file as $fileData){
                $file = new ProjectTimeLogFile();
                $file->user_id = $user->id;
                $file->company_id = $user->company_id;
                $file->project_time_log_id = $timeLog->id;

                $filename = Files::uploadLocalOrS3($fileData,'project-time-log-files/'.$timeLog->id);

                $file->filename = $fileData->getClientOriginalName();
                $file->hashname = $filename;
                $file->size = $fileData->getSize();
                $file->save();
            }
        /*
        if(isset($request->image) && !empty($request->image)){
          $filename = Files::upload($request->image,'task-files/'.$task->id);
          $timeLog->image = $filename;
        }
        */

        $timezone_attendance = Attendance::where('user_id', $user->id)->orderBy('id','desc')->first();
        if ($timezone_attendance) {
            $timezone = $timezone_attendance->clock_in_timezone;
        }else{
            $timezone = 7;
        }

        if ($timezone>=0) {
          $date_now = Carbon::now()->addHours($timezone);
        }else{
          $date_now = Carbon::now()->subHours(abs($timezone));
        }
        $timeLog->stop_at = Carbon::now();
        $timeLog->stop_at_gmt = $date_now;
        
        if($request->task_done === true || $request->task_done === "true"){
          //print_r("1 ".$request->task_done); exit();
          $timeLog->task_done = 1;

          $timeLog->done_at = Carbon::now();
          $timeLog->done_at_gmt = $date_now;
          
          $taskBoard = TaskboardColumn::where("slug", "in_review")->where("company_id", $user->company_id)->first();
          $task->board_column_id = $taskBoard->id;
          $task->save();
        }
        else{  
          //print_r("2 ".$request->task_done); exit();
        }
        
        if(isset($request->latitude) && !empty($request->latitude)){
          $timeLog->latitude = $request->latitude;
        }
        if(isset($request->longitude) && !empty($request->longitude)){
          $timeLog->longitude = $request->longitude;
        }
        if(isset($request->memo) && !empty($request->memo)){
          $timeLog->memo = $request->memo;
        }
        //$timeLog->save();
        $timeLog->status = "in_review";
        $timeLog->total_hours = $timeLog->end_time->diff($timeLog->start_time)->format('%d') * 24 + $timeLog->end_time->diff($timeLog->start_time)->format('%H');
        $timeLog->total_minutes = ($timeLog->total_hours * 60) + ($timeLog->end_time->diff($timeLog->start_time)->format('%i'));
        $timeLog->edited_by_user = $user->id;

        // if (isset($request->cc_user_id) && !empty($request->cc_user_id)) {
        //   $timeLog->cc_user_id = $request->cc_user_id;

        //   // send notif to cc user_id
        //   $arr_cc_user_id = json_decode($request->cc_user_id);
        //   foreach ($arr_cc_user_id as $cc_user) {
        //     $cc_user_notif = User::find($cc_user);
        //     if (isset($cc_user_notif) && !empty($cc_user_notif)) {
        //         $cc_user_notif->notify(new CCUser($task));
        //     }
        //   }
        // }else{
        //   $timeLog->cc_user_id = "[]";
        // }

        $timeLog->save();
          $this->logProjectActivity($task->project_id, __('messages.timerStoppedBy') . ' ' . ucwords($timeLog->user->name));
        
        $assignee = User::find($task->assignee_user_id);
        //$assignee->notify(new TaskUpdated($task));
        //notif atasan
        $loginEmployee = EmployeeDetails::where('user_id', '=', $user->id)->first();
        $json = json_decode($loginEmployee->permission_require);
        if(isset($json[0]) && !empty($json[0])){
          $atasan = User::find($json[0]);
          try {
            $atasan->notify(new TaskUpdated($task,$atasan));
          } catch (\Throwable $th) {
              $flagErrorMail = true;
          }
          // $atasan->notify(new TaskUpdated($task));
        }

        // send notif to cc_user_id
        $arr_cc_user_id = json_decode($task->cc_user_id);
        if (isset($arr_cc_user_id) && !empty($arr_cc_user_id)){  
         foreach ($arr_cc_user_id as $cc_user) {
           $cc_user_notif = User::find($cc_user);
           if (isset($cc_user_notif) && !empty($cc_user_notif)) {
              try {
                $cc_user_notif->notify(new CCTugas($task,$cc_user_notif, 'stoped'));
              } catch (\Throwable $th) {
                  $flagErrorMail = true;
              }
           }
         }
        }

        // send notif to orang kepercayaan
        // get orang kepercayaan pembuat tugas
        $get_orang_kepercayaan = DB::table('employee_details')->where('user_id',$task->created_by)->select('user_orang_kepercayaan')->get();
        foreach ($get_orang_kepercayaan as $val) {
          $arr_orang_kepercayaan = json_decode($val->user_orang_kepercayaan, true);
          if (!empty($arr_orang_kepercayaan)) {
            foreach ($arr_orang_kepercayaan as $row) {
              // get user
              $cc_orang_kepercayaan = User::find($row);
              if (isset($cc_orang_kepercayaan) && !empty($cc_orang_kepercayaan)) {
                try {
                  $cc_orang_kepercayaan->notify(new CCTugasOrangKepercayaan($task,$cc_orang_kepercayaan,'stoped'));
                } catch (\Throwable $th) {
                    $flagErrorMail = true;
                }
              }
            }
          }
        }
        
      }else{
        return ApiResponse::make('Task timelog berjalan tidak ditemukan', [
        ]);
        
      }
      
        if ($flagErrorMail) {
          return ApiResponse::make('Stop task success, Email error silahkan hubungi developer', [
                      'task' => $task,
                      'timeLog' => $timeLog
          ]);
        }else{
          return ApiResponse::make('Stop task success', [
                      'task' => $task,
                      'timeLog' => $timeLog
          ]);

        }
    }
    public function getDetail(APIRequest $request){
      // ,'taskboardColumns'
      $task = Task::where('id', $request->id)
        ->with(['project','users','create_by','category','history','taskboardColumns'])
        // ->with('project','users','create_by','category','history','files')
        ->first();
      if (isset($task) && !empty($task)){ 
        $task->files = TaskFile::where('task_id', $task->id)->get();
      }
      return ["data"=>$task];
    }
    public function storeTask(APIRequest $request){
        try {
            DB::beginTransaction();
            $user = auth()->user();
          	$this->global = $this->company = Company::withoutGlobalScope('active')->where('id', $user->company_id)->first();

          	$ganttTaskArray = [];
            $gantTaskLinkArray = [];
            $taskBoardColumn = TaskboardColumn::where('slug', 'incomplete')->first();

        	$users_assigned = json_decode($request->user_id);
        	foreach ($users_assigned as $assignee_user_id) {
	            $task = new Task();
	            $task->heading = $request->heading;
	            if ($request->description != '') {
	                $task->description = $request->description;
              }
	          	
	            $task->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
	            $task->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
	            $task->project_id = $request->project_id;
	            
	          	//$task->task_category_id = $request->category_id;
	            //$task->priority = $request->priority;
	          	$task->priority = "medium";
	            $task->board_column_id = $taskBoardColumn->id;
	            $task->created_by = $user->id;
	            //$task->dependent_task_id = $request->has('dependent') && $request->dependent == 'yes' && $request->has('dependent_task_id') && $request->dependent_task_id != '' ? $request->dependent_task_id : null;
	          	if($request->dependent_task_id){
	            	$task->dependent_task_id = $request->dependent_task_id;
	            }
	          
	          	
	          	$task->is_private = 1;
	            $task->is_requires_gps = $request->has('is_requires_gps') && $request->is_requires_gps == 'true' ? 1 : 0;
	            $task->is_requires_camera = $request->has('is_requires_camera') && $request->is_requires_camera == 'true' ? 1 : 0;
	            $task->interval_report = $request->has('interval_report_check') && $request->interval_report_check == 'true' ? $request->interval_report : 0;
	          	$task->assignee_user_id = $assignee_user_id;

	            if ($request->board_column_id) {
	                $task->board_column_id = $request->board_column_id;
	            }

	            if ($taskBoardColumn->slug == 'completed') {
	                $task->completed_on = Carbon::now()->format('Y-m-d H:i:s');
	            } else {
	                $task->completed_on = null;
              }
              if (isset($request->is_meeting) && !empty($request->is_meeting)) {
                $task->is_meeting = $request->is_meeting;
              }

              if (isset($request->cc_user_id) && !empty($request->cc_user_id)) {
                $task->cc_user_id = $request->cc_user_id;
                // send notif to cc user
                $arr_cc_user_id = json_decode($request->cc_user_id);
                foreach ($arr_cc_user_id as $cc_user) {
                  $cc_user_notif = User::find($cc_user);
                  if (isset($cc_user_notif) && !empty($cc_user_notif)) {
                     try {
                       $cc_user_notif->notify(new CCTugas($task,$cc_user_notif));
                     } catch (\Throwable $th) {
                         $flagErrorMail = true;
                     }
                  }
                }
              }else{
                $task->cc_user_id = "[]";
              }

	            $task->created_at = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i:sP');
              $task->save();

              // send notif to orang kepercayaan
              // get orang kepercayaan pembuat tugas
              $get_orang_kepercayaan = DB::table('employee_details')->where('user_id',$task->created_by)->select('user_orang_kepercayaan')->get();
              foreach ($get_orang_kepercayaan as $val) {
                $arr_orang_kepercayaan = json_decode($val->user_orang_kepercayaan, true);
                if (!empty($arr_orang_kepercayaan)) {
                  foreach ($arr_orang_kepercayaan as $row) {
                    // get user
                    $cc_orang_kepercayaan = User::find($row);
                    if (isset($cc_orang_kepercayaan) && !empty($cc_orang_kepercayaan)) {
                      try {
                        $cc_orang_kepercayaan->notify(new CCTugasOrangKepercayaan($task,$cc_orang_kepercayaan));
                      } catch (\Throwable $th) {
                          $flagErrorMail = true;
                      }
                    }
                  }
                }
              }
	          
	          	$this->logProjectActivity($task->project_id, __('messages.newTaskAddedToTheProject'));
	                  
	            TaskUser::create(
	                [
	                    'user_id' => $assignee_user_id,
	                    'task_id' => $task->id
	                ]
	              );
	          
	          	$upload_file = [];	
	          	for($i=0;$i<30;$i++){
	              	$name = "files".$i;
	            	if(isset($request->$name) && !empty($request->$name)){
	                  $upload_file[] = $request->$name;
	                }  
	            }
	          	foreach($upload_file as $fileData){
	                $file = new TaskFile();
	                $file->user_id = $user->id;
	                $file->task_id = $task->id;

	                $filename = Files::uploadLocalOrS3($fileData,'task-files/'.$task->id);

	                $file->filename = $fileData->getClientOriginalName();
	                $file->hashname = $filename;
	                $file->size = $fileData->getSize();
	                $file->save();
	            }
	          
	            // Add repeated task
	            if ($request->has('repeat') && $request->repeat == 'yes') {
	                $repeatCount = $request->repeat_count;
	                $repeatType = $request->repeat_type;
	                $repeatCycles = $request->repeat_cycles;
	                $startDate = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
	                $dueDate = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');


                  $repeatStartDate = Carbon::createFromFormat('Y-m-d', $startDate);
                  $repeatDueDate = Carbon::createFromFormat('Y-m-d', $dueDate);
	                for ($i = 1; $i < $repeatCycles; $i++) {

	                    if ($repeatType == 'day') {
	                        $repeatStartDate = $repeatStartDate->addDays($repeatCount);
	                        $repeatDueDate = $repeatDueDate->addDays($repeatCount);
	                    } else if ($repeatType == 'week') {
	                        $repeatStartDate = $repeatStartDate->addWeeks($repeatCount);
	                        $repeatDueDate = $repeatDueDate->addWeeks($repeatCount);
	                    } else if ($repeatType == 'month') {
	                        $repeatStartDate = $repeatStartDate->addMonths($repeatCount);
	                        $repeatDueDate = $repeatDueDate->addMonths($repeatCount);
	                    } else if ($repeatType == 'year') {
	                        $repeatStartDate = $repeatStartDate->addYears($repeatCount);
	                        $repeatDueDate = $repeatDueDate->addYears($repeatCount);
	                    }
                      // check task exist
                      $checkTask = Task::where('heading',$request->heading);
                      if ($request->description != '') {
                        $checkTask = $checkTask->where('description',$request->description);
                      }
                      $checkTask = $checkTask->where('start_date',$repeatStartDate->format('Y-m-d'))
                        ->where('due_date',$repeatDueDate->format('Y-m-d'))
                        ->where('project_id',$request->project_id)
                        ->where('assignee_user_id',$assignee_user_id)
                        ->count();
                      if ($checkTask==0) {
                        // create new task if not duplicate
                        $newTask = new Task();
                        $newTask->heading = $request->heading;
                        if ($request->description != '') {
                            $newTask->description = $request->description;
                        }
                        $newTask->start_date = $repeatStartDate->format('Y-m-d');
                        $newTask->due_date = $repeatDueDate->format('Y-m-d');
                        $newTask->project_id = $request->project_id;
                        //$newTask->task_category_id = $request->category_id;
                        //$newTask->priority = $request->priority;
                        $newTask->priority = "medium";
                        $newTask->board_column_id = $taskBoardColumn->id;
                        $newTask->created_by = $user->id;
                        $newTask->recurring_task_id = $task->id;
                        $newTask->assignee_user_id = $assignee_user_id;
  
                        if ($request->board_column_id) {
                            $newTask->board_column_id = $request->board_column_id;
                        }
  
                        if ($taskBoardColumn->slug == 'completed') {
                            $newTask->completed_on = Carbon::now()->format('Y-m-d H:i:s');
                        } else {
                            $newTask->completed_on = null;
                        }
  
                        $newTask->save();
                        
                      
                            TaskUser::updateOrCreate (
                                [
                                    'user_id' => $assignee_user_id,
                                    'task_id' => $newTask->id
                                ]
                            );
                      }
	                }
	            }
        	}
            
          
            DB::commit();

            return ApiResponse::make('Task created', [
                        'task' => $task
            ]);
        } catch (Exception $e) {
            DB::rollback();
            // return ApiResponse::make('Attendance failed '.$e->getMessages());
            $exception = new ApiException('Attendance failed '.$e->getMessages(), null, 403, 403, 2001);
            return ApiResponse::exception($exception);
            
        }
    }
  	public function editTask(APIRequest $request){
        $userLogin = auth()->user();
        $edit_tugas = false;
        $additional_field = json_decode($userLogin->employeeDetail->additional_field, true);
        if ($additional_field) {
            if (isset($additional_field['edit_tugas'])){ 
                if ($additional_field['edit_tugas']==1) {
                    $edit_tugas = true;
                }
            }
        }
        if (!$edit_tugas) {
            return response()->json([
                'error' => [
                    'status' => 500,
                    'message' => 'User dont have permission to use this function',
                ]
            ]);
        }
        try {
            DB::beginTransaction();
          	$flagErrorMail = false;
            
          	if(!isset($request->task_id) || empty($request->task_id)){
                return ApiResponse::make('Task id is required', [
                ]);
            }
            $user = auth()->user();
          	$this->global = $this->company = Company::withoutGlobalScope('active')->where('id', $user->company_id)->first();
            
          	$ganttTaskArray = [];
            $gantTaskLinkArray = [];
            $taskBoardColumn = TaskboardColumn::where('slug', 'incomplete')->first();
            $task = Task::find($request->task_id);
          
          	if(empty($task)){
                return ApiResponse::make('Task not found', [
                ]);
            }
            $task->heading = $request->heading;
            if ($request->description != '') {
                $task->description = $request->description;
            }

            if (isset($request->cc_user_id) && !empty($request->cc_user_id)) {
              $task->cc_user_id = $request->cc_user_id;
            }else{
              $task->cc_user_id = "[]";
            }

            $task->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
            $task->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
            // check project_id exist
            $check_project = \DB::table('projects')->where('id', $request->project_id)->count();
            if($check_project == 0){
              throw new \Exception("Project not found");
            }
            $task->project_id = $request->project_id;
            
          	$task->priority = "medium";
            //$task->board_column_id = $taskBoardColumn->id;
            $task->created_by = $user->id;
            //$task->dependent_task_id = $request->has('dependent') && $request->dependent == 'yes' && $request->has('dependent_task_id') && $request->dependent_task_id != '' ? $request->dependent_task_id : null;
          	if($request->dependent_task_id){
            	$task->dependent_task_id = $request->dependent_task_id;
            }
          	$task->is_private = 1;
            $task->is_requires_gps = $request->has('is_requires_gps') && $request->is_requires_gps == 'true' ? 1 : 0;
            $task->is_requires_camera = $request->has('is_requires_camera') && $request->is_requires_camera == 'true' ? 1 : 0;
            $task->interval_report = $request->has('interval_report_check') && $request->interval_report_check == 'true' ? $request->interval_report : 0;

            if ($request->board_column_id) {
                $task->board_column_id = $request->board_column_id;
            }

            if ($taskBoardColumn->slug == 'completed') {
                $task->completed_on = Carbon::now()->format('Y-m-d H:i:s');
            } else {
                $task->completed_on = null;
            }
            $task->save();
          $this->logProjectActivity($task->project_id, __('messages.newTaskAddedToTheProject'));
            TaskUser::create(
                [
                    'user_id' => $request->user_id,
                    'task_id' => $task->id
                ]
              );
            // Sync task users
            // $task->users()->sync($request->user_id);

            // // For gantt chart
            // if ($request->page_name && $request->page_name == 'ganttChart') {
            //     $parentGanttId = $request->parent_gantt_id;

            //     $taskDuration = $task->due_date->diffInDays($task->start_date);
            //     $taskDuration = $taskDuration + 1;

            //     $ganttTaskArray[] = [
            //         'id' => $task->id,
            //         'text' => $task->heading,
            //         'start_date' => $task->start_date->format('Y-m-d'),
            //         'duration' => $taskDuration,
            //         'parent' => $parentGanttId,
            //         'taskid' => $task->id
            //     ];

            //     $gantTaskLinkArray[] = [
            //         'id' => 'link_' . $task->id,
            //         'source' => $parentGanttId,
            //         'target' => $task->id,
            //         'type' => 1
            //     ];
            // }
          	//$upload_file = $request->files->all()["files"];
          	$upload_file = [];	
          	for($i=0;$i<30;$i++){
              	$name = "files".$i;
            	if(isset($request->$name) && !empty($request->$name)){
                  $upload_file[] = $request->$name;
                }  
            }
          	foreach($upload_file as $fileData){
                $file = new TaskFile();
                $file->user_id = $user->id;
                $file->task_id = $task->id;

                $filename = Files::uploadLocalOrS3($fileData,'task-files/'.$task->id);

                $file->filename = $fileData->getClientOriginalName();
                $file->hashname = $filename;
                $file->size = $fileData->getSize();
                $file->save();
            }
            // Add repeated task
            if ($request->has('repeat') && $request->repeat == 'yes') {
                $repeatCount = $request->repeat_count;
                $repeatType = $request->repeat_type;
                $repeatCycles = $request->repeat_cycles;
                $startDate = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');;
                $dueDate = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');;


                for ($i = 1; $i < $repeatCycles; $i++) {
                    $repeatStartDate = Carbon::createFromFormat('Y-m-d', $startDate);
                    $repeatDueDate = Carbon::createFromFormat('Y-m-d', $dueDate);

                    if ($repeatType == 'day') {
                        $repeatStartDate = $repeatStartDate->addDays($repeatCount);
                        $repeatDueDate = $repeatDueDate->addDays($repeatCount);
                    } else if ($repeatType == 'week') {
                        $repeatStartDate = $repeatStartDate->addWeeks($repeatCount);
                        $repeatDueDate = $repeatDueDate->addWeeks($repeatCount);
                    } else if ($repeatType == 'month') {
                        $repeatStartDate = $repeatStartDate->addMonths($repeatCount);
                        $repeatDueDate = $repeatDueDate->addMonths($repeatCount);
                    } else if ($repeatType == 'year') {
                        $repeatStartDate = $repeatStartDate->addYears($repeatCount);
                        $repeatDueDate = $repeatDueDate->addYears($repeatCount);
                    }

                    $newTask = new Task();
                    $newTask->heading = $request->heading;
                    if ($request->description != '') {
                        $newTask->description = $request->description;
                    }
                    $newTask->start_date = $repeatStartDate->format('Y-m-d');
                    $newTask->due_date = $repeatDueDate->format('Y-m-d');
                    $newTask->project_id = $request->project_id;
                    //$newTask->task_category_id = $request->category_id;
                    //$newTask->priority = $request->priority;
                    $newTask->priority = "medium";
                    $newTask->board_column_id = $taskBoardColumn->id;
                    $newTask->created_by = $user->id;
                    $newTask->recurring_task_id = $task->id;

                    if ($request->board_column_id) {
                        $newTask->board_column_id = $request->board_column_id;
                    }

                    if ($taskBoardColumn->slug == 'completed') {
                        $newTask->completed_on = Carbon::now()->format('Y-m-d H:i:s');
                    } else {
                        $newTask->completed_on = null;
                    }

                    $newTask->save();
					
                  
                        TaskUser::create(
                            [
                                'user_id' => $request->user_id,
                                'task_id' => $newTask->id
                            ]
                        );
                    //foreach ($request->user_id as $key => $value) {
                    //}

                    // // For gantt chart
                    // if ($request->page_name && $request->page_name == 'ganttChart') {
                    //     $parentGanttId = $request->parent_gantt_id;
                    //     $taskDuration = $newTask->due_date->diffInDays($newTask->start_date);
                    //     $taskDuration = $taskDuration + 1;

                    //     $ganttTaskArray[] = [
                    //         'id' => $newTask->id,
                    //         'text' => $newTask->heading,
                    //         'start_date' => $newTask->start_date->format('Y-m-d'),
                    //         'duration' => $taskDuration,
                    //         'parent' => $parentGanttId,
                    //         'users' => [
                    //             ucwords($newTask->user->name)
                    //         ],
                    //         'taskid' => $newTask->id
                    //     ];

                    //     $gantTaskLinkArray[] = [
                    //         'id' => 'link_' . $newTask->id,
                    //         'source' => $parentGanttId,
                    //         'target' => $newTask->id,
                    //         'type' => 1
                    //     ];
                    // }

                    // $startDate = $newTask->start_date->format('Y-m-d');
                    // $dueDate = $newTask->due_date->format('Y-m-d');
                }
            }

            //calculate project progress if enabled
            //$this->calculateProjectProgress($request->project_id);

            ////if (!is_null($request->project_id)) {
                //$this->logProjectActivity($request->project_id, __('messages.newTaskAddedToTheProject'));
            //}

            //log search
            //$this->logSearchEntry($task->id, 'Task ' . $task->heading, 'admin.all-tasks.edit', 'task');


            DB::commit();

            if (!empty($task->assignee_user_id)) {
              $assignee = User::find($task->assignee_user_id);
              try {
                $assignee->notify(new TaskUpdated($task,$assignee));
              } catch (\Throwable $th) {
                  $flagErrorMail = true;
              }
              // $assignee->notify(new TaskUpdated($task));
            }
            if ($flagErrorMail) {
              return ApiResponse::make('Task edited, Email error silahkan hubungi developer', [
                          'task' => $task
              ]);
            }else{
              return ApiResponse::make('Task edited', [
                          'task' => $task
              ]);
            }
        } catch (Exception $e) {
            DB::rollback();
            // return ApiResponse::make('Attendance failed '.$e->getMessages());
            $exception = new ApiException('Attendance failed '.$e->getMessages(), null, 403, 403, 2001);
            return ApiResponse::exception($exception);
            
        }
    }
  	public function deleteTask(APIRequest $request){
      $userLogin = auth()->user();
      $delete_tugas = false;
      $additional_field = json_decode($userLogin->employeeDetail->additional_field, true);
      if ($additional_field) {
          if (isset($additional_field['delete_tugas'])){ 
              if ($additional_field['delete_tugas']==1) {
                  $delete_tugas = true;
              }
          }
      }
      if (!$delete_tugas) {
          return response()->json([
              'error' => [
                  'status' => 500,
                  'message' => 'User dont have permission to use this function',
              ]
          ]);
      }
    	$id = $request->task_id;
        $task = Task::findOrFail($id);

        // If it is recurring and allowed by user to delete all its recurring tasks
        //if ($request->has('recurring') && $request->recurring == 'yes') {
           // Task::where('recurring_task_id', $id)->delete();
        //}

        Task::destroy($id);
        return ApiResponse::make('Task deleted', [
        ]);
  	}
    public function logProjectActivity($projectId, $text)
    {
        $activity = new ProjectActivity();
        $activity->project_id = $projectId;
        $activity->activity = $text;
        $activity->save();
    }
  
  
    public function getListPelaporanTugas(APIRequest $request){
        $user = auth()->user();
      	$status = $request->slug;
      	if($status == "masuk"){
      		$taskBoard = TaskboardColumn::where("slug", "in_review")->where("company_id", $user->company_id)->first();
        }
      	else if($status == "diterima"){
      		$taskBoard = TaskboardColumn::where("slug", "completed")->where("company_id", $user->company_id)->first();
        }
      	else if($status == "ditolak"){
      		$taskBoard = TaskboardColumn::where("slug", "rejected")->where("company_id", $user->company_id)->first();
        }
      	
        $loginEmployee = EmployeeDetails::where('user_id', '=', $user->id)->first();
      
        $employee = EmployeeDetails::where("permission_require", "LIKE", "%\"".$user->id."\"%")
        ->pluck("user_id");   
        $assignee = User::whereIn("id", $employee)->pluck("id");
      	$allMyTask = TaskUser::whereIn("user_id", $assignee)->pluck("task_id");
		//$task = TaskApp::whereIn('id', $allMyTask)->where("board_column_id", $taskBoard->id)->with('project','users','create_by','category','history','files')->get();
		$task = TaskApp::where("board_column_id", $taskBoard->id)->with('project','users','create_by','category','history','files')->get();
        return ApiResponse::make('Get users task success', [
                    'task' => $task
        ]);
    }
  
    public function terimaTask(APIRequest $request){
        $user = auth()->user();
      	$taskBoard = TaskboardColumn::where("slug", "completed")->where("company_id", $user->company_id)->first();
		$task = TaskApp::where('id', $request->task_id)->first();
        $task->board_column_id = $taskBoard->id;
        $task->save();
      
        return ApiResponse::make('Accept task success', [
                    'task' => $task
        ]);
    }
  
    public function tolakTask(APIRequest $request){
        $user = auth()->user();
      	$taskBoard = TaskboardColumn::where("slug", "rejected")->where("company_id", $user->company_id)->first();
		$task = TaskApp::where('id', $request->task_id)->first();
        $task->board_column_id = $taskBoard->id;
        $task->save();
      
        return ApiResponse::make('Reject task success', [
                    'task' => $task
        ]);
    }
    
}
