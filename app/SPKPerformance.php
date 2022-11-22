<?php

namespace App;

// use App\Observers\SPKObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// use App\Scopes\CompanyScope;

class SPKPerformance extends Model
{
    protected $table = 'spk_performance';
    
    public static function ratePerformance($data){
        DB::beginTransaction();
        try {
            $user = auth()->user();

            // only admin can using this function
            if(!SPK::have_permission($user, 'is_admin')){
                throw new \Exception("You dont have permission to using this function");
            }
            
            // check data
            $getSPK = SPK::find($data['spk_id']);
            if (!isset($getSPK) && empty($getSPK)) {
                throw new \Exception("Data not found");
            }

            //insert into spk_performance
            $setSPkPerformance = new SPKPerformance;
            $setSPkPerformance->spk_id = $getSPK->id;
            $setSPkPerformance->user_id = $getSPK->user_id;
            $setSPkPerformance->desc = $data['alasan'];
            $setSPkPerformance->point = $data['point'];
            $setSPkPerformance->save();

            DB::commit();
            return model_response(true,'Berhasil menilai user', $setSPkPerformance);
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
}
