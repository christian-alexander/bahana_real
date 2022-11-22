<?php

namespace App;

use App\Observers\PertanyaanObserver;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class Pertanyaan extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::observe(PertanyaanObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
