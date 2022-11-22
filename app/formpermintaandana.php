<?php

namespace App;

// use App\Observers\SPKObserver;

use App\Notifications\FormCreated;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helper\Files;

// use App\Scopes\CompanyScope;

class formpermintaandana extends Model
{
    use Upload;
    
    protected $table = 'form_permintaan_dana';

    protected $casts = [
        'tanggal' => 'datetime:Y-m-d',
    ];


    public static function store($data, $user_id){
        DB::beginTransaction();
        try {
            $flagErrorMail = false;
            // insert 
            $model = new formpermintaandana;
            // generate no format = NO/INT(internal)/BHLN(Anak perusahaan sisanya PT yg lain dan singkatannya)/departemen sesuai PT/wilayah/bulan/tahun
            $counter = formpermintaandana::count()+1;
            $data_anak_perusahaan = EmployeeDetails::select('sub_company_id')->where('user_id', $user_id)->first();
            $data_anak_perusahaan2 = json_decode($data_anak_perusahaan->sub_company_id,true);
            $data_anak_perusahaan3 = SubCompany::select('code')->where('id',$data_anak_perusahaan2)->first();
            
            $disetujui = EmployeeDetails::where('department_id',4)->where('is_atasan',1)->first();
            $disetujui = json_decode($disetujui->user_id,true);
            
            $wilayah = EmployeeDetails::select('wilayah_id')->where('user_id', $user_id)->first();
            $wilayah2 = json_decode($wilayah->wilayah_id,true);
            $wilayah3 = Wilayah::select('code')->where('id',$wilayah2)->first();

            $department = EmployeeDetails::select('department_id')->where('user_id', $user_id)->first();
            $department2 = json_decode($department->department_id,true);
            $department3 = Team::select('code')->where('id',$department2)->first();
            
            $bulan = Carbon::now()->format('m');
            $tahun = Carbon::now()->format('Y');
            $no = "$counter/PD/$data_anak_perusahaan3->code-$department3->code/$wilayah3->code/$bulan/$tahun";

            $model->no = $no;
            $model->subcompany_id = $data['subcompany'];
            $model->user_id = $data['user_id'];
            $model->tanggal = $data['tanggal'];
            $model->team_id = $data['department'];
            $model->keperluan = $data['keperluan'];
            $model->nominal = $data['nominal'];
            $model->terbilang = $data['terbilang'];
            $model->unsur_pph = $data['unsur_pph'];
            $model->nominal_pph = $data['nominal_pph'];
            $model->approval_pajak = $data['approval_pajak'];
            $model->diperiksa_1 = $data['diperiksa_satu'];
            $model->mengetahui = $data['mengetahui'];
            $model->disetujui_1= $data['disetujui_1'];
            $model->note = $data['note'];
            if (isset($data['file']) && !empty($data['file'])) {
                $filename = Files::uploadLocalOrS3($data['file'], "formpermintaan-dana/$user_id");
                $model->image = "user-uploads/formpermintaan-dana/$user_id/$filename";
            }
            
            $model->pembuat = $user_id;
            $model->is_pembuat = 1;
            $model->approved_pembuat_at = Carbon::now();
            $model->signature_approval_pembuat = self::store_signature($data['tanda_tangan'], $user_id);
            $model->status = 'onprogress';
            $model->status_approval = 'approved_pembuat';

            $model->created_by = $user_id;
            $model->created_by = $user_id;
            $model->save();

            DB::commit();
            if ($flagErrorMail) {
                return model_response(true,'Data berhasil disimpan, Email error silahkan hubungi developer');
            }else{
                return model_response(true,'Data berhasil disimpan');
            }
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
    
    public static function approve($data, $user_id, $model){
        DB::beginTransaction();
        try {
            if($data['type_approve']=='disetujui'){
                $model->disetujui = $user_id;
                $model->is_disetujui = 1;
                $model->approved_disetujui_at = Carbon::now();
                $model->signature_approval_disetujui = self::store_signature($data['tanda_tangan'], $user_id);
                $model->status = 'done';
                $model->status_approval = 'approved_disetujui';
                $model->save();
            }elseif($data['type_approve']=='diperiksa'){
                $model->diperiksa = $user_id;
                $model->is_diperiksa = 1;
                $model->approved_diperiksa_at = Carbon::now();
                $model->signature_approval_diperiksa = self::store_signature($data['tanda_tangan'], $user_id);
                $model->status_approval = 'approved_diperiksa';
                $model->save();
            }elseif($data['type_approve']=='mengetahui_1'){
                $model->mengetahui_1 = $user_id;
                $model->is_mengetahui_1 = 1;
                $model->approved_mengetahui_1_at = Carbon::now();
                $model->signature_approval_mengetahui_1 = self::store_signature($data['tanda_tangan'], $user_id);
                $model->status_approval = 'approved_mengetahui';
                $model->save();
            }else{
                throw new \Exception("Cant do this action");
            }
            DB::commit();
            return model_response(true,'Data berhasil disetujui', $model);
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
    public static function reject($data, $user_id, $getLaporanKerusakan){
        DB::beginTransaction();
        try {
            if($data['type_approve']=='disetujui'){
                $getLaporanKerusakan->disetujui = $user_id;
                $getLaporanKerusakan->rejected_disetujui_at = Carbon::now();
                $getLaporanKerusakan->rejected_disetujui_reason = $data['alasan_reject'];
                // 0 = pending || 1 = approved || 2 = rejected
                $getLaporanKerusakan->is_disetujui = 2;
                $getLaporanKerusakan->status = 'done';
                $getLaporanKerusakan->status_approval = 'rejected_disetujui';
                $getLaporanKerusakan->save();
            }elseif($data['type_approve']=='diperiksa'){
                $getLaporanKerusakan->diperiksa = $user_id;
                $getLaporanKerusakan->rejected_diperiksa_at = Carbon::now();
                $getLaporanKerusakan->rejected_diperiksa_reason = $data['alasan_reject'];
                // 0 = pending || 1 = approved || 2 = rejected
                $getLaporanKerusakan->is_diperiksa = 2;
                // $getLaporanKerusakan->status = 'done';
                $getLaporanKerusakan->save();
            }elseif($data['type_approve']=='mengetahui_1'){
                $getLaporanKerusakan->mengetahui_1 = $user_id;
                $getLaporanKerusakan->rejected_mengetahui_1_at = Carbon::now();
                $getLaporanKerusakan->rejected_mengetahui_1_reason = $data['alasan_reject'];
                // 0 = pending || 1 = approved || 2 = rejected
                $getLaporanKerusakan->is_mengetahui_1 = 2;
                // $getLaporanKerusakan->status = 'done';
                $getLaporanKerusakan->save();
            }else{
                throw new \Exception("Cant do this action");
            }
           
            DB::commit();
            return model_response(true,'Data berhasil ditolak', $getLaporanKerusakan);
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
}
