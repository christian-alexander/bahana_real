<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;

class Schedule extends Model
{
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });
        self::saving(function ($model) {
            if (company()) {
                $model->company_id = company()->id;
            }
        });
        static::addGlobalScope(new CompanyScope);
    }
}
