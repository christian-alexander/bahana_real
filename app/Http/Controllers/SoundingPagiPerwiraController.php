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
use App\Logistik\MtStock;
use App\Office;
use App\PermintaanDana;
use App\SBPBBM;
use App\SoundingPagiPerwira;
use App\SubCompany;
use App\User;
use App\Wilayah;
use File;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\Department;

class SoundingPagiPerwiraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail($user_id,$id)
    {
        $user = User::find($user_id);
        $getData = SoundingPagiPerwira::leftjoin('users as u_pembuat','u_pembuat.id','sounding_pagi_perwira.pembuat')
            ->leftjoin('office as o','o.id','sounding_pagi_perwira.office_id')
            ->leftjoin('users as u_menyetujui','u_menyetujui.id','sounding_pagi_perwira.menyetujui')
            ->where('sounding_pagi_perwira.id', $id)
            ->selectRaw('sounding_pagi_perwira.*,
            u_pembuat.name as name_pembuat,
            u_menyetujui.name as name_menyetujui,
            o.name as office
            ')
            ->first();
        if (!isset($getData) && empty($getData)) {
            // TODO: ganti ke param
            return response()->json([
                'error' => [
                  'status' => 404,
                  'message' => 'Form Sounding Pagi Perwira not found',
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
        
        $can_approve = false;
        $require_signature = false;
        $type_approve = '';
        $getFormApproval = GeneralSetting::where('company_id', $user->company_id)->first();
        $getFormApproval = json_decode($getFormApproval->form_approval);

        if (isset($getFormApproval) && !empty($getFormApproval)) {
            foreach ($getFormApproval as $val) {
                if (isset($val->type) && !empty($val->type)) {
                    if ($val->type=='sounding_pagi_perwira') {
                        $getFormApproval =$val;
                    }
                }
            }
        }
        // menyetujui
        // get user menyetujui
        $userMenyetujui = DB::table('users')->find($getFormApproval->menyetujui);
        if ($getData->is_menyetujui==0) {
            array_push($activity,"Waiting action $userMenyetujui->name (menyetujui)");
        }elseif($getData->is_menyetujui==1){
            array_push($activity,"Approved by $getData->name_menyetujui (menyetujui)");
        }elseif($getData->is_menyetujui==2){
            array_push($activity,"Rejected by $getData->name_menyetujui (menyetujui)");
        }

        // get atasan user
        $permission = EmployeeDetails::where('user_id', $getData->pembuat)->first();
        $permission = json_decode($permission->permission_require, true);
        if (isset($getFormApproval) && !empty($getFormApproval)) {
            if ($getFormApproval->type=='sounding_pagi_perwira') {
                if ($getFormApproval->menyetujui==$user->id && $getData->is_menyetujui == 0 ) {
                    // can approve
                    $can_approve = true;
                    $type_approve ='menyetujui';
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
        return view('iframe.sounding-pagi-perwira.detail', [
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

        $barang = MtStock::select('kdstk','nm')->get();

        return view('iframe.sounding-pagi-perwira.create',[
            "user_id"=> $user_id,
            "kapal"=> $kapal,
            "data_user"=> $data_user,
            "barang"=> $barang,
        ]);
    }
    public function store(request $request, $user_id){
        $this->validate($request, [
            'armada' => 'required',
            'bagian' => 'required',
            'tanggal' => 'required',
            'lokasi' => 'required',
            'tanda_tangan' => 'required',
        ], [
            'armada.required' => 'Armada tidak boleh kosong',
            'bagian.required' => 'Bagian tidak boleh kosong',
            'tanggal.required' => 'Tanggal tidak boleh kosong',
            'lokasi.required' => 'Lokasi tidak boleh kosong',
            'tanda_tangan.required' => 'Tanda Tangan tidak boleh kosong',
        ]);
        // logic store 
        $store = SoundingPagiPerwira::store($request->all(), $user_id);

        return redirect()->route('sounding-pagi-perwira.create',[
            $user_id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function approve(request $request,$user_id, $id){
        $data = SoundingPagiPerwira::find($id);
        if (!isset($data) && empty($data)) {
            return redirect()->route('sounding-pagi-perwira.detail',[
                $user_id,
                $id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }

        $approval = SoundingPagiPerwira::approve($request->all(),$user_id,$data);
        return redirect()->route('sounding-pagi-perwira.detail',[
            $user_id,
            $id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
    public function reject(request $request,$user_id, $id){
        $data = SBPBBM::find($id);
        if (!isset($data) && empty($data)) {
            return redirect()->route('sounding-pagi-perwira.detail',[
                $user_id,
                $id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }
        $approval = SBPBBM::reject($request->all(),$user_id, $data);
        return redirect()->route('sounding-pagi-perwira.detail',[
            $user_id,
            $id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
}
