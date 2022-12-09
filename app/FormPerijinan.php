<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormPerijinan extends Model
{
    protected $table        = "form_perijinans";
    protected $primaryKey   = "id";

    protected $fillable = [
        'user_id','nama_perusahaan','pihak_kedua', 'jenis_perijinan_id', 'no_perijinan', 'start_berlaku', 'end_berlaku', 'posisi_dokumen', 'nama_pic', 'no_hp', 'email', 'jabatan', 'attachment', 'note', 'status',
    ];
}
