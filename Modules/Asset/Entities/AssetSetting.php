<?php

namespace Modules\Asset\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Asset\Observers\AssetObserver;

class AssetSetting extends Model
{
    //region Properties

    protected $table = 'asset_settings';


    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];


    protected $dates = [
        'created_at',
        'updated_at'
    ];

    //endregion

    //region Boot

    public static function boot()
    {
        parent::boot();
    }

    //endregion


    public function history()
    {
        return $this->hasMany(AssetHistory::class);
    }

    //endregion

    //region Custom Functions

}
