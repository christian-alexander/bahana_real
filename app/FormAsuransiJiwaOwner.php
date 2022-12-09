<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormAsuransiJiwaOwner extends Model
{
    protected $table        = "form_asuransi_jiwa_owners";
    protected $primaryKey   = "id";

    protected $fillable = [
        'user_id','nama_pemilik','asuransi', 'tahapan_asuransi', 'nilai_asuransi', 'jumlah_premi', 'jatuh_tempo', 'tanggal_bayar', 'note', 'status',
    ];
}
