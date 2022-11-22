<?php

namespace App;

// use App\Observers\SPKObserver;

use App\Notifications\FormCreated;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// use App\Scopes\CompanyScope;

class InternalMemo extends Model
{
    use Upload;
    
    protected $table = 'internal_memo';

    protected $casts = [
        'tanggal' => 'datetime:Y-m-d',
    ];


    public static function store($data, $user_id){
        DB::beginTransaction();
        try {
            $flagErrorMail = false;
            // insert 
            $model = new InternalMemo;
            // generate no format = NO/INT(internal)/BHLN(Anak perusahaan sisanya PT yg lain dan singkatannya)/departemen sesuai PT/wilayah/bulan/tahun
            $counter = InternalMemo::count()+1;
            $data_anak_perusahaan = SubCompany::find($data['anak_perusahaan'])->name;
            $data_anak_perusahaan = explode(" ", $data_anak_perusahaan);
            $anak_perusahaan = "";

            foreach ($data_anak_perusahaan as $item) {
                if ($item[0]=='(') {
                    $anak_perusahaan .= strtoupper($item[1]);
                }else{
                    $anak_perusahaan .= strtoupper($item[0]);
                }
            }
            $department = $data['department'];
            $wilayah = $data['wilayah'];
            $bulan = Carbon::now()->format('m');
            $tahun = Carbon::now()->format('Y');
            $no = "$counter/INT/$anak_perusahaan/$department/$wilayah/$bulan/$tahun";
            $model->no = $no;
            $model->from_user_id = $data['dari'];
            $model->to_user_id = $data['kepada'];
            $model->subcompany_id = $data['anak_perusahaan'];
            $model->team_id = $data['department'];
            $model->wilayah_id = $data['wilayah'];
            $model->tanggal = $data['tanggal'];
            $model->tempat = $data['tempat'];
            $model->perihal = $data['perihal'];
            $model->sifat = $data['sifat'];
            $model->berita = $data['berita'];
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
                        $msg = "$userLogin->name membuat form Internal Memo";
                        try {
                            $user->notify(new FormCreated($msg,'FORM-INTERNAL-MEMO', $model));
                        } catch (\Throwable $th) {
                            $flagErrorMail = true;
                        }
                        // $user->notify(new FormCreated($msg,'FORM-INTERNAL-MEMO', $model));
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
            if($data['type_approve']=='penerima'){
                $getLaporanKerusakan->penerima = $user_id;
                $getLaporanKerusakan->is_penerima = 1;
                $getLaporanKerusakan->approved_penerima_at = Carbon::now();
                $getLaporanKerusakan->signature_approval_penerima = self::store_signature($data['tanda_tangan'], $user_id);
                $getLaporanKerusakan->status = 'done';
                $getLaporanKerusakan->status_approval = 'approved_penerima';
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
