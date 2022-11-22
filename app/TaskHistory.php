<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class TaskHistory extends BaseModel
{
    protected $table = "task_history";
    protected $appends = ['activity_log','date_time'];

   	protected $dates = ['created_at', 'updated_at'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function sub_task()
    {
        return $this->belongsTo(SubTask::class, 'sub_task_id');
    }

    public function board_column()
    {
        return $this->belongsTo(TaskboardColumn::class, 'board_column_id');
    }
  

    public function getActivityLogAttribute()
    {	
      	$message = "";
      	$user = User::find($this->user_id);
      	$message .= $user->name." ";
      	if($this->details == "createActivity"){
          $message .= "menambahkan";
        }
      	else if($this->details == "updateActivity"){
          if($this->board_column_id == 5)
            $message .= "mengerjakan";
          if($this->board_column_id == 6)
            $message .= "menyelesaikan";
          if($this->board_column_id == 4)
            $message .= "menerima";
          if($this->board_column_id == 7)
            $message .= "menolak";
        }
      	$message .= " tugas ini";
      	return $message;
        //return (!is_null($this->external_link)) ? $this->external_link : asset_url_local_s3('task-files/'.$this->task_id.'/'.$this->hashname);
    }
    public function getDateTimeAttribute()
    {	
      	$task = TaskHistory::where('id', $this->id)->select('created_at')->first();
      	return $task->created_at;
        //return (!is_null($this->external_link)) ? $this->external_link : asset_url_local_s3('task-files/'.$this->task_id.'/'.$this->hashname);
    }
    
}
