<?php

namespace App;

use App\Observers\GeneralSettingObserver;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class GeneralSetting extends Model
{
    //
    protected static function boot()
    {
        parent::boot();

        static::observe(GeneralSettingObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

}
