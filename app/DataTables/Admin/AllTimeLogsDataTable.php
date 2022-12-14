<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\LogTimeFor;
use App\ProjectTimeLog;
use App\ProjectTimeLogFile;
use App\TaskFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class AllTimeLogsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    protected $timeLogFor;
    protected $isTask;
    public function __construct()
    {
        parent::__construct();
        $this->timeLogFor = LogTimeFor::first();
        $this->isTask = false;

        if ($this->timeLogFor != null && $this->timeLogFor->log_time_for == 'task') {
            $this->isTask = true;
        }
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                if ($row->status == 'in_review') {
                    return '<a href="javascript:;" class="btn btn-info btn-circle accept-time-log"
                          data-toggle="tooltip" data-time-id="' . $row->id . '"  data-original-title="Terima"><i class="fa fa-check" aria-hidden="true"></i></a>
                            <a href="javascript:;" class="btn btn-danger btn-circle reject-time-log"
                            data-toggle="tooltip" data-time-id="' . $row->id . '" data-original-title="Tolak"><i class="fa fa-times" aria-hidden="true"></i></a>';
                } else {
                    return '<a href="javascript:;" class="btn btn-info btn-circle edit-time-log"
                          data-toggle="tooltip" data-time-id="' . $row->id . '"  data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                            data-toggle="tooltip" data-time-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
            })
            ->editColumn('image', function ($row) {
                if ($row->image && $row->image != "") {
                    return '<a href="' . url('user-uploads/screenshot/' . $row->image) . '" target="_blank" ><img src="' . url('user-uploads/screenshot/' . $row->image) . '" style="width: 50px;"" /></a>';
                }
            })
            ->editColumn('name', function ($row) {
                return '<a href="' . route('admin.employees.show', $row->user_id) . '" target="_blank" >' . ucwords($row->name) . '</a>';
            })
            ->editColumn('start_time', function ($row) {
                return $row->start_time->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format);
            })
            ->editColumn('end_time', function ($row) {
                if (!is_null($row->end_time)) {
                    return $row->end_time->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format);
                } else {
                    return "<label class='label label-success'>" . __('app.active') . "</label>";
                }
            })
            ->editColumn('total_hours', function ($row) {
                $timeLog = intdiv($row->total_minutes, 60) . ' hrs ';

                if (($row->total_minutes % 60) > 0) {
                    $timeLog .= ($row->total_minutes % 60) . ' mins';
                }

                return $timeLog;
            })
            ->addColumn('earnings', function ($row) {
                if (is_null($row->hourly_rate)) {
                    return '--';
                }
                $hours = intdiv($row->total_minutes, 60);

                $earning = round($hours * $row->hourly_rate);

                return $this->global->currency->currency_symbol . $earning . ' (' . $this->global->currency->currency_code . ')';
            })
            ->editColumn('project_name', function ($row) {

                if ($this->logTimeFor != null && $this->logTimeFor->log_time_for == 'task') {
                    return ucfirst($row->project_name);
                } else {
                    return '<a href="' . route('admin.projects.show', $row->project_id) . '">' . ucfirst($row->project_name) . '</a>';
                }
            })
            ->editColumn('status', function ($row) {

                if ($row->status == 'rejected') {
                    return "$row->status ($row->reason)";
                } else {
                    return $row->status;
                }
            })
            ->addColumn('dokumen_pendukung', function($row){
                // get doc
                $arr= [];
                $getFile = TaskFile::where('task_id',$row->task_id)->get();
                foreach ($getFile as $val) {
                    $url = asset_url_local_s3('task-files/'.$row->task_id.'/'.$val->hashname);
                    array_push($arr,$url);
                }
                $str = implode(", ",$arr);
                return $str;

            })
            ->addColumn('gps', function($row){
                if (!empty($row->latitude) && !empty($row->longitude)) {
                    return $row->latitude.','.$row->longitude;
                }else{
                    return '';
                }
            })
            ->editColumn('image', function($row){
                // get doc
                $arr= [];
                $getFile = ProjectTimeLogFile::where('project_time_log_id',$row->id)->get();
                foreach ($getFile as $val) {
                    $url = asset_url_local_s3('project-time-log-files/'.$row->id.'/'.$val->hashname);
                    array_push($arr,$url);
                }
                $str = implode(", ",$arr);
                return $str;
                // if (!empty($row->image)) {
                //     return asset_url_local_s3('project-time-log-files/'.$row->id.'/'.$row->image);
                // }else{
                //     return '';
                // }
            })
            ->rawColumns(['end_time', 'action', 'project_name', 'name', 'image'])
            ->removeColumn('project_id')
            ->removeColumn('total_minutes')
            ->removeColumn('task_id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ProjectTimeLog $model)
    {
        $request = $this->request();
        $projectName = 'projects.project_name';
        $model = $model->join('users', 'users.id', '=', 'project_time_logs.user_id')
        ->join('employee_details', 'users.id', '=', 'employee_details.user_id')
        ->leftJoin('users as cn', 'cn.id', '=', 'project_time_logs.checker_user_id');

        $this->logTimeFor = LogTimeFor::first();

        if ($this->logTimeFor != null && $this->logTimeFor->log_time_for == 'task') {
            $model = $model->join('tasks', 'tasks.id', '=', 'project_time_logs.task_id');
            $projectName = 'tasks.heading as project_name';
        } else {
            $model = $model->join('projects', 'projects.id', '=', 'project_time_logs.project_id');
        }
        $model = $model->select(
            'project_time_logs.id',
            'project_time_logs.image',
            $projectName,
            'project_time_logs.start_time',
            'project_time_logs.end_time',
            'project_time_logs.total_hours',
            'project_time_logs.total_minutes',
            'project_time_logs.memo',
            'project_time_logs.user_id',
            'project_time_logs.project_id',
            'project_time_logs.task_id',
            'project_time_logs.latitude',
            'project_time_logs.longitude',
            'project_time_logs.image',
            'users.name',
            'employee_details.hourly_rate',
            'project_time_logs.status',
            'cn.name as checker_name',
            'project_time_logs.reason',
            'tasks.description'
        );

        if (!is_null($request->startDate)) {
            $model->where(DB::raw('DATE(project_time_logs.`start_time`)'), '>=', Carbon::createFromFormat($this->global->date_format, $request->startDate));
        }

        if (!is_null($request->endDate)) {
            $model->where(DB::raw('DATE(project_time_logs.`end_time`)'), '<=', Carbon::createFromFormat($this->global->date_format, $request->endDate));
        }

        if (!is_null($request->employee) && $request->employee !== 'all') {
            $model->where('project_time_logs.user_id', $request->employee);
        }

        if (!is_null($request->projectId) && $request->projectId !== 'all') {
            if ($this->logTimeFor != null && $this->logTimeFor->log_time_for == 'task') {
                $model->where('project_time_logs.task_id', '=', $request->projectId);
            } else {
                $model->where('project_time_logs.project_id', '=', $request->projectId);
            }
        }
        return $model;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('all-time-logs-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-6'l><'col-md-6'Bf>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->orderBy(0)
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            ->stateSave(true)
            ->processing(true)
            ->language(__("app.datatable"))
            ->buttons(
                Button::make(['extend' => 'export', 'buttons' => ['excel', 'csv']])
            )
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["all-time-logs-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
                'scrollX'=> 'true',
                'responsive'=> 'true',
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            __('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false],
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
            // __('Screenshot')  => ['data' => 'image', 'name' => 'image'],
            $this->isTask ? __('app.task') : __('app.project') => ['data' => 'project_name', 'name' => $this->isTask ? 'tasks.heading' : 'projects.project_name'],
            'Deskripsi Tugas'  => ['data' => 'description', 'name' => 'tasks.description'],
            'Dokumen Pendukung'  => ['data' => 'dokumen_pendukung', 'name' => 'dokumen_pendukung','searchable' => false],
            __('app.menu.employees')  => ['data' => 'name', 'name' => 'users.name'],
            __('modules.timeLogs.startTime') => ['data' => 'start_time', 'name' => 'start_time'],
            __('modules.timeLogs.endTime') => ['data' => 'end_time', 'name' => 'end_time'],
            __('modules.timeLogs.totalHours') => ['data' => 'total_hours', 'name' => 'total_hours'],
            'Deskripsi berhenti bekerja' => ['data' => 'memo', 'name' => 'memo'],
            'GPS' => ['data' => 'gps', 'name' => 'gps','searchable' => false],
            'Kamera' => ['data' => 'image', 'name' => 'image','searchable' => false],
            'status' => ['data' => 'status', 'name' => 'status'],
            'Checker' => ['data' => 'checker_name', 'name' => 'checker_name','searchable'=> false],
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(150)
                ->addClass('text-center')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'All_time_log_' . date('YmdHis');
    }

    public function pdf()
    {
        set_time_limit(0);
        if ('snappy' == config('datatables-buttons.pdf_generator', 'snappy')) {
            return $this->snappyPdf();
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('datatables::print', ['data' => $this->getDataForPrint()]);

        return $pdf->download($this->getFilename() . '.pdf');
    }
}
