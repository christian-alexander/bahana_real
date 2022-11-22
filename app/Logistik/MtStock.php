<?php

namespace App\Logistik;

use Illuminate\Database\Eloquent\Model;

class MtStock extends Model
{
    protected $connection = 'mysql_logistik';

    protected $table = 'mtstock';

}
