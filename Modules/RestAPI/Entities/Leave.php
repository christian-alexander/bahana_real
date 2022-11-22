<?php

namespace Modules\RestAPI\Entities;


use App\Observers\LeaveObserver;
use App\User;
use Illuminate\Database\Eloquent\Builder;

class Leave extends \App\Leave
{


    protected $default = [
        'id',
        'leave_date',
        'leave_type_id',
        'leave_date_end',
        'reason',
        'status',
        'user_id',
        'created_at',
    ];

    protected $dates = ['leave_date', 'leave_date_end'];

    protected $hidden = [];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $filterable = [
        'id',
        'reason',
        'status',
        'created_at',
        'leave_date',
        'user_id',
        'leave_type_id',
    ];
}
