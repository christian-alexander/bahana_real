<?php

namespace App\Logistik;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $connection = 'mysql_logistik';

    protected $table = 'stock';

}
