<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;
use App\Observers\CabangObserver;
use App\Observers\SubCompanyObserver;

class SubCompany extends Model
{
    protected $table = 'sub_company';
    protected $fillable = ['name', 'company_id','code'];

    protected static function boot()
    {
        parent::boot();

        static::observe(SubCompanyObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function members()
    {
        return $this->hasMany(EmployeeDetails::class, 'sub_company_id');
    }
}
