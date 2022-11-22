<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Role;
use App\SPK;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class SPKDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        // dd($query);
        return datatables($query)
            ->addColumn('action', function ($row) {

                $action = '<div class="btn-group dropdown m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light" type="button"><i class="ti-more"></i></button>
                    <ul role="menu" class="dropdown-menu pull-right">
                  <li><a href="' . route('admin.spk.show', [$row->id]) . '"><i class="fa fa-search" aria-hidden="true"></i> ' . __('app.view') . '</a></li>';
                $action .= '</ul> </div>';

                return $action;
            })
            ->editColumn(
                'user_id',
                function ($row) {
                    return User::find($row->user_id)->name;
                }
            )
            ->editColumn(
                'tanggal',
                function ($row) {
                    return Carbon::parse($row->tanggal)->format($this->global->date_format);
                }
            )
            ->editColumn(
                'mt_or_spob',
                function ($row) {
                    return strtoupper($row->mt_or_spob);
                }
            )
            ->editColumn(
                'pp_id',
                function ($row) {
                    if (isset($row->pp_id) && !empty($row->pp_id)) {
                        return $row->pp_id;
                    }
                    return '-';
                }
            )
            ->editColumn(
                'status_approval',
                function ($row) {
                    if (isset($row->status_approval) && !empty($row->status_approval)) {
                        return $row->status_approval;
                    }
                    return '-';
                }
            )
            ->editColumn(
                'status',
                function ($row) {
                    if ($row->status == 'pending') {
                        return '<label class="label label-warning">' . strtoupper($row->status) . '</label>';
                    } elseif($row->status == 'onprogress') {
                        return '<label class="label label-success">' . strtoupper($row->status) . '</label>';
                    }else{
                        return '<label class="label label-primary">' . strtoupper($row->status) . '</label>';
                    }
                }
            )
            // ->editColumn('name', function ($row) use ($roles) {

            //     $image = '<img src="' . $row->image_url . '"alt="user" class="img-circle" width="30" height="30"> ';

            //     $designation = ($row->designation_name) ? ucwords($row->designation_name) : ' ';

            //     return  '<div class="row"><div class="col-sm-3 col-xs-4">' . $image . '</div><div class="col-sm-9 col-xs-8"><a href="' . route('admin.employees.show', $row->id) . '">' . ucwords($row->name) . '</a><br><span class="text-muted font-12">' . $designation . '</span></div></div>';
            // })
            ->addIndexColumn()
            ->rawColumns(['action', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $request = $this->request();
        $spk = SPK::select('*');
        if ($request->status != 'all' && $request->status != '') {
            $spk = $spk->where('status', $request->status);
        }
        if ($request->status_approval != 'all' && $request->status_approval != '') {
            $spk = $spk->where('status_approval', $request->status_approval);
        }
        // if (isset($request->start_date) && !empty($request->start_date)) {
        //     $spk = $spk->whereDate('created_at','>=',$request->start_date);
        // }
        // if (isset($request->end_date) && !empty($request->end_date)) {
        //     $spk = $spk->whereDate('created_at','<=',$request->end_date);
        // }

        if ($request->user_id != 'all' && $request->user_id != '') {
            $spk = $spk->where('user_id', $request->user_id);
        }

        return $spk;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('employees-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-6'l><'col-md-6'Bf>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->destroy(true)
            ->orderBy(0)
            ->responsive(true)
            ->serverSide(true)
            ->stateSave(true)
            ->processing(true)
            ->language(__("app.datatable"))
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["employees-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
            ])
            ->buttons(
                Button::make(['extend' => 'export', 'buttons' => ['excel', 'csv']])
            );
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
            'User' => ['data' => 'user_id', 'name' => 'user_id'],
            'No pp' => ['data' => 'pp_id', 'name' => 'pp_id'],
            'Type' => ['data' => 'mt_or_spob', 'name' => 'mt_or_spob'],
            'No' => ['data' => 'no', 'name' => 'no'],
            'Status' => ['data' => 'status', 'name' => 'status'],
            'Status Approval' => ['data' => 'status_approval', 'name' => 'status_approval'],
            'Tanggal' => ['data' => 'tanggal', 'name' => 'tanggal'],
            'Keperluan' => ['data' => 'keperluan', 'name' => 'keperluan'],
            // __('app.name') => ['data' => 'name', 'name' => 'name'],
            // __('app.email') => ['data' => 'email', 'name' => 'email'],
            // // __('app.role') => ['data' => 'role', 'name' => 'role', 'width' => '20%'],
            // __('app.status') => ['data' => 'status', 'name' => 'status'],
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
        return 'employees_' . date('YmdHis');
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
