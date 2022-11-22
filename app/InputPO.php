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

class InputPO extends Model
{
    use Upload;
    
    protected $table = 'input_po';

    protected $casts = [
        'tanggal_po' => 'datetime:Y-m-d',
    ];


    public static function store($data, $user_id){
        DB::beginTransaction();
        try {
            $flagErrorMail = false;
            // insert 
            $model = new InputPO;
            $model->sub_company_id = $data['perusahaan'];
            $model->kapal_id = $data['kapal'];
            $model->no_po = $data['nomor_po'];
            $model->nama_customer = $data['customer'];
            $model->wilayah = $data['wilayah'];
            $model->jenis_kegiatan = $data['jenis_kegiatan'];
            $model->tanggal_po = $data['tanggal_po'];
            $model->contact_person = $data['contact_person'];
            $model->jenis_produk = $data['jenis_produk'];
            $model->qty = $data['quantity'];
            $model->no_sao = $data['nomor_sao'];
            

            if (isset($data['file']) && !empty($data['file'])) {
                $filename = Files::uploadLocalOrS3($data['file'], "input-po/$user_id");
                $model->image = "user-uploads/input-po/$user_id/$filename";
            }
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
            $model = InputPO::find($id);
            if (!isset($model) && empty($model)){ 
                DB::rollback();
                return model_response(false,'Data not found');
            }
            $model->sub_company_id = $data['perusahaan'];
            $model->kapal_id = $data['kapal'];
            $model->no_po = $data['nomor_po'];
            $model->nama_customer = $data['customer'];
            $model->wilayah = $data['wilayah'];
            $model->jenis_kegiatan = $data['jenis_kegiatan'];
            $model->tanggal_po = $data['tanggal_po'];
            $model->contact_person = $data['contact_person'];
            $model->jenis_produk = $data['jenis_produk'];
            $model->qty = $data['quantity'];
            $model->no_sao = $data['nomor_sao'];
            

            if (isset($data['file']) && !empty($data['file'])) {
                if (\File::exists(public_path($model->image))) {
                    // remove prev image
                    \File::delete(public_path($model->image));
                }
                $filename = Files::uploadLocalOrS3($data['file'], "input-po/$user_id");
                $model->image = "user-uploads/input-po/$user_id/$filename";
            }
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
