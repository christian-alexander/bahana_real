<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TaskLeadtimeExport;
use App\Helper\Reply;
use App\Project;
use App\SubCompany;
use App\Task;
use App\TaskboardColumn;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class TaskReportController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.taskReport';
        $this->pageIcon = 'ti-pie-chart';
        $this->middleware(function ($request, $next) {
            if (!in_array('reports', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $this->projects = Project::all();
        $this->fromDate = Carbon::today()->subDays(30);
        $this->toDate = Carbon::today();
        $this->employees = User::allEmployees();

        $taskBoardColumn = TaskboardColumn::all();

        $incompletedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
            return $value->slug == 'incomplete';
        })->first();

        $completedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
            return $value->slug == 'completed';
        })->first();

        $this->clients = User::allClients();
        $this->taskBoardStatus = TaskboardColumn::all();

        $taskStatus = array();
        foreach ($this->taskBoardStatus as $key => $value) {
            $totalTasks = Task::leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
                ->where(DB::raw('DATE(`due_date`)'), '>=', $this->fromDate)
                ->where(DB::raw('DATE(`due_date`)'), '<=', $this->toDate);

            $totalTasks = $totalTasks->where('tasks.board_column_id', $value->id);
            $taskStatus[$value->slug] = [
                'count' => $totalTasks->count(),
                'label' => $value->column_name,
                'color' => $value->label_color
            ];
        }
        $this->taskStatus = json_encode($taskStatus);

        $this->totalTasks = Task::where(DB::raw('DATE(`due_date`)'), '>=', $this->fromDate->format('Y-m-d'))
            ->where(DB::raw('DATE(`due_date`)'), '<=', $this->toDate->format('Y-m-d'))
            ->count();

        $this->completedTasks = Task::where(DB::raw('DATE(`due_date`)'), '>=', $this->fromDate->format('Y-m-d'))
            ->where(DB::raw('DATE(`due_date`)'), '<=', $this->toDate->format('Y-m-d'))
            ->where('tasks.board_column_id', $completedTaskColumn->id)
            ->count();

        $this->pendingTasks = Task::where(DB::raw('DATE(`due_date`)'), '>=', $this->fromDate->format('Y-m-d'))
            ->where(DB::raw('DATE(`due_date`)'), '<=', $this->toDate->format('Y-m-d'))
            ->where('tasks.board_column_id', $incompletedTaskColumn->id)
            ->count();

        return view('admin.reports.tasks.index', $this->data);
    }

    public function store(Request $request)
    {

        // $request->startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
        // $request->endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();

        $taskBoardColumn = TaskboardColumn::all();
        $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
        $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();

        $incompletedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
            return $value->slug == 'incomplete';
        })->first();

        $completedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
            return $value->slug == 'completed';
        })->first();

        $taskStatus = array();

        foreach ($taskBoardColumn as $key => $value) {
            $totalTasks = Task::leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
                ->where(DB::raw('DATE(`due_date`)'), '>=', $startDate)
                ->where(DB::raw('DATE(`due_date`)'), '<=', $endDate);

            if (!is_null($request->projectId)) {
                $totalTasks->where('project_id', $request->projectId);
            }

            if (!is_null($request->employeeId)) {
                $totalTasks->where('user_id', $request->employeeId);
            }



            $totalTasks = $totalTasks->where('tasks.board_column_id', $value->id);
            $taskStatus[$value->slug] = [
                'count' => $totalTasks->count(),
                'label' => $value->column_name,
                'color' => $value->label_color
            ];
        }

        $totalTasks = Task::where(DB::raw('DATE(`due_date`)'), '>=', $startDate)
            ->where(DB::raw('DATE(`due_date`)'), '<=', $endDate);

        if (!is_null($request->projectId)) {
            $totalTasks->where('project_id', $request->projectId);
        }

        if (!is_null($request->employeeId)) {
            $totalTasks->where('user_id', $request->employeeId);
        }

        $totalTasks = $totalTasks->count();

        $completedTasks = Task::where(DB::raw('DATE(`due_date`)'), '>=', $startDate)
            ->where(DB::raw('DATE(`due_date`)'), '<=', $endDate);

        if (!is_null($request->projectId)) {
            $completedTasks->where('project_id', $request->projectId);
        }

        if (!is_null($request->employeeId)) {
            $completedTasks->where('user_id', $request->employeeId);
        }
        $taskBoardColumn = TaskboardColumn::where('slug', 'completed')->first();
        $completedTasks = $completedTasks->where('tasks.board_column_id', $taskBoardColumn->id)->count();

        $pendingTasks = Task::where(DB::raw('DATE(`due_date`)'), '>=', $startDate)
            ->where(DB::raw('DATE(`due_date`)'), '<=', $endDate);

        if (!is_null($request->projectId)) {
            $pendingTasks->where('project_id', $request->projectId);
        }

        if (!is_null($request->employeeId)) {
            $pendingTasks->where('user_id', $request->employeeId);
        }
        $taskBoardColumn = TaskboardColumn::where('slug', 'incomplete')->first();
        $pendingTasks = $pendingTasks->where('tasks.board_column_id', '<>', $completedTaskColumn->id)->count();

        return Reply::successWithData(
            __('messages.reportGenerated'),
            ['pendingTasks' => $pendingTasks, 'completedTasks' => $completedTasks, 'totalTasks' => $totalTasks, 'taskStatus' => $taskStatus]
        );
    }

    public function data(Request $request)
    {
        $startDate  = $request->startDate;
        $endDate    = $request->endDate;
        $employeeId = $request->employeeId;
        $projectId  = $request->projectId;

        $startDate = Carbon::createFromFormat($this->global->date_format, $startDate)->toDateString();
        $endDate = Carbon::createFromFormat($this->global->date_format, $endDate)->toDateString();

        $tasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
            ->select('tasks.id', 'projects.project_name', 'tasks.heading', 'tasks.due_date', 'tasks.project_id', 'taskboard_columns.column_name', 'taskboard_columns.label_color');

        if (!is_null($startDate)) {
            $tasks->where(DB::raw('DATE(tasks.`due_date`)'), '>=', $startDate);
        }

        if (!is_null($endDate)) {
            $tasks->where(DB::raw('DATE(tasks.`due_date`)'), '<=', $endDate);
        }

        if ($projectId != 0) {
            $tasks->where('tasks.project_id', '=', $projectId);
        }

        if ($employeeId != 0) {
            $tasks->where('task_users.user_id', $employeeId);
        }
        
        $tasks->with('users')->get();

        return DataTables::of($tasks)
            ->editColumn('due_date', function ($row) {
                if ($row->due_date->isPast()) {
                    return '<span class="text-danger">' . $row->due_date->format($this->global->date_format) . '</span>';
                }
                return '<span class="text-success">' . $row->due_date->format($this->global->date_format) . '</span>';
            })
            ->editColumn('name', function ($row) {
                $members = '';
                foreach ($row->users as $member) {
                    $members .= '<a href="' . route('admin.employees.show', [$member->id]) . '">';
                    $members .= '<img data-toggle="tooltip" data-original-title="' . ucwords($member->name) . '" src="' . $member->image_url . '"
                    alt="user" class="img-circle" width="25" height="25"> ';
                    $members .= '</a>';
                }

                return $members;
            })
            ->editColumn('heading', function ($row) {
                return '<a href="javascript:;" data-task-id="' . $row->id . '" class="show-task-detail">' . ucfirst($row->heading) . '</a>';
            })

            ->editColumn('column_name', function ($row) {
                return '<label class="label" style="background-color: ' . $row->label_color . '">' . $row->column_name . '</label>';
            })

            ->editColumn('project_name', function ($row) {
                if (is_null($row->project_id)) {
                    return "";
                }
                return '<a href="' . route('admin.projects.show', $row->project_id) . '">' . ucfirst($row->project_name) . '</a>';
            })
            ->rawColumns(['column_name', 'project_name', 'due_date', 'name', 'heading'])
            ->removeColumn('project_id')
            ->removeColumn('image')
            ->removeColumn('label_color')
            ->make(true);
    }
    public function leadtimePengecekanTugas(){
        $this->employees = User::allEmployees();
        $this->subcompanies = SubCompany::where('company_id', $this->user->company_id)->get();
        $this->teams = Team::all();

        $this->init_date = Carbon::now()->format('d-m-Y');
      
        return view('admin.reports.tasks.leadtime-tugas', $this->data);
    }
    public function leadtimePengecekanTugasData(Request $request){
        try {
            $getData = Task::getLaporanLeadtimePengecekanTugas(
                $request->tanggal_mulai_pembuatan_tugas,
                $request->tanggal_berakhir_pembuatan_tugas,
                $request->subcompany,
                $request->department
            );
            if ($getData['status']==500) {
                throw new \Exception($getData['message']);
            }
            $this->leaves = $getData['data'];
            // return $this->leaves;
            // $this->type_cuti = TipeCuti::where('company_id', \Auth::user()->company_id)
            // ->get();
            // // get all type ijin
            // $this->type_ijin = [
            //     "tidak-masuk",
            //     "datang-terlambat",
            //     "pulang-awal",
            //     "pulang-awal-system",
            //     "keluar-kantor",
            //     "sakit",
            // ];
    
            $view = view('admin.reports.tasks.leadtime-tugas-data', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'data' => $view]);
        } catch (\Throwable $th) {
            return Reply::dataOnly(['status' => 'error', 'data' => $th->getMessage()]);
        }
    }
    public function leadtimePengecekanTugasExport($sub_company,$team,$tanggal_mulai_pembuatan_tugas,$tanggal_berakhir_pembuatan_tugas){
        try {
            return Excel::download(new TaskLeadtimeExport($tanggal_mulai_pembuatan_tugas,$tanggal_berakhir_pembuatan_tugas,$sub_company,$team), "leadtime-tugas-$tanggal_mulai_pembuatan_tugas sd $tanggal_berakhir_pembuatan_tugas.xlsx");
        } catch (\Throwable $th) {
            return Reply::dataOnly(['status' => 'error', 'data' => $th->getMessage()]);
        }
    }
}
