<?php

namespace App;

// use App\Observers\SPKObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// use App\Scopes\CompanyScope;

class SPKDetail extends Model
{
    protected $table = 'spk_detail';

    public function spk()
    {
        return $this->belongsTo(SPK::class, 'spk_id', 'id');
    }

    public static function changeProductEtc($data){
        DB::beginTransaction();
        try {
            $user = auth()->user();

            // only admin can using this function
            if(!SPK::have_permission($user, 'is_admin')){
                throw new \Exception("You dont have permission to using this function");
            }
            
            // check data
            $getSPKDetail = SPKDetail::find($data['spk_detail_id']);
            if (!isset($getSPKDetail) && empty($getSPKDetail)) {
                throw new \Exception("Data not found");
            }

            // logic change product
            $getSPKDetail->barang_etc = null;
            $getSPKDetail->barang_id = $data['barang_id'];
            $getSPKDetail->save();

            DB::commit();
            return model_response(true,'Data berhasil diubah', $getSPKDetail);
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
    public static function changeQty($data){
        DB::beginTransaction();
        try {
            $user = auth()->user();

            // only admin can using this function
            if(!SPK::have_permission($user, 'is_manager')){
                throw new \Exception("You dont have permission to using this function");
            }
            
            // check data
            $getSPKDetail = SPKDetail::find($data['spk_detail_id']);
            if (!isset($getSPKDetail) && empty($getSPKDetail)) {
                throw new \Exception("Data not found");
            }

            // logic change product
            $getSPKDetail->barang_disetujui = $data['qty'];
            $getSPKDetail->save();

            DB::commit();
            return model_response(true,'Qty berhasil diubah', $getSPKDetail);
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
    public static function remove($user_id,$spk_detail_id){
        DB::beginTransaction();
        try {
            $user = User::find($user_id);

            // only admin can using this function
            if(!SPK::have_permission($user, 'is_manager')){
                throw new \Exception("You dont have permission to using this function");
            }
            
            // check data
            $getSPKDetail = SPKDetail::find($spk_detail_id);
            if (!isset($getSPKDetail) && empty($getSPKDetail)) {
                throw new \Exception("Data not found");
            }
            $spk_id = $getSPKDetail->spk_id;
            $barang = $getSPKDetail->barang_etc;

            // logic delete product
            $getSPKDetail->delete();

            SPKActivity::store($spk_id,$user->id,SPKActivity::DELETE_BARANG.' '.$barang);

            DB::commit();
            return model_response(true,'Data berhasil dihapus', $getSPKDetail);
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
}
