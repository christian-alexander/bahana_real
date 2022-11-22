<?php

namespace App;

// use App\Observers\SPKObserver;

use App\Notifications\FormCreated;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// use App\Scopes\CompanyScope;

class SoundingPagiPerwira extends Model
{
    use Upload;
    
    protected $table = 'sounding_pagi_perwira';

    protected $casts = [
        'tanggal' => 'datetime:Y-m-d',
    ];


    public static function store($data, $user_id){
        DB::beginTransaction();
        try {
            $flagErrorMail = false;
            // dd($data);
            // insert 
            $model = new SoundingPagiPerwira;
            $model->office_id = $data['armada'];
            $model->bagian = $data['bagian'];

            // aturan no jurnal
            $now = Carbon::parse($data['tanggal']);
            $tahun = $now->copy()->format('Y');
            $bulan = $now->copy()->format('m');
            $kapal=$data['armada'];
            $bagian=$data['bagian'];

            $no_urut = SoundingPagiPerwira::whereYear('tanggal',$tahun)
                ->whereMonth('tanggal',$bulan)
                ->count()+1;
            $no_urut = sprintf('%03d', $no_urut);

            $no_jurnal = "$tahun/$bulan/JS/$kapal/$bagian/$no_urut";

            $model->no_jurnal = $no_jurnal;
            $model->tanggal = $data['tanggal'];
            $model->lokasi = $data['lokasi'];
            $total = 0;
            $table_json =[
                "s_1"=> [
                    "title" => "S 1",
                    "s_1_produk" => $data['s_1_produk'],
                    "s_1_awal_cm" => $data['s_1_awal_cm'],
                    "s_1_awal_m2" => $data['s_1_awal_m2'],
                    "s_1_1x" => $data['s_1_1x'],
                    "s_1_2x" => $data['s_1_2x'],
                    "s_1_3x" => $data['s_1_3x'],
                    "s_1_rata_rata" => $data['s_1_rata_rata'],
                    "s_1_m2" => $data['s_1_m2'],
                    "s_1_catatan" => $data['s_1_catatan'],
                ],
                "s_2" => [
                    "title" => "S 2",
                    "s_2_produk" => $data['s_2_produk'],
                    "s_2_awal_cm" => $data['s_2_awal_cm'],
                    "s_2_awal_m2" => $data['s_2_awal_m2'],
                    "s_2_1x" => $data['s_2_1x'],
                    "s_2_2x" => $data['s_2_2x'],
                    "s_2_3x" => $data['s_2_3x'],
                    "s_2_rata_rata" => $data['s_2_rata_rata'],
                    "s_2_m2" => $data['s_2_m2'],
                    "s_2_catatan" => $data['s_2_catatan'],
                ],
                "s_3" => [
                    "title" => "S 3",
                    "s_3_produk" => $data['s_3_produk'],
                    "s_3_awal_cm" => $data['s_3_awal_cm'],
                    "s_3_awal_m2" => $data['s_3_awal_m2'],
                    "s_3_1x" => $data['s_3_1x'],
                    "s_3_2x" => $data['s_3_2x'],
                    "s_3_3x" => $data['s_3_3x'],
                    "s_3_rata_rata" => $data['s_3_rata_rata'],
                    "s_3_m2" => $data['s_3_m2'],
                    "s_3_catatan" => $data['s_3_catatan'],
                ],
                "s_4" => [
                    "title" => "S 4",
                    "s_4_produk" => $data['s_4_produk'],
                    "s_4_awal_cm" => $data['s_4_awal_cm'],
                    "s_4_awal_m2" => $data['s_4_awal_m2'],
                    "s_4_1x" => $data['s_4_1x'],
                    "s_4_2x" => $data['s_4_2x'],
                    "s_4_3x" => $data['s_4_3x'],
                    "s_4_rata_rata" => $data['s_4_rata_rata'],
                    "s_4_m2" => $data['s_4_m2'],
                    "s_4_catatan" => $data['s_4_catatan'],
                ],
                "s_5" => [
                    "title" => "S 5",
                    "s_5_produk" => $data['s_5_produk'],
                    "s_5_awal_cm" => $data['s_5_awal_cm'],
                    "s_5_awal_m2" => $data['s_5_awal_m2'],
                    "s_5_1x" => $data['s_5_1x'],
                    "s_5_2x" => $data['s_5_2x'],
                    "s_5_3x" => $data['s_5_3x'],
                    "s_5_rata_rata" => $data['s_5_rata_rata'],
                    "s_5_m2" => $data['s_5_m2'],
                    "s_5_catatan" => $data['s_5_catatan'],
                ],
                "s_6" => [
                    "title" => "S 6",
                    "s_6_produk" => $data['s_6_produk'],
                    "s_6_awal_cm" => $data['s_6_awal_cm'],
                    "s_6_awal_m2" => $data['s_6_awal_m2'],
                    "s_6_1x" => $data['s_6_1x'],
                    "s_6_2x" => $data['s_6_2x'],
                    "s_6_3x" => $data['s_6_3x'],
                    "s_6_rata_rata" => $data['s_6_rata_rata'],
                    "s_6_m2" => $data['s_6_m2'],
                    "s_6_catatan" => $data['s_6_catatan'],
                ],
                "p_1"=> [
                    "title" => "P 1",
                    "p_1_produk" => $data['p_1_produk'],
                    "p_1_awal_cm" => $data['p_1_awal_cm'],
                    "p_1_awal_m2" => $data['p_1_awal_m2'],
                    "p_1_1x" => $data['p_1_1x'],
                    "p_1_2x" => $data['p_1_2x'],
                    "p_1_3x" => $data['p_1_3x'],
                    "p_1_rata_rata" => $data['p_1_rata_rata'],
                    "p_1_m2" => $data['p_1_m2'],
                    "p_1_catatan" => $data['p_1_catatan'],
                ],
                "p_2" => [
                    "title" => "P 2",
                    "p_2_produk" => $data['p_2_produk'],
                    "p_2_awal_cm" => $data['p_2_awal_cm'],
                    "p_2_awal_m2" => $data['p_2_awal_m2'],
                    "p_2_1x" => $data['p_2_1x'],
                    "p_2_2x" => $data['p_2_2x'],
                    "p_2_3x" => $data['p_2_3x'],
                    "p_2_rata_rata" => $data['p_2_rata_rata'],
                    "p_2_m2" => $data['p_2_m2'],
                    "p_2_catatan" => $data['p_2_catatan'],
                ],
                "p_3" => [
                    "title" => "P 3",
                    "p_3_produk" => $data['p_3_produk'],
                    "p_3_awal_cm" => $data['p_3_awal_cm'],
                    "p_3_awal_m2" => $data['p_3_awal_m2'],
                    "p_3_1x" => $data['p_3_1x'],
                    "p_3_2x" => $data['p_3_2x'],
                    "p_3_3x" => $data['p_3_3x'],
                    "p_3_rata_rata" => $data['p_3_rata_rata'],
                    "p_3_m2" => $data['p_3_m2'],
                    "p_3_catatan" => $data['p_3_catatan'],
                ],
                "p_4" => [
                    "title" => "P 4",
                    "p_4_produk" => $data['p_4_produk'],
                    "p_4_awal_cm" => $data['p_4_awal_cm'],
                    "p_4_awal_m2" => $data['p_4_awal_m2'],
                    "p_4_1x" => $data['p_4_1x'],
                    "p_4_2x" => $data['p_4_2x'],
                    "p_4_3x" => $data['p_4_3x'],
                    "p_4_rata_rata" => $data['p_4_rata_rata'],
                    "p_4_m2" => $data['p_4_m2'],
                    "p_4_catatan" => $data['p_4_catatan'],
                ],
                "p_5" => [
                    "title" => "P 5",
                    "p_5_produk" => $data['p_5_produk'],
                    "p_5_awal_cm" => $data['p_5_awal_cm'],
                    "p_5_awal_m2" => $data['p_5_awal_m2'],
                    "p_5_1x" => $data['p_5_1x'],
                    "p_5_2x" => $data['p_5_2x'],
                    "p_5_3x" => $data['p_5_3x'],
                    "p_5_rata_rata" => $data['p_5_rata_rata'],
                    "p_5_m2" => $data['p_5_m2'],
                    "p_5_catatan" => $data['p_5_catatan'],
                ],
                "p_6" => [
                    "title" => "P 6",
                    "p_6_produk" => $data['p_6_produk'],
                    "p_6_awal_cm" => $data['p_6_awal_cm'],
                    "p_6_awal_m2" => $data['p_6_awal_m2'],
                    "p_6_1x" => $data['p_6_1x'],
                    "p_6_2x" => $data['p_6_2x'],
                    "p_6_3x" => $data['p_6_3x'],
                    "p_6_rata_rata" => $data['p_6_rata_rata'],
                    "p_6_m2" => $data['p_6_m2'],
                    "p_6_catatan" => $data['p_6_catatan'],
                ]
            ];

            $model->table_json = json_encode($table_json);
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
            if($data['type_approve']=='menyetujui'){
                $model->menyetujui = $user_id;
                $model->is_menyetujui = 1;
                $model->approved_menyetujui_at = Carbon::now();
                $model->signature_approval_menyetujui = self::store_signature($data['tanda_tangan'], $user_id);
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
    public static function reject($data, $user_id, $penerima){
        DB::beginTransaction();
        try {
            if($data['type_approve']=='menyaksikan'){
                $penerima->menyaksikan = $user_id;
                $penerima->rejected_menyaksikan_at = Carbon::now();
                $penerima->rejected_menyaksikan_reason = $data['alasan_reject'];
                // 0 = pending || 1 = approved || 2 = rejected
                $penerima->is_menyaksikan = 2;
                $penerima->status = 'done';
                $penerima->status_approval = 'rejected_menyaksikan';
                $penerima->save();
            }elseif($data['type_approve']=='mengetahui_1'){
                $penerima->mengetahui_1 = $user_id;
                $penerima->rejected_mengetahui_1_at = Carbon::now();
                $penerima->rejected_mengetahui_1_reason = $data['alasan_reject'];
                // 0 = pending || 1 = approved || 2 = rejected
                $penerima->is_mengetahui_1 = 2;
                // $penerima->status = 'done';
                $penerima->save();
            }elseif($data['type_approve']=='diperiksa'){
                $penerima->diperiksa = $user_id;
                $penerima->rejected_diperiksa_at = Carbon::now();
                $penerima->rejected_diperiksa_reason = $data['alasan_reject'];
                // 0 = pending || 1 = approved || 2 = rejected
                $penerima->is_diperiksa = 2;
                // $penerima->status = 'done';
                $penerima->save();
            }elseif($data['type_approve']=='penerima'){
                $penerima->penerima = $user_id;
                $penerima->rejected_penerima_at = Carbon::now();
                $penerima->rejected_penerima_reason = $data['alasan_reject'];
                // 0 = pending || 1 = approved || 2 = rejected
                $penerima->is_penerima = 2;
                // $penerima->status = 'done';
                $penerima->save();
            }else{
                throw new \Exception("Cant do this action");
            }
           
            DB::commit();
            return model_response(true,'Data berhasil ditolak', $penerima);
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
}
