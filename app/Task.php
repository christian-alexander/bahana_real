<?php

namespace App;

use App\Observers\TaskObserver;
use App\Scopes\CompanyScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Task extends BaseModel
{
    protected $fillable = ['board_column_id'];
    use Notifiable;

    protected static function boot()
    {
        
        parent::boot();

        static::observe(TaskObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function routeNotificationForMail()
    {
        return $this->user->email;
    }

    protected $dates = ['due_date', 'completed_on', 'start_date'];
    protected $appends = ['due_on', 'create_on'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function board_column()
    {
        return $this->belongsTo(TaskboardColumn::class, 'board_column_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_users');
    }

    public function create_by()
    {
        return $this->belongsTo(User::class, 'created_by')->withoutGlobalScopes(['active']);
    }

    public function category()
    {
        return $this->belongsTo(TaskCategory::class, 'task_category_id');
    }

    public function subtasks()
    {
        return $this->hasMany(SubTask::class, 'task_id');
    }

    public function history()
    {
        return $this->hasMany(TaskHistory::class, 'task_id')->orderBy('id', 'desc');
    }

    public function completedSubtasks()
    {
        return $this->hasMany(SubTask::class, 'task_id')->where('sub_tasks.status', 'complete');
    }

    public function incompleteSubtasks()
    {
        return $this->hasMany(SubTask::class, 'task_id')->where('sub_tasks.status', 'incomplete');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class, 'task_id')->orderBy('id', 'desc');
    }

    public function files()
    {
        return $this->hasMany(TaskFile::class, 'task_id');
    }


    /**
     * @return string
     */
    public function getDueOnAttribute()
    {
        if (!is_null($this->due_date)) {
            return $this->due_date->format('d M, y');
        }
        return "";
    }
    public function getCreateOnAttribute()
    {
        if (!is_null($this->start_date)) {
            return $this->start_date->format('d M, y');
        }
        return "";
    }

    /**
     * @param $projectId
     * @param null $userID
     */
    public static function projectOpenTasks($projectId, $userID = null)
    {
        $taskBoardColumn = TaskboardColumn::where('slug', '<>','completed')->first();
        $projectTask = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')->where('tasks.board_column_id', $taskBoardColumn->id)->select('tasks.*');

        if ($userID) {
            $projectIssue = $projectTask->where('task_users.user_id', '=', $userID);
        }

        $projectIssue = $projectTask->where('project_id', $projectId)
            ->get();

        return $projectIssue;
    }

    public static function projectCompletedTasks($projectId)
    {
        $taskBoardColumn = TaskboardColumn::where('slug', 'completed')->first();
        return Task::where('tasks.board_column_id', $taskBoardColumn->id)
            ->where('project_id', $projectId)
            ->get();
    }
    public static function checkUserIsMyAtasanOrangKepercayaan($atasan_id,$user_login_id){
        // fungsi ini untuk mengetahui apakah atasan_id adalah orang kepercayaan dari user_login_id
        $atasan = EmployeeDetails::where('user_id',$atasan_id)->first();
        if (empty($atasan)) {
            return false;
        }

        if ($atasan_id==$user_login_id) {
            return true;
        }

        $orang_kepercayaan_atasan = json_decode($atasan->user_orang_kepercayaan);
        if (empty($orang_kepercayaan_atasan)) {
            return false;
        }

        if (!in_array($user_login_id,$orang_kepercayaan_atasan)) {
            return false;
        }
        return true;

    }
    public static function isOrangKepercayaan($atasan_id,$assignee_sub_company_id, $user_login_id){
        // fungsi ini untuk mengetahui apakah aku (user yang login) adalah orang kepercayaan dari
        // atasan_id dan memiliki permission by sub company dengan membandingkan assignee_sub_company_id dengan
        // atasan sub_company_orang_kepercayaan

        // get data atasan
        $atasan = EmployeeDetails::where('user_id',$atasan_id)->first();
        
        if ($atasan) {
            // check atasan mengaktifkan delegasi tugas
            $is_on_orang_kepercayaan = json_decode($atasan->is_on_orang_kepercayaan, true);
            if (!isset($is_on_orang_kepercayaan) && empty($is_on_orang_kepercayaan)){ 
                return false;
            }
            // if (isset($is_on_orang_kepercayaan[$user_login_id])){ 
                if (@$is_on_orang_kepercayaan[$user_login_id]==0) {
                    return false;
                }
            // }
            $orang_kepercayaan_atasan = json_decode($atasan->user_orang_kepercayaan);
            if (empty($orang_kepercayaan_atasan)) {
                return false;
            }
            if (!in_array($user_login_id,$orang_kepercayaan_atasan)) {
                return false;
            }
            
            $sub_company_orang_kepercayaan_atasan = json_decode($atasan->sub_company_orang_kepercayaan, true);
            $sub_company_orang_kepercayaan_atasan = $sub_company_orang_kepercayaan_atasan[$user_login_id];
            if (empty($sub_company_orang_kepercayaan_atasan)) {
                return false;
            }
            if (!in_array($assignee_sub_company_id,$sub_company_orang_kepercayaan_atasan)) {
                return false;
            }
            return true;
        }
        return false;
    }
    public static function orangKepercayaanCanViewTugas($tugas_id){
        // get tugas
        $tugas = Task::find($tugas_id);
        if ($tugas) {
            // get creator task
            $creator_task = EmployeeDetails::where('user_id',$tugas->created_by)->first();
            if ($creator_task) {
                if ($creator_task->is_on_orang_kepercayaan==0) {
                    return null;
                }
                // get user_orang_kepercayaan
                $user_orang_kepercayaan = json_decode($creator_task->user_orang_kepercayaan);
                array_push($user_orang_kepercayaan,$tugas->created_by);

                $assignee_user = EmployeeDetails::where('user_id',$tugas->assignee_user_id)->first();
                if (empty($assignee_user)) {
                    return null;
                }
                
                $tugas_creator_sub_company_orang_kepercayaan = json_decode($creator_task->sub_company_orang_kepercayaan);
                // check sub company assignee_user dengan sub_company_orang_kepercayaan
                if (in_array($assignee_user->sub_company_id,$tugas_creator_sub_company_orang_kepercayaan)) {
                    return $user_orang_kepercayaan;
                }
            }
        }
        return null;
    }
    public static function getLaporanLeadtimePengecekanTugas($tanggal_mulai_pembuatan_tugas,$tanggal_berakhir_pembuatan_tugas,$subcompany,$department){
        try {
            $task_start_created = Carbon::parse($tanggal_mulai_pembuatan_tugas);
            $task_end_created = Carbon::parse($tanggal_berakhir_pembuatan_tugas);
            $tugas = Task::join('employee_details as ed_creator','ed_creator.user_id','tasks.assignee_user_id')
                ->join('users as u_creator','u_creator.id','ed_creator.user_id')
                ->join('employee_details as ed_penerima','ed_penerima.user_id','tasks.assignee_user_id')
                ->join('users as u_penerima','u_penerima.id','ed_penerima.user_id')
                ->join('teams as t_penerima','t_penerima.id','ed_penerima.department_id')
                ->join('projects as p','p.id','tasks.project_id')
                ->leftjoin('project_time_logs as ptl','ptl.task_id','tasks.id')
                ->leftjoin('users as u_approval','u_approval.id','ptl.checker_user_id')
                ->join('taskboard_columns as tc','tc.id','tasks.board_column_id')
                ->where('ed_penerima.sub_company_id',$subcompany)
                ->where('ed_penerima.department_id',$department)
                ->whereBetween('tasks.created_at',[$task_start_created->copy()->format('Y-m-d'),$task_end_created->copy()->format('Y-m-d')])
                ->select(
                    'tasks.*',
                    'u_creator.name as creator_name',
                    'u_penerima.name as penerima_name',
                    'u_penerima.name as penerima_name',
                    't_penerima.team_name as penerima_department_name',
                    'p.project_name',
                    'ptl.start_time',
                    'ptl.end_time',
                    'tc.column_name as task_status',
                    'u_approval.name as approval_name',
                    'ptl.status as approval_status',
                    'ptl.updated_at as approval_updated_at',
                    )
                ->get();
            $response=[];  
            foreach ($tugas as $item) {
                // get gmt by lates attendance
                $timezone = 7;
                if (isset($item->assignee_user_id) && !empty($item->assignee_user_id)){ 
                    $attendance = Attendance::where('user_id',$item->assignee_user_id)->select('clock_in_timezone')->orderBy('clock_in_time','desc')->first();
                    if ($attendance) {
                        $timezone = $attendance->clock_in_timezone;
                    }
                }
                // leadtime_pengerjaan_tugas
                // gap dari tugas dimulai hingga tugas selesai
                $leadtime_pengerjaan_tugas=0;
                $tugas_selesai = null;
                if (!empty($item->end_time)) {
                    $tugas_dimulai = Carbon::parse($item->start_time);
                    $tugas_selesai = Carbon::parse($item->end_time);
                    $leadtime_pengerjaan_tugas = $tugas_selesai->diff($tugas_dimulai)->format('%a hari %h jam %i menit');
                }
                $tanggal_approve ='-';
                $leadtime_approval_tugas =0;
                $leadtime_setelah_blokir_absen =0;
                if ($item->approval_status=='accepted') {
                    $tanggal_approve_temp = Carbon::parse($item->approval_updated_at);
                    if (!empty($tugas_selesai)) {
                        $leadtime_approval_tugas = $tugas_selesai->copy()->diff($tanggal_approve_temp)->format('%a hari %h jam %i menit');
                    }
                    $tanggal_approve = $tanggal_approve_temp->copy();

                    // rumus leadtime_setelah_blokir_absen adalah diff dari tanggal approve dengan tanggal tugas selesai dikurangin 1 hari/24 jam
                    $leadtime_setelah_blokir_absen = $tanggal_approve_temp->copy()->diff($tugas_selesai)->format('%a hari %h jam %i menit');
                    if ($leadtime_setelah_blokir_absen>24) {
                        $leadtime_setelah_blokir_absen = $leadtime_setelah_blokir_absen - 24; 
                    }else{
                        $leadtime_setelah_blokir_absen=0;
                    }
                }
                $tanggal_tugas_dikerjakan = Carbon::parse($item->start_time);
                $tanggal_pembuatan_tugas = Carbon::parse($item->created_at);
                $tanggal_selesai_tugas = Carbon::parse($item->end_time);
                if ($timezone>0) {
                    $tanggal_tugas_dikerjakan = $tanggal_tugas_dikerjakan->addHours($timezone)->format('d-m-Y H:i');
                    $tanggal_selesai_tugas = $tanggal_selesai_tugas->addHours($timezone)->format('d-m-Y H:i');
                    $tanggal_pembuatan_tugas = $tanggal_pembuatan_tugas->addHours($timezone)->format('d-m-Y H:i');
                    if ($tanggal_approve != '-') {
                        $tanggal_approve = $tanggal_approve->addHours($timezone)->format('d-m-Y H:i');
                    }
                }else{
                    $tanggal_approve = $tanggal_tugas_dikerjakan->subHours(abs($timezone))->format('d-m-Y H:i');
                    $tanggal_selesai_tugas = $tanggal_selesai_tugas->subHours(abs($timezone))->format('d-m-Y H:i');
                    $tanggal_pembuatan_tugas = $tanggal_pembuatan_tugas->subHours(abs($timezone))->format('d-m-Y H:i');
                    if ($tanggal_approve != '-') {
                        $tanggal_approve = $tanggal_approve->subHours(abs($timezone))->format('d-m-Y H:i');
                    }
                }

                if (empty($item->end_time)) {
                    $leadtime_pengerjaan_tugas = '-';
                    $leadtime_approval_tugas = '-';
                    $leadtime_setelah_blokir_absen = '-';
                    $tanggal_selesai_tugas = '-';
                }
                
                $response[]=[
                    "tanggal_pembuatan_tugas"=>$tanggal_pembuatan_tugas,
                    "pembuat_tugas"=>$item->creator_name,
                    "penerima_tugas"=>$item->penerima_name,
                    "judul_tugas"=>$item->heading,
                    "deskripsi_tugas"=>$item->description,
                    "catatan_tambahan"=>"-",
                    "department_penerima_tugas"=>$item->penerima_department_name,
                    "proyek"=>$item->project_name,
                    "batas_waktu"=>$item->due_date->format('d-m-Y').' 23:59',
                    "tanggal_tugas_dikerjakan"=>$tanggal_tugas_dikerjakan,
                    "tanggal_selesai_tugas"=>$tanggal_selesai_tugas,
                    "status_tugas"=>$item->task_status,
                    "tanggal_approve"=>$tanggal_approve,
                    "nama_approval"=>$item->approval_name,
                    "status_approval"=>$item->approval_status,
                    "leadtime_pengerjaan_tugas"=>$leadtime_pengerjaan_tugas,
                    "leadtime_approval_tugas"=>$leadtime_approval_tugas,
                    "leadtime_setelah_blokir_absen"=>$leadtime_setelah_blokir_absen,
                ];
            }
            return [
                'status' => 200,
                'message' => 'Data found',
                'data' => $response
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 500,
                'message' => $e->getMessage(),
            ];
        }
    }
}
