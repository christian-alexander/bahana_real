<?php

namespace App;

use App\Observers\OfficeObserver;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class Office extends Model
{
    protected $table = 'office';
    protected $fillable = ['name', 'company_id', 'code', 'latitude', 'longitude', 'jam_istirahat_awal', 'jam_istirahat_akhir'];

    protected static function boot()
    {
        parent::boot();

        static::observe(OfficeObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
