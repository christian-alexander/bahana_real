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

class RencanaPelayanan extends Model
{
    use Upload;
    
    protected $table = 'rencana_pelayanan';


    public static function store($data, $user_id){
        DB::beginTransaction();
        try {
            $flagErrorMail = false;
            // insert 
            $model = new RencanaPelayanan;
            $model->input_po_id = $data['input_po'];
            $model->kapal_id = $data['kapal'];
            $model->tanggal_rencana_bunker = $data['tanggal_rencana_bunker'];
            $model->nama_oob = $data['nama_oob'];
            $model->no_rfb = $data['nomor_rfb'];
            
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
    public static function updateModel($data, $user_id, $id){
        DB::beginTransaction();
        try {
            $flagErrorMail = false;
            // insert 
            $model = RencanaPelayanan::find($id);
            if (!isset($model) && empty($model)){ 
                DB::rollback();
                return model_response(false,'Data not found');
            }
            $model->input_po_id = $data['input_po'];
            $model->kapal_id = $data['kapal'];
            $model->tanggal_rencana_bunker = $data['tanggal_rencana_bunker'];
            $model->nama_oob = $data['nama_oob'];
            $model->no_rfb = $data['nomor_rfb'];
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
    
}
