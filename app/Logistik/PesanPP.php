<?php

namespace App\Logistik;

use Illuminate\Database\Eloquent\Model;

class PesanPP extends Model
{
    protected $connection = 'mysql_logistik';

    protected $table = 'pesanpp';

    public $timestamps = false;
}
