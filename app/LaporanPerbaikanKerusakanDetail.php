<?php

namespace App;

// use App\Observers\SPKObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\User;

// use App\Scopes\CompanyScope;

class LaporanPerbaikanKerusakanDetail extends Model
{
    protected $table = 'laporan_perbaikan_kerusakan_detail';
}
