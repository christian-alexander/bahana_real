<?php

namespace App;

// use App\Observers\SPKObserver;

use App\Notifications\FormCreated;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// use App\Scopes\CompanyScope;

class PermintaanDana extends Model
{
    use Upload;
    
    protected $table = 'permintaan_dana';

    protected $casts = [
        'tanggal' => 'datetime:Y-m-d',
    ];


    public static function store($data, $user_id){
        DB::beginTransaction();
        try {
            $flagErrorMail = false;
            // insert 
            $model = new PermintaanDana;
            $model->subcompany_id = $data['subcompany'];
            $model->user_id = $data['user_id'];
            $model->tanggal = $data['tanggal'];
            $model->keperluan = $data['keperluan'];
            $model->nominal = $data['nominal'];
            $model->note = $data['note'];
            $model->pembuat = $user_id;

            $model->is_pembuat = 1;
            $model->approved_pembuat_at = Carbon::now();
            $model->signature_approval_pembuat = self::store_signature($data['tanda_tangan'], $user_id);
            $model->status = 'onprogress';
            $model->status_approval = 'approved_pembuat';

            $model->created_by = $user_id;
            $model->created_by = $user_id;
            if (isset($data['cc']) && !empty($data['cc'])) {
                $model->cc_user_id = json_encode($data['cc']);
            }
            $model->save();


            
            if (isset($data['cc']) && !empty($data['cc'])) {
                foreach ($data['cc'] as $val) {
                    // get user
                    $user = User::find($val);
                    if (isset($user) && !empty($user)) {
                        $userLogin = User::find($user_id);
                        $msg = "$userLogin->name membuat form Permintaan Pendanaan";
                        try {
                            $user->notify(new FormCreated($msg,'FORM-PERMINTAAN-DANA', $model));
                        } catch (\Throwable $th) {
                            $flagErrorMail = true;
                        }
                        // $user->notify(new FormCreated($msg,'FORM-PERMINTAAN-DANA', $model));
                    }
                }
            }

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
                $model->save();
            }elseif($data['type_approve']=='mengetahui_1'){
                $model->mengetahui_1 = $user_id;
                $model->is_mengetahui_1 = 1;
                $model->approved_mengetahui_1_at = Carbon::now();
                $model->signature_approval_mengetahui_1 = self::store_signature($data['tanda_tangan'], $user_id);
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
