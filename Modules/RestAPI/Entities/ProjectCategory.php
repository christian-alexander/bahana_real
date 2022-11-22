<?php

namespace Modules\RestAPI\Entities;


use App\Observers\ProjectObserver;
use App\User;
use Illuminate\Database\Eloquent\Builder;

class ProjectCategory extends \App\ProjectCategory
{


    protected $default = [
        'id',
        'company_id',
        'category_name',
    ];

    protected $dates = [];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [
        'id',
        'company_id',
        'created_at',
        'updated_at',
    ];

    protected $filterable = [
        'id',
        'company_id',
        'category_name',
    ];
}
