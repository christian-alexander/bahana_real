<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\FormCreated;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helper\Files;

class formsurattugas extends Model
{
    use Upload;
    
    protected $table = 'form_surat_tugas';

    protected $casts = [
        'tanggal' => 'datetime:Y-m-d',
    ];


    public static function store($data, $user_id){
        DB::beginTransaction();
        try {
            $flagErrorMail = false;
            // insert 
            $model = new formsurattugas;
            // generate no format = NO/INT(internal)/BHLN(Anak perusahaan sisanya PT yg lain dan singkatannya)/departemen sesuai PT/wilayah/bulan/tahun
            $counter = formsurattugas::count()+1;
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
            $no = "$counter/ST/$data_anak_perusahaan3->code-$department3->code/$wilayah3->code/$bulan/$tahun";

            $model->no = $no;
            $model->subcompany_id = $data['subcompany'];
            $model->user_id = $data['user_id'];
            $model->team_id = $data['department'];
            $model->jabatan_satu = $data['jabatan_satu'];
            $model->nik_satu = $data['nik_satu'];
            $model->jabatan_dua = $data['jabatan_dua'];
            $model->nik_dua = $data['nik_dua'];
            $model->nama_bertugas = $data['nama_bertugas'];
            $model->rute_awal = $data['rute_awal'];
            $model->rute_akhir = $data['rute_akhir'];
            $model->tanggal_mulai = $data['tanggal_mulai'];
            $model->tanggal_selesai = $data['tanggal_selesai'];
            $model->estimasi_biaya = $data['estimasi_biaya'];
            $model->keperluan = $data['keperluan'];
            $model->acc_atasan_1 = $data['acc_atasan_1'];
            $model->acc_atasan_2 = $data['acc_atasan_2'];

            // $model->note = $data['note'];
            // if (isset($data['file']) && !empty($data['file'])) {
            //     $filename = Files::uploadLocalOrS3($data['file'], "surat-tugas/$user_id");
            //     $model->image = "user-uploads/surat-tugas/$user_id/$filename";
            // }
            
            $model->pembuat = $user_id;
            $model->is_pembuat = 1;
            $model->approved_pembuat_at = Carbon::now();
            $model->signature_approval_pembuat = self::store_signature($data['tanda_tangan'], $user_id);
            $model->status = 'onprogress';
            $model->status_approval = 'approved_pembuat';

            $model->created_by = $user_id;
            $model->created_by = $user_id;
            // if (isset($data['cc']) && !empty($data['cc'])) {
            //     $model->cc_user_id = json_encode($data['cc']);
            // }
            $model->save();


            
            // if (isset($data['cc']) && !empty($data['cc'])) {
            //     foreach ($data['cc'] as $val) {
            //         // get user
            //         $user = User::find($val);
            //         if (isset($user) && !empty($user)) {
            //             $userLogin = User::find($user_id);
            //             $msg = "$userLogin->name membuat form Permintaan Pendanaan";
            //             try {
            //                 $user->notify(new FormCreated($msg,'FORM-PERMINTAAN-DANA', $model));
            //             } catch (\Throwable $th) {
            //                 $flagErrorMail = true;
            //             }
            //             // $user->notify(new FormCreated($msg,'FORM-PERMINTAAN-DANA', $model));
            //         }
            //     }
            // }

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
            if($data['type_approve']=='penerima'){
                $model->penerima = $user_id;
                $model->is_penerima = 1;
                $model->approved_penerima_at = Carbon::now();
                $model->signature_approval_penerima = self::store_signature($data['tanda_tangan'], $user_id);
                $model->status = 'done';
                $model->status_approval = 'approved_penerima';
                $model->save();
            }elseif($data['type_approve']=='mengetahui_1'){
                $model->mengetahui_1 = $user_id;
                $model->is_mengetahui_1 = 1;
                $model->approved_mengetahui_1_at = Carbon::now();
                $model->signature_approval_mengetahui_1 = self::store_signature($data['tanda_tangan'], $user_id);
                $model->status_approval = 'approved_atasan_1';
                $model->save();
            }elseif($data['type_approve']=='mengetahui_2'){
                $model->mengetahui_2 = $user_id;
                $model->is_mengetahui_2 = 1;
                $model->approved_mengetahui_2_at = Carbon::now();
                $model->signature_approval_mengetahui_2 = self::store_signature($data['tanda_tangan'], $user_id);
                $model->status_approval = 'approved_atasan_2';
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
            if($data['type_approve']=='penerima'){
                $getLaporanKerusakan->penerima = $user_id;
                $getLaporanKerusakan->rejected_penerima_at = Carbon::now();
                $getLaporanKerusakan->rejected_penerima_reason = $data['alasan_reject'];
                // 0 = pending || 1 = approved || 2 = rejected
                $getLaporanKerusakan->is_penerima = 2;
                $getLaporanKerusakan->status = 'done';
                $getLaporanKerusakan->status_approval = 'rejected_penerima';
                $getLaporanKerusakan->save();
            }elseif($data['type_approve']=='mengetahui_1'){
                $getLaporanKerusakan->mengetahui_1 = $user_id;
                $getLaporanKerusakan->rejected_mengetahui_1_at = Carbon::now();
                $getLaporanKerusakan->rejected_mengetahui_1_reason = $data['alasan_reject'];
                // 0 = pending || 1 = approved || 2 = rejected
                $getLaporanKerusakan->is_mengetahui_1 = 2;
                $getLaporanKerusakan->status = 'done';
                $getLaporanKerusakan->save();
            }elseif($data['type_approve']=='mengetahui_2'){
                $getLaporanKerusakan->mengetahui_2 = $user_id;
                $getLaporanKerusakan->rejected_mengetahui_2_at = Carbon::now();
                $getLaporanKerusakan->rejected_mengetahui_2_reason = $data['alasan_reject'];
                // 0 = pending || 1 = approved || 2 = rejected
                $getLaporanKerusakan->is_mengetahui_2 = 2;
                $getLaporanKerusakan->status = 'done';
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
