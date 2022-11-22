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

class AttendanceExport implements FromCollection, WithHeadings, WithStrictNullComparison
{

    private $userId;
    private $startDate;
    private $endDate;
    private $department;
    private $tipeCuti;
    private $tipeIjin;
    private $office_id;
    private $libur;

    public function __construct($userId, $startDate, $endDate, $department, $tipeCuti,$subcompany,$wilayah, $getTypeIjin,$office_id, $libur)
    {
        $this->userId = $userId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->department = $department;
        $this->tipeCuti = $tipeCuti;
        $this->tipeIjin = $getTypeIjin;
        $this->subcompany = $subcompany;
        $this->wilayah = $wilayah;
        $this->office_id = $office_id;
        $this->libur = $libur;
    }

    public function headings(): array
    {
        $arr=[];
        
        foreach ($this->tipeCuti as $val) {
            array_push($arr,$val->name);
        }
        //get anak perusahaan
        $sub_company =SubCompany::find($this->subcompany)->name;
        //get wilayah
        $wilayah =Wilayah::find($this->wilayah)->name;
        //get department
        $department = Team::find($this->department)->team_name;
        //get periode
        $start = $this->startDate;
        $end = $this->endDate;
        // hari libur 
        $hari_libur = $this->libur==1?'Ya':'Tidak';

        // extra cuti display
        $leave_taken = 0;
        $leaveTaken = Leave::leaveTaken(1);
        if ($leaveTaken['status']==200) {
            $leave_taken = $leaveTaken['data'];
        }

        $fields = ['No','Name','Hadir','WFO','WFH','WFH Dengan Dinas Sementara','WFH Dengan Dinas Sementara Weekend','WFO Weekend', 'GPS','Tidak Absen Masuk','Terlambat','Lembur','Pulang Tidak Absen','Ijin Tidak Masuk','Ijin Terlambat','Ijin Pulang Awal','Ijin Pulang Awal By System','Ijin Keluar Kantor', 'Sakit','Cuti','Alpha'];

        foreach($leave_taken as $key => $item){
            $fields[] = $key." Diambil";
            $fields[] = $key." Tersisa";
        }

        $fields[] = 'ket';

        // return array_merge(['No','Name','Hadir','WFO','WFH','GPS','Terlambat','Lembur','Pulang Tidak Absen','Ijin Tidak Masuk','Ijin Terlambat','Ijin Pulang Awal','Ijin Pulang Awal By System','Ijin Keluar Kantor', 'Sakit','Cuti','Alpha','ket']);
        return array_merge([
            ["Anak Perusahaan: $sub_company"],
            ["Wilayah: $wilayah"],
            ["Department: $department"],
            ["Periode: $start sampai $end"],
            ["Hitung Hari Libur: $hari_libur"],
            $fields]);
            // ['No','Name','Hadir','WFO','WFH','GPS','Terlambat','Lembur','Pulang Tidak Absen','Ijin Tidak Masuk','Ijin Terlambat','Ijin Pulang Awal','Ijin Pulang Awal By System','Ijin Keluar Kantor', 'Sakit','Cuti','Alpha','ket']]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $start_date = $this->startDate;
        $end_date = $this->endDate;
        $id = $this->userId;
        $department = $this->department;
        $subcompany = $this->subcompany;
        $wilayah = $this->wilayah;
        $office_id = $this->office_id;
        $libur = $this->libur;

        $data = Attendance::getLaporanKehadiran($start_date, $end_date, $department, $id, $subcompany,$wilayah,$office_id, $libur);

        $idx=1;
        $arr_output=[];
        array_insert($this->tipeIjin,3,'pulang-awal-system');
        foreach ($data as $val) {
            
            // extra cuti display
            $leave_taken = 0;
            $fields = [];
            $leaveTaken = Leave::leaveTaken($val->id);
            if ($leaveTaken['status']==200) {
                $leave_taken = $leaveTaken['data'];
            }

            foreach($leave_taken as $key => $item){
                $fields[] = $item['leave_taken'];
                $fields[] = $item['leave_remaining'];
            }

            $arr_merge=[];
            foreach ($this->tipeIjin as $tipeIjin) {
                if (isset($val->type_ijin[$tipeIjin]) && !empty($val->type_ijin)) {
                    array_push($arr_merge,$val->type_ijin[$tipeIjin]);
                }else{
                    array_push($arr_merge,0);
                }
            }
            // dd($this->tipeIjin,$arr_merge);
            // dd($arr_merge);
            // foreach ($this->tipeCuti as $tipeCuti) {
            //     if (isset($val->type_cuti[$tipeCuti->name]) && !empty($val->type_cuti)) {
            //         array_push($arr_merge,$val->type_cuti[$tipeCuti->name]);
            //     }else{
            //         array_push($arr_merge,0);
            //     }
            // }
            array_push($arr_output, array_merge([
                $idx,
                $val->base_name,
                $val->hadir,
                $val->wfo,
                $val->wfh,
                $val->wfh_with_dinas,
                $val->wfh_with_dinas_weekend,
                $val->wfo_weekend,
                $val->gps,
                $val->tidak_absen_masuk,
                $val->terlambat,
                $val->lembur.' menit',
                $val->pulang_tidak_absen,
            ],$arr_merge,[
                $val->cuti,
                $val->alpha
            ], $fields));
            $idx++;
        }
        // dd($arr_output);
        return new Collection([
            $arr_output
        ]);
    }
}
