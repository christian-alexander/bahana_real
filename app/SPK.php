<?php

namespace App;

// use App\Observers\SPKObserver;

use App\Logistik\Lokasi;
use App\Logistik\MtStock;
use App\Logistik\PesanPP;
use App\Logistik\RekapPP;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\User;

// use App\Scopes\CompanyScope;

class SPK extends Model
{
    // masih gak dipake
    protected static function boot()
    {
        parent::boot();

        // static::observe(PertanyaanObserver::class);

        // static::addGlobalScope(new CompanyScope);

        // self::creating(function($model){
        //     $model->user_id = auth()->user()->id;
        //     $model->created_by = auth()->user()->id;
        // });
    }
    protected $table = 'spk';

    public function details()
    {
        return $this->hasMany(SPKDetail::class, 'spk_id', 'id');
    }
    public function approval()
    {
        return $this->hasMany(SPKApproval::class, 'spk_id', 'id');
    }
    public function activity()
    {
        return $this->hasMany(SPKActivity::class, 'spk_id', 'id');
    }
    public function performance()
    {
        return $this->hasMany(SPKPerformance::class, 'spk_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function store_signature($base64, $user_id){
        $folderPath = public_path('user-uploads/signature/'.$user_id.'/');

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }
       
        $image_parts = explode(";base64,", $base64);
             
        $image_type_aux = explode("image/", $image_parts[0]);
           
        $image_type = $image_type_aux[1];
           
        $image_base64 = base64_decode($image_parts[1]);
 
        $signature = uniqid() . '.'.$image_type;
           
        $file = $folderPath . $signature;
        
        $relative_path = 'user-uploads/signature/'.$user_id.'/'.$signature;
 
        file_put_contents($file, $image_base64);

        return $relative_path;
    }

