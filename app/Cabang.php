<?php

namespace App;

use App\Observers\CabangObserver;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class Cabang extends Model
{
    protected $table = 'cabang';
    protected $fillable = ['name', 'company_id'];

    protected static function boot()
    {
        parent::boot();

        static::observe(CabangObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function members()
    {
        return $this->hasMany(EmployeeDetails::class, 'cabang_id');
    }
}
