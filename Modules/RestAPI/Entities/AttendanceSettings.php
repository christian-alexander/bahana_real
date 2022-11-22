<?php

namespace Modules\RestAPI\Entities;



class AttendanceSettings extends \App\Attendance
{
    protected $table = 'attendance_settings';

    protected $default = [
        'id',
        'company_id',
        'office_start_time',
        'office_end_time',
        'halfday_mark_time',
        'late_mark_duration',
        'clockin_in_day',
        'employee_clock_in_out',
        'office_open_days',
        'ip_address',
        'radius',
        'radius_check',
        'ip_check',
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
