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
use App\SubCompany;
use App\User;
use App\Wilayah;
use File;
use Modules\RestAPI\Entities\Department;

class PermintaanDanaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail($user_id,$id)
    {
        $user = User::find($user_id);
        $getData = PermintaanDana::leftjoin('users as u_pembuat','u_pembuat.id','permintaan_dana.pembuat')
            ->leftjoin('sub_company as s','s.id','permintaan_dana.subcompany_id')
            ->leftjoin('users as u_nama','u_nama.id','permintaan_dana.user_id')
            ->leftjoin('users as u_disetujui','u_disetujui.id','permintaan_dana.disetujui')
            ->leftjoin('users as u_mengetahui_1','u_mengetahui_1.id','permintaan_dana.mengetahui_1')
            ->leftjoin('users as u_diperiksa','u_diperiksa.id','permintaan_dana.diperiksa')
            ->where('permintaan_dana.id', $id)
            ->selectRaw('permintaan_dana.*,
            u_pembuat.name as name_pembuat,
            u_disetujui.name as name_disetujui,
            u_mengetahui_1.name as name_mengetahui_1,
            u_diperiksa.name as name_diperiksa,
            u_nama.name as nama,
            s.name as subcompany
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
        //diperiksa
        if ($getData->is_diperiksa==0) {
            array_push($activity,"Waiting action $getData->name_diperiksa (diperiksa)");
        }elseif($getData->is_diperiksa==1){
            array_push($activity,"Approved by $getData->name_diperiksa (diperiksa)");
        }elseif($getData->is_diperiksa==2){
            array_push($activity,"Rejected by $getData->name_diperiksa (diperiksa)");
        }
        //mengetahui
        if ($getData->is_mengetahui==0) {
            array_push($activity,"Waiting action $getData->name_mengetahui_1 (mengetahui)");
        }elseif($getData->is_mengetahui==1){
            array_push($activity,"Approved by $getData->name_mengetahui_1 (mengetahui)");
        }elseif($getData->is_mengetahui==2){
            array_push($activity,"Rejected by $getData->name_mengetahui_1 (mengetahui)");
        }
        //disetujui
        if ($getData->is_disetujui==0) {
            array_push($activity,"Waiting action $getData->name_disetujui (disetujui)");
        }elseif($getData->is_disetujui==1){
            array_push($activity,"Approved by $getData->name_disetujui (disetujui)");
        }elseif($getData->is_disetujui==2){
            array_push($activity,"Rejected by $getData->name_disetujui (disetujui)");
        }

        $can_approve = false;
        $require_signature = false;
        $type_approve = '';
        $getFormApproval = GeneralSetting::where('company_id', $user->company_id)->first();
        $getFormApproval = json_decode($getFormApproval->form_approval);

        if (isset($getFormApproval) && !empty($getFormApproval)) {
            foreach ($getFormApproval as $val) {
                if (isset($val->type) && !empty($val->type)) {
                    if ($val->type=='permintaan_dana') {
                        $getFormApproval =$val;
                    }
                }
            }
        }
        // get atasan user
        $permission = EmployeeDetails::where('user_id', $getData->pembuat)->first();
        $permission = json_decode($permission->permission_require, true);
        if (isset($getFormApproval) && !empty($getFormApproval)) {
            if ($getFormApproval->type=='permintaan_dana') {
                if ($getFormApproval->diperiksa==$user->id && $getData->is_diperiksa == 0 ) {
                    // can approve
                    $can_approve = true;
                    $type_approve ='diperiksa';
                    $require_signature = true;
                }elseif ($getFormApproval->mengetahui_1==$user->id && $getData->is_mengetahui_1 == 0) {
                    // can approve
                    $can_approve = true;
                    $type_approve ='mengetahui_1';
                    $require_signature = true;
                }elseif ($getFormApproval->disetujui==$user->id && $getData->is_disetujui == 0) {
                    // can approve
                    $can_approve = true;
                    $type_approve ='disetujui';
                    $require_signature = true;
                }
            }
        }
        $getData->can_approve = $can_approve;
        $getData->type_approve = $type_approve;
        $getData->require_signature = $require_signature;
        return view('iframe.permintaan-dana.detail', [
            'data'=> $getData,
            'user'=>$user,
            'activity' =>$activity
        ]);
    }
    public function create($user_id)
    {
        $user = User::find($user_id);

        // get anak perusahaan
        $subCompany = SubCompany::where('company_id', $user->company_id)->select('name','id')->get();

        // get user
        $data_user = \DB::table('users')->get();

        return view('iframe.permintaan-dana.create',[
            "user_id"=> $user_id,
            "subCompany"=> $subCompany,
            "data_user"=> $data_user,
        ]);
    }
    public function store(request $request, $user_id){
        $this->validate($request, [
            'subcompany' => 'required',
            'user_id' => 'required',
            'tanggal' => 'required',
            'keperluan' => 'required',
            'nominal' => 'required',
            'tanda_tangan' => 'required',
        ], [
            'subcompany.required' => 'Anak Perusahaan tidak boleh kosong',
            'user_id.required' => 'Nama tidak boleh kosong',
            'tanggal.required' => 'Tanggal tidak boleh kosong',
            'keperluan.required' => 'Keperluan tidak boleh kosong',
            'nominal.required' => 'Nominal tidak boleh kosong',
            'tanda_tangan.required' => 'Tanda Tangan tidak boleh kosong',
        ]);
        // logic store 
        $store = PermintaanDana::store($request->all(), $user_id);

        return redirect()->route('permintaan-dana.create',[
            $user_id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function approve(request $request,$user_id, $id){
        $data = PermintaanDana::find($id);
        if (!isset($data) && empty($data)) {
            return redirect()->route('permintaan-dana.detail',[
                $user_id,
                $id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }

        $approval = PermintaanDana::approve($request->all(),$user_id,$data);
        return redirect()->route('permintaan-dana.detail',[
            $user_id,
            $id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
    public function reject(request $request,$user_id, $id){
        $data = PermintaanDana::find($id);
        if (!isset($data) && empty($data)) {
            return redirect()->route('permintaan-dana.detail',[
                $user_id,
                $id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }
        $approval = PermintaanDana::reject($request->all(),$user_id, $data);
        return redirect()->route('permintaan-dana.detail',[
            $user_id,
            $id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
}
