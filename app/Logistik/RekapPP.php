<?php

namespace App\Logistik;

use Illuminate\Database\Eloquent\Model;

class RekapPP extends Model
{
    protected $connection = 'mysql_logistik';

    protected $table = 'rekappp';

    public $timestamps = false;
}
