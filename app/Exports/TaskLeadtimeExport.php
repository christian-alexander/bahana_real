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
use Modules\RestAPI\Entities\Task;

class TaskLeadtimeExport implements FromCollection, WithHeadings, WithStrictNullComparison
{

    private $tanggal_mulai_pembuatan_tugas;
    private $tanggal_berakhir_pembuatan_tugas;
    private $subcompany;
    private $department;

    public function __construct($tanggal_mulai_pembuatan_tugas,$tanggal_berakhir_pembuatan_tugas, $subcompany, $department)
    {
        $this->tanggal_mulai_pembuatan_tugas = $tanggal_mulai_pembuatan_tugas;
        $this->tanggal_berakhir_pembuatan_tugas = $tanggal_berakhir_pembuatan_tugas;
        $this->subcompany = $subcompany;
        $this->department = $department;
    }

    public function headings(): array
    {
        //get anak perusahaan
        $sub_company =SubCompany::find($this->subcompany)->name;
        //get department
        $department = Team::find($this->department)->team_name;
        //get periode
        $start_date = $this->tanggal_mulai_pembuatan_tugas;
        $end_date = $this->tanggal_berakhir_pembuatan_tugas;

        $fields = [
            "Tanggal Pembuatan Tugas",
            "Pembuat Tugas",
            "Penerima Tugas",
            "Judul Tugas",
            "Deskripsi Tugas",
            "Catatan Tambahan",
            "Department Penerima Tugas",
            "Proyek",
            "Batas Waktu",
            "Tanggal Selesai Tugas",
            "Status Tugas",
            "Tanggal Approve",
            "Nama Approval",
            "Status Approval",
            "Leadtime Pengerjaan Tugas",
            "Leadtime Approval Tugas",
            "Leadtime Setelah Blokir Absen (jam)",
        ];

        return array_merge([
            ["Anak Perusahaan: $sub_company"],
            ["Department: $department"],
            ["Periode: $start_date sd $end_date"],
            $fields]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = Task::getLaporanLeadtimePengecekanTugas(
            $this->tanggal_mulai_pembuatan_tugas,
            $this->tanggal_berakhir_pembuatan_tugas,
            $this->subcompany,
            $this->department
        );
        $data= $data['data'];
        return new Collection([
            $data
        ]);
    }
}
