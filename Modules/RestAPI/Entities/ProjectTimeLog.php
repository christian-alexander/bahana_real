<?php

namespace Modules\RestAPI\Entities;

use App\EmployeeDetails;
use App\User;
use App\Task;
use App\Project;
use App\ProjectTimeLogFile;
use App\TaskChatComment;

class ProjectTimeLog extends \App\ProjectTimeLog
{
    // region Properties

    protected $table = 'project_time_logs';
    protected $fillable= ['start_time','end_time','image','memo','total_hours', 'total_minutes', 'company_id', 'task_id', 'user_id', 'project_id','location','status','checker_user_id', 'reason'];
    protected $appends = ['checker', 'image_full_path','list_comments'];

    protected $default = [
        'id',
        'task_id',
        'start_time',
        'end_time',
        'image',
        'memo',
       // 'location',
        'total_hours',
        'total_minutes',
        'created_at',
        'updated_at',
      	'status',
      	'checker_user_id',
      	'latitude',
      	'longitude',
      	'reason',
      	'image_full_path',
    ];

    protected $hidden = [
        // 'user_id',
        // 'project_id',
        // 'task_category_id',
        // 'created_by'
    ];

    protected $guarded = [
        'id',
    ];

    protected $filterable = [
        'id',
        'task_id',
        'user_id',
        'company_id',
        'project_id',
      	'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // public function checker_user()
    // {
    //     return $this->belongsTo(User::class, 'checker_user_id','id');
    // }
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function getListCommentsAttribute(){
        $user = auth()->user();
        $data = TaskChatComment::with('user')->where('task_id',$this->task_id)->get();
        foreach ($data as $key => $item) {
            if ($item->is_orang_kepercayaan==1) {
                // get task data
                $task = Task::find($item->task_id);
                // get creator task
                $creator_task = EmployeeDetails::where('user_id',$task->created_by)->first();
                // check orang kepercayaan
                $check_orang_kepercayaan = Task::checkUserIsMyAtasanOrangKepercayaan($creator_task->user_id,$user->id,$task->id);
                
                if (!$check_orang_kepercayaan) {
                    unset($data[$key]);
                }
                // check user login is creator of comment/chat
                if ($item->created_by!=$user->id) {
                    if ($creator_task->user_id!=$user->id) {
                        unset($data[$key]);
                    }
                }
            }
        }
        return $data;
    }
    // public function taskboardColumns()
    // {
    //     return $this->belongsTo(TaskboardColumn::class, 'board_column_id');
    // }
	public function getCheckerAttribute(){
      if(isset($this->checker_user_id)){
        $checker = User::find($this->checker_user_id);
        if(isset($checker) && !empty($checker)){
          return $checker;
        }
      }
      
      return null;
    }
	public function getImageFullPathAttribute(){
      	if(isset($this->image) && !empty($this->image))
          return (!is_null($this->external_link)) ? $this->external_link : asset_url_local_s3('task-files/'.$this->task_id.'/'.$this->image);
      	else
          return null;
    }

    public function files()
    {
        return $this->hasMany(ProjectTimeLogFile::class, 'project_time_log_id');
    }
}
