<?php

namespace Modules\RestAPI\Entities;



class Attendance extends \App\Attendance
{
    protected $table = 'attendances';

    protected $default = [
        'id',
        'company_id',
        'user_id',
        'clock_in_time',
        'clock_in_ip',
        'clock_out_ip',
        'late',
        'half_day',
        'working_from',
        'clock_out_time',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $filterable = [
        'id',
        'company_id',
        'user_id',
    ];
}
