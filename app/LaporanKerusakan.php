<?php

namespace App;

// use App\Observers\SPKObserver;

use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// use App\Scopes\CompanyScope;

class LaporanKerusakan extends Model
{
    use Upload;
    
    protected $table = 'laporan_kerusakan';

    protected $casts = [
        'tanggal' => 'datetime:Y-m-d',
    ];

    public function details()
    {
        return $this->hasMany(LaporanKerusakanDetail::class, 'laporan_kerusakan_id', 'id');
    }

    public static function store($data, $user_id){
        DB::beginTransaction();
        try {
            // insert into LaporanKerusakan
            $model = new LaporanKerusakan;
            $model->nomor = $data['no'];
            $model->nama_kapal = $data['nama_kapal'];
            $model->tanggal = $data['tanggal'];
            $model->bagian_kapal = $data['bagian_kapal'];
            $model->note = $data['note'];
            $model->pelaksana = $user_id;
            $model->created_by = $user_id;
            $model->created_by = $user_id;
            $model->save();

            if (isset($data['posisi_di_kapal']) && !empty($data['posisi_di_kapal'])) {
                // save to detail
                for ($i=0; $i < count($data['posisi_di_kapal']); $i++) { 
                    $detail = new LaporanKerusakanDetail;
                    $detail->laporan_kerusakan_id = $model->id;
                    $detail->posisi_di_kapal = $data['posisi_di_kapal'][$i];
                    $detail->jumlah_kerusakan = $data['jumlah_kerusakan'][$i];
                    $detail->uraian_kerusakan = $data['uraian_kerusakan'][$i];
                    $detail->analisis_kerusakan = $data['analisis_kerusakan'][$i];
                    $detail->usaha_penanggulangan = $data['usaha_penanggulangan'][$i];
                    $detail->hal_yang_perlu_ditindak_lanjuti  = $data['hal_yang_perlu_ditindak_lanjuti'][$i];
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
    public static function updateModel($data, $user_id, $laporan_kerusakan_id){
        DB::beginTransaction();
        try {
            // get data
            $model = LaporanKerusakan::find($laporan_kerusakan_id);
            if (!isset($model) && empty($model)) {
                throw new \Exception("Data not found");
            }
            $model->nomor = $data['no'];
            $model->nama_kapal = $data['nama_kapal'];
            $model->tanggal = $data['tanggal'];
            $model->bagian_kapal = $data['bagian_kapal'];
            $model->note = $data['note'];
            $model->pelaksana = $user_id;
            $model->created_by = $user_id;
            $model->created_by = $user_id;
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

            if (isset($data['posisi_di_kapal']) && !empty($data['posisi_di_kapal'])) {
                // save to detail
                for ($i=0; $i < count($data['posisi_di_kapal']); $i++) {
                    $detail = new LaporanKerusakanDetail;
                    $detail->laporan_kerusakan_id = $model->id;
                    $detail->posisi_di_kapal = $data['posisi_di_kapal'][$i];
                    $detail->jumlah_kerusakan = $data['jumlah_kerusakan'][$i];
                    $detail->uraian_kerusakan = $data['uraian_kerusakan'][$i];
                    $detail->analisis_kerusakan = $data['analisis_kerusakan'][$i];
                    $detail->usaha_penanggulangan = $data['usaha_penanggulangan'][$i];
                    $detail->hal_yang_perlu_ditindak_lanjuti  = $data['hal_yang_perlu_ditindak_lanjuti'][$i];
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
    public static function approve($data, $user_id, $getLaporanKerusakan){
        DB::beginTransaction();
        try {
            if($data['type_approve']=='pelaksana'){
                $getLaporanKerusakan->pelaksana = $user_id;
                $getLaporanKerusakan->is_pelaksana = 1;
                $getLaporanKerusakan->approved_pelaksana_at = Carbon::now();
                $getLaporanKerusakan->signature_applicant = self::store_signature($data['tanda_tangan'], $user_id);
                $getLaporanKerusakan->status = 'onprogress';
                $getLaporanKerusakan->status_approval = 'approved_pelaksana';
                $getLaporanKerusakan->save();
            }elseif ($data['type_approve']=='diperiksa') {
                $getLaporanKerusakan->diperiksa = $user_id;
                $getLaporanKerusakan->is_diperiksa = 1;
                $getLaporanKerusakan->approved_diperiksa_at = Carbon::now();
                $getLaporanKerusakan->signature_approval_diperiksa = self::store_signature($data['tanda_tangan'], $user_id);
                $getLaporanKerusakan->status = 'done';
                $getLaporanKerusakan->status_approval = 'approved_diperiksa';
                $getLaporanKerusakan->save();
            }elseif($data['type_approve']=='mengetahui_1'){
                $getLaporanKerusakan->mengetahui_1 = $user_id;
                $getLaporanKerusakan->is_mengetahui_1 = 1;
                $getLaporanKerusakan->approved_mengetahui_1_at = Carbon::now();
                $getLaporanKerusakan->signature_approval_mengetahui_1 = self::store_signature($data['tanda_tangan'], $user_id);
                $getLaporanKerusakan->save();
            }elseif($data['type_approve']=='mengetahui_2'){
                $getLaporanKerusakan->mengetahui_2 = $user_id;
                $getLaporanKerusakan->is_mengetahui_2 = 1;
                $getLaporanKerusakan->approved_mengetahui_2_at = Carbon::now();
                $getLaporanKerusakan->signature_approval_mengetahui_2 = self::store_signature($data['tanda_tangan'], $user_id);
                $getLaporanKerusakan->save();
            }else{
                throw new \Exception("Cant do this action");
            }
            DB::commit();
            return model_response(true,'Data berhasil disetujui', $getLaporanKerusakan);
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
