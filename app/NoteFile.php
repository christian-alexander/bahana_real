<?php

namespace App;

use App\Observers\CabangObserver;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\DB;

class NoteFile extends Model
{
    protected $table = 'note_files';

    protected $appends = ['full_url_file'];

    public function getFullUrlFileAttribute()
    {
        return asset_url_local_s3($this->url_file);
    }

    public static function storeModel($request){
        DB::beginTransaction();
        try {
            $model ='';
            DB::commit();
            return model_response(true,'Data berhasil disimpan',$model);
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
}