    public static function store($data, $user_id){
        DB::beginTransaction();
        try {
            // insert into spk
            $model = new SPK;
            $model->mt_or_spob = $data['mt_or_spob'];
            $model->no = $data['no'];
            $model->keperluan = $data['keperluan'];
            $model->tanggal = $data['tanggal'];
            $model->note = $data['note'];
            $model->user_id = $user_id;
            $model->created_by = $user_id;
            $model->signature_applicant = self::store_signature($data['tanda_tangan'], $user_id);
            $model->save();


            if (isset($data['type']) && !empty($data['type'])) {
                // save to detail
                for ($i=0; $i < count($data['type']); $i++) { 
                    $detail = new SPKDetail;
                    $detail->spk_id = $model->id;
                    if ($data['type'][$i]=='barang_etc') {
                        $detail->barang_etc = $data['barang'][$i];
                    }else{
                        $detail->barang_id = $data['barang'][$i];
                        $detail->barang_etc = $data['barang_name'][$i];
                        
                        $json_barang = MtStock::where('kdstk',$data['barang'][$i])->first();
                        $json_barang = json_encode($json_barang);
                        $detail->json_barang = $json_barang;
                    }
                    $detail->barang_diminta = $data['barang_diminta'][$i];
                    $detail->ket = $data['ket'][$i];
                    $detail->save();
                }
                SPKActivity::store($model->id,$user_id,SPKActivity::CREATE_SPK);
            }
            DB::commit();
            return model_response(true,'Data berhasil disimpan');
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
    public static function have_permission($user, $for){
        $resp = false;
        if (isset($user->employeeDetail) && !empty($user->employeeDetail)) {
            $permission = json_decode($user->employeeDetail->additional_field, true);
            if (isset($permission[$for]) && !empty($permission[$for])) {
                $resp = true;
            }
        }
        return $resp;
    }
    public static function approve($data, $user_id, $getSPK,$check_approval_status){
        DB::beginTransaction();
        try {
            $user = User::find($user_id);
            $data_approve = null;
            $msg = 'Data berhasil diterima';

            if (!isset($check_approval_status) && empty($check_approval_status)) {
                // jika kosong maka ini adalah approval ke 1
                // check user yang login punya akses untuk approve
                if(self::have_permission($user, 'is_nahkoda')){
                    $data_approve = self::set_approved($getSPK->id,$user,'approved','approved_1',null,$data['tanda_tangan']);
                    // update data spk
                    $getSPK->status = 'onprogress';
                    $getSPK->status_approval = 'Diterima Nahkoda';
                    $getSPK->save();

                }
            }elseif($check_approval_status->status=='approved_1'){
                // ini menunggu approval ke 2
                if(self::have_permission($user, 'is_admin')){
                    // change barang_id
                    if (isset($data['detail_id']) && !empty($data['detail_id'])) {
                        for ($i=0; $i < count($data['detail_id']); $i++) { 
                            if (!isset($data['barang_id'][$i]) && empty($data['barang_id'][$i])) {
                                throw new \Exception("Barang tidak boleh kosong");
                            }
                            // get data detail
                            $getSPKDetail = SPKDetail::find($data['detail_id'][$i]);
                            if (!isset($getSPKDetail) && empty($getSPKDetail)) {
                                throw new \Exception("SPK Detail not found");
                            }
                            $msg_activity_detail = "mengganti barang lain-lain (".$getSPKDetail->barang_etc.") menjadi ".$data['barang_id'][$i];
                            $getSPKDetail->barang_id = $data['barang_id'][$i];
                        
                            $json_barang = MtStock::where('kdstk',$data['barang_id'][$i])->first();

                            $getSPKDetail->barang_etc = $json_barang->nm;
                            $json_barang = json_encode($json_barang);
                            $getSPKDetail->json_barang = $json_barang;

                            $getSPKDetail->save();

                            SPKActivity::store($getSPK->id,$user->id, $msg_activity_detail);
                        }

                        // SPKActivity::store($getSPK->id,$user->id,SPKActivity::CHANGE_BARANG_ETC);
                    }

                    $data_approve = self::set_approved($getSPK->id,$user,'approved','approved_2');

                    // insert into spk performance
                    if (isset($data['point']) && !empty($data['point'])) {
                        SPKPerformance::ratePerformance([
                            "spk_id"=> $getSPK->id,
                            "alasan"=> $data['alasan'],
                            "point"=> $data['point'],
                        ]);

                        SPKActivity::store($getSPK->id,$user->id,SPKActivity::REPORT_PERFORMANCE);
                    }
                    $getSPK->status_approval = 'Diterima Admin';
                    $getSPK->save();

                }
            }elseif($check_approval_status->status=='approved_2'){
                // ini menunggu approval ke 3
                if ($getSPK->keperluan=='mesin') {
                    if($user->employeeDetail->is_pe==1){
                        if (isset($data['detail_id']) && !empty($data['detail_id'])) {
                            for ($i=0; $i < count($data['detail_id']); $i++) { 
                                // get data detail
                                $getSPKDetail = SPKDetail::find($data['detail_id'][$i]);
                                if (!isset($getSPKDetail) && empty($getSPKDetail)) {
                                    throw new \Exception("SPK Detail not found");
                                }
                                $getSPKDetail->barang_disetujui = $data['qty_disetujui'][$i];
                                $getSPKDetail->save();
    
                                SPKActivity::store($getSPK->id,$user->id,'mengubah jumlah barang ('.$getSPKDetail->barang_etc.') yang disetujui dari '.$getSPKDetail->barang_diminta.' ke '.$getSPKDetail->barang_disetujui);
                            }
                        }
                        $getSPK->status_approval = 'Diterima PE';
                        $getSPK->save();
                    }
                }else{
                    if($user->employeeDetail->is_pc==1){
                        if (isset($data['detail_id']) && !empty($data['detail_id'])) {
                            for ($i=0; $i < count($data['detail_id']); $i++) { 
                                // get data detail
                                $getSPKDetail = SPKDetail::find($data['detail_id'][$i]);
                                if (!isset($getSPKDetail) && empty($getSPKDetail)) {
                                    throw new \Exception("SPK Detail not found");
                                }
                                $getSPKDetail->barang_disetujui = $data['qty_disetujui'][$i];
                                $getSPKDetail->save();
    
                                SPKActivity::store($getSPK->id,$user->id,'mengubah jumlah barang ('.$getSPKDetail->barang_etc.') yang disetujui dari '.$getSPKDetail->barang_diminta.' ke '.$getSPKDetail->barang_disetujui);
                            }
                        }
                        $getSPK->status_approval = 'Diterima PC';
                        $getSPK->save();
                    }
                }
                $data_approve = self::set_approved($getSPK->id,$user,'approved','approved_3');
            }elseif($check_approval_status->status=='approved_3'){
                // ini menunggu approval ke 3
                if(self::have_permission($user, 'is_manager')){

                    // ubah barang disetujui
                    if (isset($data['detail_id']) && !empty($data['detail_id'])) {
                        for ($i=0; $i < count($data['detail_id']); $i++) { 
                            // get data detail
                            $getSPKDetail = SPKDetail::find($data['detail_id'][$i]);
                            if (!isset($getSPKDetail) && empty($getSPKDetail)) {
                                throw new \Exception("SPK Detail not found");
                            }
                            $getSPKDetail->barang_disetujui = $data['qty_disetujui'][$i];
                            $getSPKDetail->save();

                            SPKActivity::store($getSPK->id,$user->id,'mengubah jumlah barang ('.$getSPKDetail->barang_etc.') yang disetujui dari '.$getSPKDetail->barang_diminta.' ke '.$getSPKDetail->barang_disetujui);
                        }

                        // SPKActivity::store($getSPK->id,$user->id,SPKActivity::QTY_APPROVE);
                    }

                    $data_approve = self::set_approved($getSPK->id,$user,'approved','approved_4',null,$data['tanda_tangan']);

                    // update data spk
                    $getSPK->status = 'done';
                    $getSPK->status_approval = 'Diterima Manager';
                    $getSPK->save();


                } 
            }elseif($check_approval_status->status=='approved_4'){
                // check no pp unique
                // $check = SPK::where('pp_id',$data['no_pp'])->count();
                // if ($check>0) {
                //     throw new \Exception("No pp tidak boleh kembar");
                // }
                // verif spv
                // update data spk
                // $getSPK->pp_id = $data['no_pp'];
                $getSPK->cabang = $data['cabang'];

                $data_cabang = Lokasi::where('lok',$data['cabang'])->first();
                $getSPK->json_cabang = json_encode($data_cabang);
                $getSPK->verif_spv= 1;
                $getSPK->save();

                // insert into rekappp and pesanpp
                $rekappp = new RekapPP;
                $rekappp->tgl = Carbon::now();
                $rekappp->nopo = null;
                $rekappp->nosp = $getSPK->id;
                $rekappp->nmord = null;
                $rekappp->dept = null;
                $rekappp->dept1 = null;
                $rekappp->dept2 = null;
                $rekappp->cat = null;
                $rekappp->ousr1 = null;
                $rekappp->ousr2 = null;
                $rekappp->ousr3 = null;
                $rekappp->ousr4 = null;
                $rekappp->nmusr = User::find($getSPK->user_id)->name;
                $rekappp->tgusr = null;
                $rekappp->save();
                
                // get detail
                $detailSPK = SPKDetail::where('spk_id',$getSPK->id)->get();
                foreach ($detailSPK as $val) {
                    $arr_barang =[];
                    if (isset($val->json_barang) && !empty($val->json_barang)) {
                        $arr_barang = json_decode($val->json_barang, true);
                    }
                    $pesanpp = new PesanPP;
                    $pesanpp->tgl=Carbon::now();
                    $pesanpp->nopb=null;
                    $pesanpp->nosp=$getSPK->id;
                    $pesanpp->ket=$val->ket;
                    $pesanpp->kdstk=$val->barang_id;
                    $pesanpp->bhn=null;
                    $pesanpp->sat=$arr_barang['sat'];
                    $pesanpp->qty=$val->barang_diminta;
                    // $pesanpp->qtystk=;
                    $pesanpp->qtybeli=$val->barang_disetujui;
                    $pesanpp->kdcab=$getSPK->cabang;
                    $pesanpp->kdsup=null;
                    $pesanpp->ketkhus=null;
                    $pesanpp->kethgs=null;
                    $pesanpp->usrhgs=null;
                    $pesanpp->nmusr=User::find($getSPK->user_id)->name;
                    $pesanpp->tgusr=null;
                    $pesanpp->save();
                }


                // SPKActivity::store($getSPK->id,$user_id,'menambahkan no pp ('.$data['no_pp'].') dan memverifikasi surat permintaan kapal ini');
                SPKActivity::store($getSPK->id,$user_id,'menambahkan cabang ('.$data_cabang->nm.') dan memverifikasi surat permintaan kapal ini');

                $msg = 'Data berhasil diverifikasi';
            }else{
                throw new \Exception("You dont have permission to using this function");
            }
            DB::commit();
            return model_response(true,$msg, $data_approve);
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
    public static function reject($data, $user_id, $spk_id){
        DB::beginTransaction();
        try {
            $getSPK = SPK::find($spk_id);
            if (!isset($getSPK) && empty($getSPK)) {
                throw new \Exception("Data not found");
            }
            $reason = isset($data['alasan_reject']) && !empty($data['alasan_reject'])?$data['alasan_reject']:null;
            $user = User::find($user_id);
            $data_approve = null;
            $check_approval_status = SPKApproval::where('spk_id', $getSPK->id)->orderBy('updated_at','DESC')->first();
            if (!isset($check_approval_status) && empty($check_approval_status)) {
                // jika kosong maka ini adalah reject ke 1
                // check user yang login punya akses untuk approve
                if(self::have_permission($user, 'is_nahkoda')){
                    $data_approve =self::set_approved($getSPK->id,$user,'rejected','rejected_1', $reason);
                    // update data spk
                    $getSPK->status = 'done';
                    $getSPK->status_approval = 'Ditolak Nahkoda';
                    $getSPK->save();

                }
            }elseif($check_approval_status->status=='approved_1'){
                // ini menunggu approval ke 2
                if(self::have_permission($user, 'is_admin')){
                    $data_approve =self::set_approved($getSPK->id,$user,'rejected','rejected_2', $reason);

                    // update data spk
                    $getSPK->status = 'done';
                    $getSPK->status_approval = 'Ditolak Admin';
                    $getSPK->save();

                }
            }elseif($check_approval_status->status=='approved_2'){
                // ini menunggu approval ke 3
                if(self::have_permission($user, 'is_manager')){
                    $data_approve =self::set_approved($getSPK->id,$user,'rejected','rejected_3', $reason);

                    // update data spk
                    $getSPK->status = 'done';
                    $getSPK->status_approval = 'Ditolak Manager';
                    $getSPK->save();

                }
                if ($getSPK->keperluan=='mesin') {
                    if($user->employeeDetail->is_pe==1){
                        $data_approve =self::set_approved($getSPK->id,$user,'rejected','rejected_3', $reason);

                        // update data spk
                        $getSPK->status = 'done';
                        $getSPK->status_approval = 'Ditolak PE';
                        $getSPK->save();
                    }
                }else{
                    if($user->employeeDetail->is_pc==1){
                        $data_approve =self::set_approved($getSPK->id,$user,'rejected','rejected_3', $reason);

                        // update data spk
                        $getSPK->status = 'done';
                        $getSPK->status_approval = 'Ditolak PC';
                        $getSPK->save();
                    }
                }
            }elseif($check_approval_status->status=='approved_3'){
                // ini menunggu approval ke 3
                if(self::have_permission($user, 'is_manager')){
                    $data_approve =self::set_approved($getSPK->id,$user,'rejected','rejected_4', $reason);

                    // update data spk
                    $getSPK->status = 'done';
                    $getSPK->status_approval = 'Ditolak Manager';
                    $getSPK->save();

                }
            }else{
                throw new \Exception("You dont have permission to using this function");
            }
            DB::commit();
            return model_response(true,'Data berhasil ditolak', $data_approve);
        } catch (\Throwable $e) {
            //throw $th;
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }

    public static function set_approved($spk_id,$user, $tipe, $status, $reason=null, $signature = null){
        // insert into spk approval
        $model = new SPKApproval;
        $model->spk_id = $spk_id;
        $model->tipe = $tipe;
        $model->status = $status;
        if ($status=='approved_1' || $status =='approved_4') {
            $model->signature = self::store_signature($signature, $user->id);
        }
        if ($tipe=='approved') {
            $model->approved_by = $user->id;
            $model->approved_at = Carbon::now();

            $activity = SPKActivity::APPROVE_SPK;
        }else{
            $model->rejected_by = $user->id;
            $model->rejected_at = Carbon::now();
            $model->rejected_reason = $reason;

            $activity = SPKActivity::REJECT_SPK;
        }
        $model->save();

        SPKActivity::store($spk_id,$user->id,$activity);

        return $model;
    }

}
