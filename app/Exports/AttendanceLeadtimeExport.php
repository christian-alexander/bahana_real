<?php

namespace App\Exports;

use App\Attendance;
use App\Designation;
use App\Leave;
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

class AttendanceLeadtimeExport implements FromCollection, WithHeadings, WithStrictNullComparison
{

    private $tanggal_mulai_pembuatan_ijin;
    private $tanggal_berakhir_pembuatan_ijin;
    private $pembuatIjin;
    private $subcompany;
    private $wilayah;
    private $department;
    private $status;
    private $office_id;

    public function __construct($tanggal_mulai_pembuatan_ijin,$tanggal_berakhir_pembuatan_ijin, $pembuatIjin, $subcompany, $wilayah, $department, $status,$office_id)
    {
        $this->tanggal_mulai_pembuatan_ijin = $tanggal_mulai_pembuatan_ijin;
        $this->tanggal_berakhir_pembuatan_ijin = $tanggal_berakhir_pembuatan_ijin;
        $this->pembuatIjin = $pembuatIjin;
        $this->subcompany = $subcompany;
        $this->wilayah = $wilayah;
        $this->department = $department;
        $this->status = $status;
        $this->office_id = $office_id;
    }

    public function headings(): array
    {
        //get anak perusahaan
        $sub_company =SubCompany::find($this->subcompany)->name;
        //get wilayah
        $wilayah ='All';
        if ($this->wilayah != 0) {
            $wilayah =Wilayah::find($this->wilayah)->name;
        }
        //get department
        $department = 'All';
        if ($this->department != 0) {
            $department = Team::find($this->department)->team_name;
        }
        //get periode
        $start_date = $this->tanggal_mulai_pembuatan_ijin;
        $end_date = $this->tanggal_berakhir_pembuatan_ijin;

        $fields = [
            'Tanggal Pembuatan Ijin',
            'Nama PT',
            'Departemen',
            'Pembuat Ijin',
            'Jenis Ijin',
            'Deskripsi Ijin',
            'Tanggal Awal Ijin',
            'Tanggal Akhir Ijin',
            'Status',
            'Approval 1 Nama',
            'Approval 1 Tanggal',
            'Approval 1 Waktu/Jam',
            'Lead Time (day)',
            'Approval 2 Nama',
            'Approval 2 Tanggal',
            'Approval 2 Waktu/Jam',
            'Lead Time (day)',
            'Approval HRD Nama',
            'Approval HRD Tanggal',
            'Approval HRD Waktu/Jam',
            'Lead Time (day)',
            'Total Lead Time (day)'
        ];

        return array_merge([
            ["Anak Perusahaan: $sub_company"],
            ["Wilayah: $wilayah"],
            ["Department: $department"],
            ["Periode: $start_date sd $end_date"],
            $fields]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = Leave::getLaporanLeadtime(
            $this->tanggal_mulai_pembuatan_ijin,
            $this->tanggal_berakhir_pembuatan_ijin,
            $this->pembuatIjin,
            $this->subcompany,
            $this->wilayah,
            $this->department,
            $this->status,
            null,
            null,
            null,
            $this->office_id
        );
        $data= $data['data'];
        $arr_output=[];
        foreach ($data as $val) {
            $arr_output[]=[
                "tanggal_pembuatan_ijin" => $val['tanggal_pembuatan_ijin'],
                "nama_pt" => $val['nama_pt'],
                "departemen" => $val['departemen'],
                "pembuat_ijin" => $val['pembuat_ijin'],
                "jenis_ijin" => $val['jenis_ijin'],
                "deskripsi_ijin" => $val['deskripsi_ijin'],
                "tgl_awal_ijin" => $val['tgl_awal_ijin'],
                "tgl_akhir_ijin" => $val['tgl_akhir_ijin'],
                "status" => $val['status'],
                "Approval 1 Nama"=>$val['approval_1']['nama'],
                "Approval 1 Tanggal"=>$val['approval_1']['tanggal'],
                "Approval 1 Waktu/Jam"=>$val['approval_1']['jam'],
                "Approval 1 Lead Time (day)"=>$val['approval_1']['leadtime'],
                "Approval 2 Nama"=>$val['approval_2']['nama'],
                "Approval 2 Tanggal"=>$val['approval_2']['tanggal'],
                "Approval 2 Waktu/Jam"=>$val['approval_2']['jam'],
                "Approval 2 Lead Time (day)"=>$val['approval_2']['leadtime'],
                "Approval HRD Nama"=>$val['hrd']['nama'],
                "Approval HRD Tanggal"=>$val['hrd']['tanggal'],
                "Approval HRD Waktu/Jam"=>$val['hrd']['jam'],
                "Approval HRD Lead Time (day)"=>$val['hrd']['leadtime'],
                "total" => 0,
            ];
        }
        return new Collection([
            $arr_output
        ]);
    }
}
