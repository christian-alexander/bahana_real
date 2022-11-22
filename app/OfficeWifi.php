<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfficeWifi extends Model
{
    protected $table = 'office_wifi';
    protected $fillable = ['office_id', 'name', 'bssid'];
}
