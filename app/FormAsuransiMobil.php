<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormAsuransiMobil extends Model
{
    protected $table        = "form_asuransi_mobils";
    protected $primaryKey   = "id";

    protected $fillable = [
        'user_id','wilayah_operasional','keterangan_mobil', 'pengguna', 'asuransi', 'nilai_asuransi', 'start_berlaku', 'end_berlaku', 'posisi_dokumen_asli', 'note', 'status',
    ];
}
