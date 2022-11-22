<?php

namespace App;

// use App\Observers\SPKObserver;

use App\Notifications\FormCreated;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// use App\Scopes\CompanyScope;

class SBPBBM extends Model
{
    use Upload;
    
    protected $table = 'sbpbbm';

    protected $casts = [
        'tanggal' => 'datetime:Y-m-d',
    ];


    public static function store($data, $user_id){
        DB::beginTransaction();
        try {
            $flagErrorMail = false;
            // dd($data);
            // insert 
            $model = new SBPBBM;
            $model->office_id = $data['kapal'];
            $model->bagian = $data['bagian'];

            // aturan no jurnal
            $now = Carbon::parse($data['tanggal']);
            $tahun = $now->copy()->format('Y');
            $bulan = $now->copy()->format('m');
            $kapal=$data['kapal'];
            $bagian=$data['bagian'];

            $no_urut = SBPBBM::whereYear('tanggal',$tahun)
                ->whereMonth('tanggal',$bulan)
                ->count()+1;
            $no_urut = sprintf('%03d', $no_urut);

            $no_jurnal = "$tahun/$bulan/JS/$kapal/$bagian/$no_urut";

            $model->no_jurnal = $no_jurnal;
            $model->tanggal = $data['tanggal'];
            $model->jam = $data['jam'];
            $model->rob_awal = $data['rob_awal'];
            $model->rob_akhir = $data['rob_akhir'];
            $model->port_lokasi = $data['port_lokasi'];

            $pemakaian_json = [
                "me" => $data['pemakaian_me'],
                "test" => $data['pemakaian_test'],
            ];

            $model->pemakaian_json = json_encode($pemakaian_json);

            $table_json =[
                "s_2"=> [
                    "title" => "S 2",
                    "s_2_1x" => $data['s_2_1x'],
                    "s_2_2x" => $data['s_2_2x'],
                    "s_2_3x" => $data['s_2_3x'],
                    "s_2_rata_rata" => $data['s_2_rata_rata'],
                    "s_2_m2" => $data['s_2_m2'],
                    "s_2_catatan" => $data['s_2_catatan'],
                ],
                "s_3" => [
                    "title" => "S 3",
                    "s_3_1x" => $data['s_3_1x'],
                    "s_3_2x" => $data['s_3_2x'],
                    "s_3_3x" => $data['s_3_3x'],
                    "s_3_rata_rata" => $data['s_3_rata_rata'],
                    "s_3_m2" => $data['s_3_m2'],
                    "s_3_catatan" => $data['s_3_catatan'],
                ],
                "c_1" => [
                    "title" => "C 1",
                    "c_1_1x" => $data['c_1_1x'],
                    "c_1_2x" => $data['c_1_2x'],
                    "c_1_3x" => $data['c_1_3x'],
                    "c_1_rata_rata" => $data['c_1_rata_rata'],
                    "c_1_m2" => $data['c_1_m2'],
                    "c_1_catatan" => $data['c_1_catatan'],
                ],
                "p_2" => [
                    "title" => "P 2",
                    "p_2_1x" => $data['p_2_1x'],
                    "p_2_2x" => $data['p_2_2x'],
                    "p_2_3x" => $data['p_2_3x'],
                    "p_2_rata_rata" => $data['p_2_rata_rata'],
                    "p_2_m2" => $data['p_2_m2'],
                    "p_2_catatan" => $data['p_2_catatan'],
                ],
                "p_3" => [
                    "title" => "P 3",
                    "p_3_1x" => $data['p_3_1x'],
                    "p_3_2x" => $data['p_3_2x'],
                    "p_3_3x" => $data['p_3_3x'],
                    "p_3_rata_rata" => $data['p_3_rata_rata'],
                    "p_3_m2" => $data['p_3_m2'],
                    "p_3_catatan" => $data['p_3_catatan'],
                ],
                "h_me" => [
                    "title" => "H ME",
                    "h_me_1x" => $data['h_me_1x'],
                    "h_me_2x" => $data['h_me_2x'],
                    "h_me_3x" => $data['h_me_3x'],
                    "h_me_rata_rata" => $data['h_me_rata_rata'],
                    "h_me_m2" => $data['h_me_m2'],
                    "h_me_catatan" => $data['h_me_catatan'],
                ],
                "h_ae" => [
                    "title" => "H AE",
                    "h_ae_1x" => $data['h_ae_1x'],
                    "h_ae_2x" => $data['h_ae_2x'],
                    "h_ae_3x" => $data['h_ae_3x'],
                    "h_ae_rata_rata" => $data['h_ae_rata_rata'],
                    "h_ae_m2" => $data['h_ae_m2'],
                    "h_ae_catatan" => $data['h_ae_catatan'],
                ],
                "setling" =>[
                    "title" => "Setling",
                    "setling_1x" => $data['setling_1x'],
                    "setling_2x" => $data['setling_2x'],
                    "setling_3x" => $data['setling_3x'],
                    "setling_rata_rata" => $data['setling_rata_rata'],
                    "setling_m2" => $data['setling_m2'],
                    "setling_catatan" => $data['setling_catatan'],
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
            if($data['type_approve']=='menyaksikan'){
                $model->menyaksikan = $user_id;
                $model->is_menyaksikan = 1;
                $model->approved_menyaksikan_at = Carbon::now();
                $model->signature_approval_menyaksikan = self::store_signature($data['tanda_tangan'], $user_id);
                $model->status = 'done';
                $model->status_approval = 'approved_menyaksikan';
                $model->save();
            }elseif($data['type_approve']=='mengetahui_1'){
                $model->mengetahui_1 = $user_id;
                $model->is_mengetahui_1 = 1;
                $model->approved_mengetahui_1_at = Carbon::now();
                $model->signature_approval_mengetahui_1 = self::store_signature($data['tanda_tangan'], $user_id);
                $model->save();
            }elseif($data['type_approve']=='diperiksa'){
                $model->diperiksa = $user_id;
                $model->is_diperiksa = 1;
                $model->approved_diperiksa_at = Carbon::now();
                $model->signature_approval_diperiksa = self::store_signature($data['tanda_tangan'], $user_id);
                $model->save();
            }elseif($data['type_approve']=='penerima'){
                $model->penerima = $user_id;
                $model->is_penerima = 1;
                $model->approved_penerima_at = Carbon::now();
                $model->signature_approval_penerima = self::store_signature($data['tanda_tangan'], $user_id);
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
