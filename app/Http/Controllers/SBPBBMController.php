<?php

namespace App\Http\Controllers;

use App\EmployeeDetails;
use App\GeneralSetting;
use App\Http\Controllers\Controller;
use App\InternalMemo;
use Illuminate\Http\Request;
use App\LaporanKerusakan;
use App\LaporanPenangguhanPekerjaan;
use App\LaporanPerbaikanKerusakan;
use App\Office;
use App\PermintaanDana;
use App\SBPBBM;
use App\SubCompany;
use App\User;
use App\Wilayah;
use File;
use Modules\RestAPI\Entities\Department;

class SBPBBMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail($user_id,$id)
    {
        $user = User::find($user_id);
        $getData = SBPBBM::leftjoin('users as u_pembuat','u_pembuat.id','sbpbbm.pembuat')
            ->leftjoin('office as o','o.id','sbpbbm.office_id')
            ->leftjoin('users as u_menyaksikan','u_menyaksikan.id','sbpbbm.menyaksikan')
            ->leftjoin('users as u_mengetahui_1','u_mengetahui_1.id','sbpbbm.mengetahui_1')
            ->leftjoin('users as u_diperiksa','u_diperiksa.id','sbpbbm.diperiksa')
            ->leftjoin('users as u_penerima','u_penerima.id','sbpbbm.penerima')
            ->where('sbpbbm.id', $id)
            ->selectRaw('sbpbbm.*,
            u_pembuat.name as name_pembuat,
            u_menyaksikan.name as name_menyaksikan,
            u_mengetahui_1.name as name_mengetahui_1,
            u_diperiksa.name as name_diperiksa,
            u_penerima.name as name_penerima,
            o.name as office
            ')
            ->first();
        if (!isset($getData) && empty($getData)) {
            // TODO: ganti ke param
            return response()->json([
                'error' => [
                  'status' => 404,
                  'message' => 'Form pendaan not found',
                ]
              ]);
        }
        $activity =[];
        // pembuat
        if ($getData->is_pembuat==0) {
            array_push($activity,"Waiting action $getData->name_pembuat (pembuat)");
        }elseif($getData->is_pembuat==1){
            array_push($activity,"Approved by $getData->name_pembuat (pembuat)");
        }elseif($getData->is_pembuat==2){
            array_push($activity,"Rejected by $getData->name_pembuat (pembuat)");
        }
        // menyaksikan
        if ($getData->is_menyaksikan==0) {
            array_push($activity,"Waiting action $getData->name_menyaksikan (menyaksikan)");
        }elseif($getData->is_menyaksikan==1){
            array_push($activity,"Approved by $getData->name_menyaksikan (menyaksikan)");
        }elseif($getData->is_menyaksikan==2){
            array_push($activity,"Rejected by $getData->name_menyaksikan (menyaksikan)");
        }

        //diperiksa
        if ($getData->is_diperiksa==0) {
            array_push($activity,"Waiting action $getData->name_diperiksa (diperiksa)");
        }elseif($getData->is_diperiksa==1){
            array_push($activity,"Approved by $getData->name_diperiksa (diperiksa)");
        }elseif($getData->is_diperiksa==2){
            array_push($activity,"Rejected by $getData->name_diperiksa (diperiksa)");
        }
        //
        if ($getData->is_mengetahui_1==0) {
            array_push($activity,"Waiting action $getData->name_mengetahui_1 (mengetahui)");
        }elseif($getData->is_mengetahui_1==1){
            array_push($activity,"Approved by $getData->name_mengetahui_1 (mengetahui)");
        }elseif($getData->is_mengetahui_1==2){
            array_push($activity,"Rejected by $getData->name_mengetahui_1 (mengetahui)");
        }
        //penerima
        if ($getData->is_penerima==0) {
            array_push($activity,"Waiting action $getData->name_penerima (penerima)");
        }elseif($getData->is_penerima==1){
            array_push($activity,"Approved by $getData->name_penerima (penerima)");
        }elseif($getData->is_penerima==2){
            array_push($activity,"Rejected by $getData->name_penerima (penerima)");
        }


        $can_approve = false;
        $require_signature = false;
        $type_approve = '';
        $getFormApproval = GeneralSetting::where('company_id', $user->company_id)->first();
        $getFormApproval = json_decode($getFormApproval->form_approval);

        if (isset($getFormApproval) && !empty($getFormApproval)) {
            foreach ($getFormApproval as $val) {
                if (isset($val->type) && !empty($val->type)) {
                    if ($val->type=='sounding_bunker_pemakaian_bbm') {
                        $getFormApproval =$val;
                    }
                }
            }
        }
        // get atasan user
        $permission = EmployeeDetails::where('user_id', $getData->pembuat)->first();
        $permission = json_decode($permission->permission_require, true);
        if (isset($getFormApproval) && !empty($getFormApproval)) {
            if ($getFormApproval->type=='sounding_bunker_pemakaian_bbm') {
                if ($getFormApproval->menyaksikan==$user->id && $getData->is_menyaksikan == 0 ) {
                    // can approve
                    $can_approve = true;
                    $type_approve ='menyaksikan';
                    $require_signature = true;
                }elseif ($getFormApproval->mengetahui_1==$user->id && $getData->is_mengetahui_1 == 0) {
                    // can approve
                    $can_approve = true;
                    $type_approve ='mengetahui_1';
                    $require_signature = true;
                }elseif ($getFormApproval->diperiksa==$user->id && $getData->is_diperiksa == 0) {
                    // can approve
                    $can_approve = true;
                    $type_approve ='diperiksa';
                    $require_signature = true;
                }elseif ($getFormApproval->penerima==$user->id && $getData->is_penerima == 0) {
                    // can approve
                    $can_approve = true;
                    $type_approve ='penerima';
                    $require_signature = true;
                }
            }
        }
        $getData->can_approve = $can_approve;
        $getData->type_approve = $type_approve;
        $getData->require_signature = $require_signature;
        $getData->pemakaian_json = json_decode($getData->pemakaian_json);
        $getData->table_json = json_decode($getData->table_json);
        // return $getData;
        return view('iframe.sounding-bunker-pemakaian-bbm.detail', [
            'data'=> $getData,
            'user'=>$user,
            'activity' =>$activity
        ]);
    }
    public function create($user_id)
    {
        $user = User::find($user_id);

        $kapal = Office::where('is_kapal',1)->where('company_id', $user->company_id)->get();

        // get user
        $data_user = \DB::table('users')->get();

        return view('iframe.sounding-bunker-pemakaian-bbm.create',[
            "user_id"=> $user_id,
            "kapal"=> $kapal,
            "data_user"=> $data_user,
        ]);
    }
    public function store(request $request, $user_id){
        $this->validate($request, [
            'kapal' => 'required',
            'bagian' => 'required',
            'tanggal' => 'required',
            'jam' => 'required',
            'rob_awal' => 'required',
            'rob_akhir' => 'required',
            'port_lokasi' => 'required',
            'tanda_tangan' => 'required',
        ], [
            'kapal.required' => 'Kapal tidak boleh kosong',
            'bagian.required' => 'Bagian tidak boleh kosong',
            'tanggal.required' => 'Tanggal tidak boleh kosong',
            'jam.required' => 'Jam tidak boleh kosong',
            'rob_awal.required' => 'Rob Awal tidak boleh kosong',
            'rob_akhir.required' => 'Rob Akhir tidak boleh kosong',
            'port_lokasi.required' => 'Port Lokasi tidak boleh kosong',
            'tanda_tangan.required' => 'Tanda Tangan tidak boleh kosong',
        ]);
        // logic store 
        $store = SBPBBM::store($request->all(), $user_id);

        return redirect()->route('sounding-bunker-pemakaian-bbm.create',[
            $user_id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function approve(request $request,$user_id, $id){
        $data = SBPBBM::find($id);
        if (!isset($data) && empty($data)) {
            return redirect()->route('sounding-bunker-pemakaian-bbm.detail',[
                $user_id,
                $id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }

        $approval = SBPBBM::approve($request->all(),$user_id,$data);
        return redirect()->route('sounding-bunker-pemakaian-bbm.detail',[
            $user_id,
            $id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
    public function reject(request $request,$user_id, $id){
        $data = SBPBBM::find($id);
        if (!isset($data) && empty($data)) {
            return redirect()->route('sounding-bunker-pemakaian-bbm.detail',[
                $user_id,
                $id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }
        $approval = SBPBBM::reject($request->all(),$user_id, $data);
        return redirect()->route('sounding-bunker-pemakaian-bbm.detail',[
            $user_id,
            $id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
}
