<?php

namespace Modules\RestAPI\Entities;


use App\Observers\LeaveTypeObserver;
use App\User;
use Illuminate\Database\Eloquent\Builder;

class LeaveType extends \App\LeaveType
{


    protected $default = [
        'id',
        'company_id',
        'type_name',
        'display_name',
        'created_at',
    ];

    protected $dates = [];

    protected $hidden = [];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $filterable = [
        'id',
        'company_id',
        'type_name',
        'created_at',
    ];
}
