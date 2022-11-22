<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Events\TaskReminderEvent;
use App\Helper\Reply;
use App\Notifications\TaskReminder;
use App\Role;
use App\TaskUser;
use App\User;
use App\Helper\Files;
use Froiden\RestAPI\ApiController;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Support\Facades\Notification;
// use Modules\RestAPI\Entities\Employee;
use Modules\RestAPI\Entities\ProjectTimeLog;
use Modules\RestAPI\Http\Requests\ProjectTimeLog\IndexRequest;
use Modules\RestAPI\Http\Requests\ProjectTimeLog\CreateRequest;
use Modules\RestAPI\Http\Requests\ProjectTimeLog\ShowRequest;
use Modules\RestAPI\Http\Requests\ProjectTimeLog\UpdateRequest;
use Modules\RestAPI\Http\Requests\ProjectTimeLog\DeleteRequest;
use App\EmployeeDetails;
use Modules\RestAPI\Entities\Task;
use App\TaskboardColumn;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\API\APIRequest;
use Carbon\Carbon;
use Modules\RestAPI\Entities\Attendance;

class ProjectTimeLogController extends ApiBaseController
{

    protected $model = ProjectTimeLog::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query)
    {
        $user = auth()->user();
      	$userIdAllowed = [];
        $employee = EmployeeDetails::where("permission_require", "LIKE", "%\"".$user->id."\"%")->pluck("user_id");  
		
      	$assignee = User::whereIn("id", $employee)->orWhere("id", $user->id)->pluck("id"); // bisa bawahan dan lihat diri sendiri
		//$assignee = User::whereIn("id", $employee)->pluck("id"); // hanya bisa lihat milik bawahan
        return $query->whereIn("user_id", $assignee);
    }
    // public function modifyCreate($query)
    // {
    //     print_r($query);die;

    //     return $query;
    // }

    public function storing(ProjectTimeLog $timelog)
    {
        if ($timelog->image) {
            $timelog->image = Files::upload($timelog->image, 'screenshot', 600);
        }

        return $timelog;
    }

    public function updated(ProjectTimeLog $timelog)
    {
      $user = auth()->user();
      $task = Task::find($timelog->task_id);
      if ($timelog->status == "accepted") {
        if ($timelog->task_done == 1) {
          $taskBoard = TaskboardColumn::where("slug", "completed")->where("company_id", $user->company_id)->first();
          \DB::table('tasks')
          ->where("id", $timelog->task_id)
          ->update([
            "board_column_id"=> $taskBoard->id
          ]);
          // $task->board_column_id = $taskBoard->id;
          // $task->save();
        }
        $modelUpdate = ProjectTimeLog::find($timelog->id);
        if (!empty($modelUpdate)) {
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
          $modelUpdate->checker_at = Carbon::now();
          $modelUpdate->checker_at_gmt = $date_now;
          $modelUpdate->save();
        }
      } else if ($timelog->status == "rejected") {
        if ($timelog->task_done == 1) {
          $taskBoard = TaskboardColumn::where("slug", "incomplete")->where("company_id", $user->company_id)->first();
          \DB::table('tasks')
          ->where("id", $timelog->task_id)
          ->update([
            "board_column_id"=> $taskBoard->id
          ]);
          // $task->board_column_id = $taskBoard->id;
          // $task->save();
        }
      }

      //return $this->syncTaskUsers($timelog);
    }

    // private function syncTaskUsers(ProjectTimeLog $task){
    //     if (request()->get('task_users')) {
    //         $ids = array_column(request()->get('task_users'),'id');
    //         $task->users()->sync($ids);
    //     }

    //     return $task;
    // }

    // public function remind($taskID)
    // {
    //     $task = \App\Task::findOrFail($taskID);
    //     event(new TaskReminderEvent($task));
    //     return ApiResponse::make(__('messages.reminderMailSuccess'));
    // }
  public function list(APIRequest $request)
  {
    // dd('asd');
    $user = auth()->user();
    //$taskBoard = TaskboardColumn::where("slug", $request->slug)->where("company_id", $user->company_id)->first();
    //$allMyTask = TaskUser::where("user_id", $user->id)->pluck("task_id");
    $limit = isset($request->limit) ? $request->limit : 10;
    $offset = isset($request->offset) ? $request->offset : 0;

    //$time_log = new ProjectTimeLog;
    $time_log = DB::table('project_time_logs');
    if (isset($request->start_date) && !empty($request->start_date)) {
      $time_log = $time_log->where('start_time', '>=', $request->start_date);
    }

    if (isset($request->end_date) && !empty($request->end_date)) {
      $time_log = $time_log->where('start_time', '<=', $request->end_date);
    }
    if (isset($request->status) && !empty($request->status)) {
      if($request->status == "null"){
        $time_log = $time_log->whereNull('status');
      }
      else{
        $time_log = $time_log->where('status', $request->status);
      }
    }

    if (isset($request->assignee_user_id) && !empty($request->assignee_user_id)) {
      if ($request->assignee_user_id == "all") {
        $employee = EmployeeDetails::where("permission_require", "LIKE", "%\"" . $user->id . "\"%")->pluck("user_id");
        //$time_log = $time_log->whereIn('user_id', $employee)->orWhere('user_id', $user->id

        $cc_user_project_id = DB::table('project_time_logs')->where("cc_user_id", "LIKE", "%\"" . $user->id . "\"%")->pluck("id");                                
            $time_log = $time_log->where(function ($query) use ($employee, $user, $cc_user_project_id) {
                $query->whereIn('user_id', $employee)
                      ->orWhereIn('id',$cc_user_project_id)
                      ->orWhere('user_id', $user->id);
            });
      } else if ($request->assignee_user_id == "diri_sendiri") {
        $time_log = $time_log->where('user_id', $user->id);
      } else if ($request->assignee_user_id == "all_bawahan") {
        $employee = EmployeeDetails::where("permission_require", "LIKE", "%\"" . $user->id . "\"%")->pluck("user_id");
        $cc_user_project_id = DB::table('project_time_logs')->where("cc_user_id", "LIKE", "%\"" . $user->id . "\"%")->pluck("id");     

        $time_log = $time_log->whereIn('user_id', $employee)
                    ->orWhereIn('id',$cc_user_project_id);
      } else {
        $time_log = $time_log->where('user_id', $request->assignee_user_id);
      }
    }
    //17/12 cleming need_my_approval
    if (isset($request->need_my_approval) && $request->need_my_approval == "true"){
      //$tasks_created_by_me = DB::table('tasks')->where('created_by', $user->id)->pluck('id');
      //$time_log = $time_log->whereIn('task_id', $tasks_created_by_me);
      
        $employee = EmployeeDetails::where("permission_require", "LIKE", "[\"" . $user->id . "\"%")->pluck("user_id");
        $time_log = $time_log->whereIn('user_id', $employee);
    }
    //17/12 cleming need_my_approval
    // if (isset($request->assignee_user_id) && !empty($request->assignee_user_id)) {
    //   if ($request->assignee_user_id == "all") {
    //     $employee = EmployeeDetails::where("permission_require", "LIKE", "%\"" . $user->id . "\"%")->pluck("user_id");
    //     $time_log = $time_log->whereIn('assignee_user_id', $employee)->orWhere('assignee_user_id', $user->id);
    //   } else if ($request->assignee_user_id == "diri_sendiri") {
    //     $time_log = $time_log->where('assignee_user_id', $user->id);
    //   } else if ($request->assignee_user_id == "all_bawahan") {

    //     $employee = EmployeeDetails::where("permission_require", "LIKE", "%\"" . $user->id . "\"%")->pluck("user_id");
    //     $time_log = $time_log->whereIn('assignee_user_id', $employee);
    //   } else {
    //     $time_log = $time_log->where('assignee_user_id', $request->assignee_user_id);
    //   }
    // }

    $time_log = $time_log->limit($limit)->offset($offset)->orderBy('created_at','DESC');
    $time_log = $time_log->get();
    foreach ($time_log as &$t) {
      // $t->task = DB::table('tasks')->where('id', $t->task_id)->get();
      $task = DB::table('tasks')->where('id', $t->task_id)->get();
      if (count($task)>0) {
        foreach ($task as $item) {
          // get creator
          $created_by = DB::table('users')->where('id',$item->created_by)->select('id','name')->first();
          $item->created_by_user = $created_by;
        }
      }
      $t->task = $task;
      $user_review = DB::table('users')->where('id',$t->checker_user_id)->select('id','name')->first();
      if ($t->status=='accepted') {
        $t->di_review_oleh = $user_review;
      }else{
        $t->di_review_oleh = null;
      }
      if ($t->status=='rejected') {
        $t->di_tolak_oleh = $user_review;
      }else{
        $t->di_tolak_oleh = null;
      }
      $t->project = DB::table('projects')->where('id', $t->project_id)->first();
      $t->user = DB::table('users')->where('id', $t->user_id)->first();
      $t->start_time = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $t->start_time)->toIso8601String();
      if(isset($t->end_time))
        $t->end_time = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $t->end_time)->toIso8601String();
      if(isset($t->created_at))
        $t->created_at = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $t->created_at)->toIso8601String();
      if(isset($t->updated_at))
        $t->updated_at = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $t->updated_at)->toIso8601String();
    }
    return ApiResponse::make('Get users project time log success', [
      'project_time_log' => $time_log,
      'limit' => $limit,
      'offset' => $offset,
    ]);
  }
  

}
