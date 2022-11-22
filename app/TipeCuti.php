<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;
use App\Observers\TipeCutiObserver;

class TipeCuti extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::observe(TipeCutiObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
