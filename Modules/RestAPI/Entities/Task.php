<?php

namespace Modules\RestAPI\Entities;


use App\TaskboardColumn;
use App\TaskHistory;
use App\TaskFile;
use App\ProjectTimeLog;
use Illuminate\Support\Facades\DB;

class Task extends \App\Task
{
    // region Properties

    protected $table = 'tasks';
    protected $fillable= ['heading','start_date','priority','due_date','is_private','status', 'board_column_id'];
    protected $appends = ['all_board_columns', 'time_log_active', 'timelog','atasan_1','cc_user','assignee_user'];

    protected $default = [
        'id',
        'heading',
        'start_date',
        'priority',
        'due_date',
        'is_private',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'project_id',
        'task_category_id',
        //'board_column_id',
        'created_by'
    ];

    protected $guarded = [
        'id',
    ];

    protected $filterable = [
        'id',
        'heading',
        'project_id',
        'board_column_id',
        'assignee_user_id',
        'due_date',
        'start_date',
        'is_meeting',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_users');
    }
    /**
     * Get the checker associated with the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function taskboardColumns()
    {
        return $this->belongsTo(TaskboardColumn::class, 'board_column_id');
    }

    public function getAllBoardColumnsAttribute()
    {
        return TaskboardColumn::all();
    }
    public function getCCUserAttribute()
    {
        $cc_user_id = json_decode($this->cc_user_id);
        $arr =[];
        if (isset($cc_user_id) && !empty($cc_user_id)){ 
            foreach ($cc_user_id as $key => $val) {
                $user = User::with(['employeeDetail.department'])->find($val);
                array_push($arr,$user);
            }
        }
        return $arr;
    }
    public function getAssigneeUserAttribute()
    {
        $assignee_user_id = $this->assignee_user_id;
        $response = null;
        if (!empty($assignee_user_id)) {
            $response = User::with(['employeeDetail.department'])->find($assignee_user_id);
        }
        return $response;
    }

    public function visibleTo(\App\User $user)
    {

        if ($user->hasRole('admin') ) {
            return true;
        }
        if (in_array($user->id, [$this->created_by]) || $this->is_private === 0) {
            return true;
        }

        $task = Task::join('projects', 'tasks.project_id', '=', 'projects.id')
            ->join('project_members', 'project_members.project_id', '=', 'projects.id')
            ->join('task_users', 'task_users.user_id', '=', 'tasks.id')
            ->where('project_members.user_id', $user->id)
            ->orWhere('task_users.user_id', $this->id)
            ->get();

        if(!$task->isEmpty()){
            return true;
        }
        return false;

    }

    public function scopeVisibility($query)
    {
        if(api_user()) {

            $user = api_user();
			
            $query->join('task_users', 'task_users.task_id', '=', 'tasks.id')
                  ->where('task_users.user_id', $user->id);
          	/*
            if ($user->hasRole('admin')) {
                return $query;
            }

            else{
                // If employee or client show projects assigned
                $query->join('task_users', 'task_users.task_id', '=', 'tasks.id')
                      ->where('task_users.user_id', $user->id);
                //$query->where('is_private', 0);

                // $query->join('projects', 'tasks.project_id', '=', 'projects.id')
                //       ->join('project_members', 'project_members.project_id', '=', 'projects.id')
                //       ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
                //       ->where('project_members.user_id', $user->id);
                // $query->orWhere('task_users.user_id', $user->id);
                // $query->orWhere('created_by', $user->id);
                // $query->orWhere('is_private', 0);

                return $query;
            }
            */
        }
        return $query;
    }
  
   	public function history()
    {
        return $this->hasMany(TaskHistory::class, 'task_id')->orderBy('id', 'desc');
    }

    public function files()
    {
        return $this->hasMany(TaskFile::class, 'task_id');
    }
  
    public function getTimeLogActiveAttribute()
    {
      	
        $user = api_user();
      	$activeTimer = ProjectTimeLog::with('user')
            ->whereNull('end_time')
            ->join('users', 'users.id', '=', 'project_time_logs.user_id')
            ->where('user_id', $user->id)
            ->where('task_id', $this->id)
          	->first();
      return $activeTimer;
    }
	
  
    public function getTimeLogAttribute()
    {
      	
      	$activeTimer = ProjectTimeLog::with('user','checker')
            ->where('task_id', $this->id)
          	->get();
      return $activeTimer;
    }
    public function getAtasan1Attribute()
    {
        $task = Task::find($this->id);
        // get user
        $user = User::join('employee_details as ed','ed.user_id','users.id')
            ->where('users.id',$task->assignee_user_id)
            ->select('ed.permission_require')
            ->first();
        $atasan=null;
        $permission_require = \json_decode($user->permission_require);
        if (isset($permission_require[0]) && !empty($permission_require[0])){ 
            $atasan = DB::table('users')->find($permission_require[0]);
        }
      	return $atasan;
    }
    public function getAtasan2Attribute()
    {
        $task = Task::find($this->id);
        // get user
        $user = User::join('employee_details as ed','ed.user_id','users.id')
            ->where('users.id',$task->assignee_user_id)
            ->select('ed.permission_require')
            ->first();
        $atasan=null;
        $permission_require = \json_decode($user->permission_require);
        if (isset($permission_require[1]) && !empty($permission_require[1])){ 
            $atasan = DB::table('users')->find($permission_require[1]);
        }
      	return $atasan;
    }
	
   

}
