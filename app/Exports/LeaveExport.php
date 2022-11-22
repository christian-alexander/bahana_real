<?php

namespace App\Exports;

use App\Attendance;
use App\Company;
use App\Designation;
use App\Leave;
use App\LeaveCuti;
use App\SubCompany;
use App\Team;
use App\User;
use App\Wilayah;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Nwidart\Modules\Collection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class LeaveExport implements FromCollection, WithHeadings, WithStrictNullComparison
{

    private $start_date;
    private $end_date;
    private $employeeId;

    public function __construct($start_date,$end_date, $employeeId)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->employeeId = $employeeId;
    }

    public function headings(): array
    {
        //get periode
        $start_date = $this->start_date;
        $end_date = $this->end_date;

        $fields = [
            'Karyawan',
            'Tanggal Buat Ijin',
            'Ijin Tanggal',
            'Akhir Ijin Tanggal',
            'Ijin Dipakai',
            'Ijin Status',
            'Leave Type',
            'Status',
            'Alasan'
        ];

        return array_merge([
            ["Periode: $start_date sd $end_date"],
            $fields]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $startDate = $this->start_date;
        $endDate = $this->end_date;
        $startDt = '';
        $endDt = '';
        if (!is_null($startDate)) {
            $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d');
            $startDt = 'DATE(leaves.`leave_date`) >= ' . '"' . $startDate . '"';
        }

        if (!is_null($endDate)) {
            $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d');
            $endDt = 'DATE(leaves.`leave_date`) <= ' . '"' . $endDate . '"';
        }
        

        $leavesList = Leave::select(
            'leaves.id',
            'users.name',
            'leaves.leave_date',
            'leaves.leave_date_end',
            'leaves.status',
            'leave_types.type_name',
            'leave_types.color',
            'leaves.duration',
            'leaves.masking_status',
            'ed.permission_require',
            'leaves.created_at',
            'leaves.reason',
        )
            ->where('leaves.status', '<>', 'rejected');
            if (!empty($startDate)) {
                $leavesList =$leavesList->whereRaw($startDt);
            }
            if (!empty($endDt)) {
                $leavesList =$leavesList->whereRaw($endDt);
            }
            $leavesList =$leavesList->join('employee_details as ed', 'ed.user_id', 'leaves.user_id')
            ->join('users', 'users.id', '=', 'leaves.user_id')
            ->join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id');
        if ($this->employeeId != 0) {
            $leavesList->where('leaves.user_id', $this->employeeId);
        }

        $leaves = $leavesList->get();
        $arr_output=[];
        foreach ($leaves as $row) {
            $leave_type = '-';
            if ($row->type_name=='Cuti Custom') {
                $detail = LeaveCuti::join('tipe_cutis','tipe_cutis.id','leave_cutis.kategori_cuti')
                ->where('leave_cutis.leave_id',$row->id)
                ->select('tipe_cutis.name')
                ->first();
                if (isset($detail) && !empty($detail)) {
                    $leave_type = $detail->name;
                }
            }
            $leave_type =  $row->type_name;
            $arr_output[]=[
                "nama" => $row->name,
                "created_at" => $row->created_at->format('d-m-Y'),
                "leave_date" => $row->leave_date->format('d-m-Y'),
                "leave_date_end" => $row->leave_date_end->format('d-m-Y'),
                "ijin_dipakai" => (Carbon::parse($row->leave_date_end)->diffInDays($row->leave_date))+1,
                "ijin_status" => $row->status,
                "leave_type" => $leave_type,
                "status" => $row->masking_status,
                "alasan" => $row->reason
            ];
        }
        return new Collection([
            $arr_output
        ]);
    }
}
