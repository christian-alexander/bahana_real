<?php

namespace Modules\RestAPI\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;


class Tracker extends Model
{
    // use HasFactory;
    protected $connection = 'mysql_tracker';
    protected $table = 'trackers';


    protected $default = [
        'id',
        'user_id',
        'latitude',
      	'longitude',
      	'catatan',
        'is_manual'
    ];

    protected $hidden = [
    ];

    protected $guarded = [
        'id',
    ];

    protected $filterable = [
    ];


}
