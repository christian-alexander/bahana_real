<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleKapal extends Model
{
    protected $table = 'schedule_kapal';
    protected $fillable = ['user_id', 'kapal_id', 'date_start', 'date_end', 'created_by'];
}
