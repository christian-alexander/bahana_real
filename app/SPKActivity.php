<?php

namespace App;

// use App\Observers\SPKObserver;
use Illuminate\Database\Eloquent\Model;

// use App\Scopes\CompanyScope;

class SPKActivity extends Model
{
    protected $table = 'spk_activity';

    const CREATE_SPK = 'membuat surat permintaan kapal';
    const APPROVE_SPK = 'menyetujui surat permintaan kapal ini';
    const REJECT_SPK = 'menolak surat permintaan kapal ini';
    const VERIF_SPK = 'menambahkan no pp dan memverifikasi surat permintaan kapal ini';
    const REPORT_PERFORMANCE = 'melaporkan kinerja user';
    const QTY_APPROVE = 'mengubah jumlah barang yang disetujui';
    const CHANGE_BARANG_ETC = 'mengganti barang lain-lain menjadi id barang';
    const DELETE_BARANG = 'menghapus barang';

    public static function store($spk_id,$user_id, $activity){
        $user = User::find($user_id);

        $model  = new SPKActivity;
        $model->spk_id = $spk_id;
        $model->triggered_by = $user->id;
        $model->activity = $user->name.' '.$activity;
        $model->save();
    }

}
