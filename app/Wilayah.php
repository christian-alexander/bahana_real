<?php

namespace App;

use App\Observers\WilayahObserver;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    protected $fillable = ['name', 'company_id','code'];

    protected static function boot()
    {
        parent::boot();

        static::observe(WilayahObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function members()
    {
        return $this->hasMany(EmployeeDetails::class, 'wilayah_id');
    }
}
