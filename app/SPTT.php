<?php

namespace App;

// use App\Observers\SPKObserver;

use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\User;

// use App\Scopes\CompanyScope;

class SPTT extends Model
{
    use Upload;
    
    protected $table = 'sptt';

    protected $casts = [
        'tanggal' => 'datetime:Y-m-d',
    ];

    public function details()
    {
        return $this->hasMany(SPTTDetail::class, 'sptt_id', 'id');
    }

    public static function store($data, $user_id){
        DB::beginTransaction();
        try {
            // insert into spk
            $model = new SPTT;
            $model->nomor = $data['no'];
            $model->mt_spob = $data['type'];
            $model->posisi_kapal = $data['posisi_kapal'];
            $model->tanggal = $data['tanggal'];
            $model->note = $data['note'];
            $model->diserahkan_oleh = $data['diserahkan_oleh'];
            $model->penerima = $data['penerima'];
            $model->created_by = $user_id;
            $model->created_by = $user_id;
            // $model->signature_applicant = self::store_signature($data['tanda_tangan'], $user_id);
            $model->save();


            if (isset($data['uraian']) && !empty($data['uraian'])) {
                // save to detail
                for ($i=0; $i < count($data['uraian']); $i++) { 
                    $detail = new SPTTDetail;
                    $detail->sptt_id = $model->id;
                    $detail->uraian = $data['uraian'][$i];
                    $detail->satuan = $data['satuan'][$i];
                    $detail->jumlah = $data['jumlah'][$i];
                    $detail->save();
                }
            }
            DB::commit();
            return model_response(true,'Data berhasil disimpan');
        } catch (\Throwable $e) {
            dd($e->getMessage());
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
    public static function updateModel($data, $user_id, $sptt_id){
        DB::beginTransaction();
        try {
            // get data
            $model = SPTT::find($sptt_id);
            if (!isset($model) && empty($model)) {
                throw new \Exception("Data not found");
            }
            $model->nomor = $data['no'];
            $model->mt_spob = $data['type'];
            $model->posisi_kapal = $data['posisi_kapal'];
            $model->tanggal = $data['tanggal'];
            $model->note = $data['note'];
            $model->diserahkan_oleh = $data['diserahkan_oleh'];
            $model->penerima = $data['penerima'];
            $model->updated_by = $user_id;
            // if (isset($data['tanda_tangan']) && !empty($data['tanda_tangan'])) {
            //     if (isset($model->signature_applicant) && !empty($model->signature_applicant)) {
            //         // remove old signature_applicant
            //         if (file_exists(public_path().'/'. $model->signature_applicant)) {
            //             unlink(public_path().'/'. $model->signature_applicant);
            //         }
            //     }
            // }
            // $model->signature_applicant = self::store_signature($data['tanda_tangan'], $user_id);
            $model->save();

            // clear old data
            $model->details->each->delete();

            if (isset($data['uraian']) && !empty($data['uraian'])) {
                // save to detail
                for ($i=0; $i < count($data['uraian']); $i++) { 
                    $detail = new SPTTDetail;
                    $detail->sptt_id = $model->id;
                    $detail->uraian = $data['uraian'][$i];
                    $detail->satuan = $data['satuan'][$i];
                    $detail->jumlah = $data['jumlah'][$i];
                    $detail->save();
                }
            }
            DB::commit();
            return model_response(true,'Data berhasil disimpan');
        } catch (\Throwable $e) {
            dd($e->getMessage());
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
    public static function approve($data, $user_id, $sptt){
        DB::beginTransaction();
        try {
            if ($data['type_approve']=='diserahkan_oleh') {
                $sptt->approved_diserahkan_oleh_at = Carbon::now();
                $sptt->is_diserahkan_oleh = 1;
                $sptt->signature_diserahkan_oleh = self::store_signature($data['tanda_tangan'], $user_id);
                $sptt->status = 'onprogress';
                $sptt->status_approval = 'approved_diserahkan_oleh';
                $sptt->save();
            }else{
                $sptt->approved_penerima_at = Carbon::now();
                $sptt->is_penerima_oleh = 1;
                $sptt->signature_penerima = self::store_signature($data['tanda_tangan'], $user_id);
                $sptt->status = 'done';
                $sptt->status_approval = 'approved_penerima';
                $sptt->save();
            }
            DB::commit();
            return model_response(true,'Data berhasil disetujui', $sptt);
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
    public static function reject($data, $user_id, $sptt){
        DB::beginTransaction();
        try {
            if ($data['type_approve']=='diserahkan_oleh') {
                $sptt->approved_diserahkan_oleh_at = Carbon::now();
                $sptt->is_diserahkan_oleh = 2;
                $sptt->rejected_diserahkan_oleh_reason = $data['alasan_reject'];
                $sptt->status = 'done';
                $sptt->status_approval = 'rejected_diserahkan_oleh';
                $sptt->save();
            }else{
                $sptt->rejected_penerima_at = Carbon::now();
                $sptt->is_penerima_oleh = 2;
                $sptt->rejected_penerima_reason = $data['alasan_reject'];
                $sptt->status = 'done';
                $sptt->status_approval = 'rejected_penerima';
                $sptt->save();
            }
            DB::commit();
            return model_response(true,'Data berhasil disetujui', $sptt);
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
}
