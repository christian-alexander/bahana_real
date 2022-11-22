<?php

namespace App;

// use App\Observers\SPKObserver;

use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// use App\Scopes\CompanyScope;

class LaporanPenangguhanPekerjaan extends Model
{
    use Upload;
    
    protected $table = 'laporan_penangguhan_pekerjaan';

    protected $casts = [
        'tanggal' => 'datetime:Y-m-d',
    ];

    public function details()
    {
        return $this->hasMany(LaporanPenangguhanPekerjaanDetail::class, 'lpp_id', 'id');
    }
    public function laporanKerusakan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'laporan_kerusakan_id', 'id');
    }

    public static function store($data, $user_id,$laporan_penangguhan_pekerjaan_id){
        DB::beginTransaction();
        try {
            // insert into LaporanKerusakan
            $model = new LaporanPenangguhanPekerjaan;
            $model->laporan_kerusakan_id = $laporan_penangguhan_pekerjaan_id;
            $model->nomor = $data['no'];
            $model->tanggal = $data['tanggal'];
            $model->bagian_kapal = $data['bagian_kapal'];
            $model->note = $data['note'];
            $model->pelaksana = $data['pelaksana'];
            $model->created_by = $user_id;
            // $model->signature_applicant = self::store_signature($data['tanda_tangan'], $user_id);
            $model->save();

            if (isset($data['item_pekerjaan']) && !empty($data['item_pekerjaan'])) {
                // save to detail
                for ($i=0; $i < count($data['item_pekerjaan']); $i++) { 
                    $detail = new LaporanPenangguhanPekerjaanDetail;
                    $detail->lpp_id = $model->id;
                    $detail->item_pekerjaan = $data['item_pekerjaan'][$i];
                    $detail->posisi = $data['posisi'][$i];
                    $detail->alasan_penangguhan = $data['alasan_penangguhan'][$i];
                    $detail->target_perbaikan = $data['target_perbaikan'][$i];
                    $detail->keterangan = $data['keterangan'][$i];
                    $detail->save();
                }
            }
            DB::commit();
            return model_response(true,'Data berhasil disimpan');
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
    public static function updateModel($data, $user_id, $laporan_penangguhan_pekerjaan_id){
        DB::beginTransaction();
        try {
            // get data
            $model = LaporanPenangguhanPekerjaan::find($laporan_penangguhan_pekerjaan_id);
            if (!isset($model) && empty($model)) {
                throw new \Exception("Data not found");
            }
            $model->nomor = $data['no'];
            $model->tanggal = $data['tanggal'];
            $model->bagian_kapal = $data['bagian_kapal'];
            $model->note = $data['note'];
            $model->pelaksana = $data['pelaksana'];
            $model->updated_by = $user_id;
            $model->save();

            // clear old data
            $model->details->each->delete();

            if (isset($data['item_pekerjaan']) && !empty($data['item_pekerjaan'])) {
                // save to detail
                for ($i=0; $i < count($data['item_pekerjaan']); $i++) {
                    $detail = new LaporanPenangguhanPekerjaanDetail;
                    $detail->lpp_id = $model->id;
                    $detail->item_pekerjaan = $data['item_pekerjaan'][$i];
                    $detail->posisi = $data['posisi'][$i];
                    $detail->alasan_penangguhan = $data['alasan_penangguhan'][$i];
                    $detail->target_perbaikan = $data['target_perbaikan'][$i];
                    $detail->keterangan = $data['keterangan'][$i];
                    $detail->save();
                }
            }
            DB::commit();
            return model_response(true,'Data berhasil disimpan');
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
    public static function approve($data, $user_id, $getLPP){
        DB::beginTransaction();
        try {
            if($data['type_approve']=='pelaksana'){
                $getLPP->pelaksana = $user_id;
                $getLPP->is_pelaksana = 1;
                $getLPP->approved_pelaksana_at = Carbon::now();
                $getLPP->signature_approval_pelaksana = self::store_signature($data['tanda_tangan'], $user_id);
                $getLPP->status = 'onprogress';
                $getLPP->status_approval = 'approved_pelaksana';
                $getLPP->save();
            }elseif ($data['type_approve']=='diperiksa') {
                $getLPP->diperiksa = $user_id;
                $getLPP->is_diperiksa = 1;
                $getLPP->approved_diperiksa_at = Carbon::now();
                $getLPP->signature_approval_diperiksa = self::store_signature($data['tanda_tangan'], $user_id);
                $getLPP->status = 'done';
                $getLPP->status_approval = 'approved_diperiksa';
                $getLPP->save();
            }elseif($data['type_approve']=='mengetahui_1'){
                $getLPP->mengetahui_1 = $user_id;
                $getLPP->is_mengetahui_1 = 1;
                $getLPP->approved_mengetahui_1_at = Carbon::now();
                $getLPP->signature_approval_mengetahui_1 = self::store_signature($data['tanda_tangan'], $user_id);
                $getLPP->save();
            }elseif($data['type_approve']=='mengetahui_2'){
                $getLPP->mengetahui_2 = $user_id;
                $getLPP->is_mengetahui_2 = 1;
                $getLPP->approved_mengetahui_2_at = Carbon::now();
                $getLPP->signature_approval_mengetahui_2 = self::store_signature($data['tanda_tangan'], $user_id);
                $getLPP->save();
            }else{
                throw new \Exception("Cant do this action");
            }
            DB::commit();
            return model_response(true,'Data berhasil disetujui', $getLPP);
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
    public static function reject($data, $user_id, $getLaporanKerusakan){
        DB::beginTransaction();
        try {
            if($data['type_approve']=='pelaksana'){
                $getLaporanKerusakan->pelaksana = $user_id;
                $getLaporanKerusakan->rejected_pelaksana_at = Carbon::now();
                $getLaporanKerusakan->rejected_pelaksana_reason = $data['alasan_reject'];
                // 0 = pending || 1 = approved || 2 = rejected
                $getLaporanKerusakan->is_pelaksana = 2;
                $getLaporanKerusakan->status = 'done';
                $getLaporanKerusakan->status_approval = 'rejected_pelaksana';
                $getLaporanKerusakan->save();
            }elseif ($data['type_approve']=='diperiksa') {
                $getLaporanKerusakan->diperiksa = $user_id;
                $getLaporanKerusakan->rejected_diperiksa_at = Carbon::now();
                $getLaporanKerusakan->rejected_diperiksa_reason = $data['alasan_reject'];
                // 0 = pending || 1 = approved || 2 = rejected
                $getLaporanKerusakan->is_diperiksa = 2;
                $getLaporanKerusakan->status = 'done';
                $getLaporanKerusakan->status_approval = 'rejected_diperiksa';
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
