<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisPerijinan extends Model
{
    protected $table        = "jenis_perijinans";
    protected $primaryKey   = "id";

    protected $fillable = [
        'nama','reminder'
    ];
}
