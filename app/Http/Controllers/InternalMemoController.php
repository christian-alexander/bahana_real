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
use App\SubCompany;
use App\User;
use App\Wilayah;
use File;
use Modules\RestAPI\Entities\Department;

class InternalMemoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail($user_id,$id)
    {
        $user = User::find($user_id);
        $getData = InternalMemo::leftjoin('users as u_pembuat','u_pembuat.id','internal_memo.pembuat')
            ->leftjoin('sub_company as s','s.id','internal_memo.subcompany_id')
            ->leftjoin('teams as t','t.id','internal_memo.team_id')
            ->leftjoin('wilayah as w','w.id','internal_memo.wilayah_id')
            ->leftjoin('users as u_from','u_from.id','internal_memo.from_user_id')
            ->leftjoin('users as u_to','u_to.id','internal_memo.to_user_id')
            ->leftjoin('users as u_penerima','u_penerima.id','internal_memo.penerima')
            ->leftjoin('users as u_mengetahui_1','u_mengetahui_1.id','internal_memo.mengetahui_1')
            ->leftjoin('users as u_mengetahui_2','u_mengetahui_2.id','internal_memo.mengetahui_2')
            ->where('internal_memo.id', $id)
            ->selectRaw('internal_memo.*,
            u_pembuat.name as name_pembuat,
            u_penerima.name as name_penerima,
            u_mengetahui_1.name as name_mengetahui_1,
            u_mengetahui_2.name as name_mengetahui_2,
            u_from.name as name_from,
            u_to.name as name_to,
            s.name as subcompany,
            t.team_name as department,
            w.name as wilayah
            ')
            ->first();
        if (!isset($getData) && empty($getData)) {
            // TODO: ganti ke param
            return response()->json([
                'error' => [
                  'status' => 404,
                  'message' => 'Internal memo not found',
                ]
              ]);
        }
        $can_approve = false;
        $require_signature = false;
        $type_approve = '';
        $getFormApproval = GeneralSetting::where('company_id', $user->company_id)->first();
        $getFormApproval = json_decode($getFormApproval->form_approval);

        if (isset($getFormApproval) && !empty($getFormApproval)) {
            foreach ($getFormApproval as $val) {
                if (isset($val->type) && !empty($val->type)) {
                    if ($val->type=='internal_memo') {
                        $getFormApproval =$val;
                    }
                }
            }
        }
        // get atasan user
        $permission = EmployeeDetails::where('user_id', $getData->pembuat)->first();
        $permission = json_decode($permission->permission_require, true);
        if (isset($getFormApproval) && !empty($getFormApproval)) {
            if ($getFormApproval->type=='internal_memo') {
                if ($getFormApproval->mengetahui_1==$user->id && $getData->is_mengetahui_1 == 0 ) {
                    // can approve
                    $can_approve = true;
                    $type_approve ='mengetahui_1';
                    $require_signature = true;
                }elseif ($getFormApproval->mengetahui_2==$user->id && $getData->is_mengetahui_2 == 0) {
                    // can approve
                    $can_approve = true;
                    $type_approve ='mengetahui_2';
                    $require_signature = true;
                }
                // jika atasan_1
                if ($getFormApproval->mengetahui_1=='atasan_1' && $getData->is_mengetahui_1 == 0 ) {
                    if ($permission[0]==$user->id) {
                        // can approve
                        $can_approve = true;
                        $type_approve ='mengetahui_1';
                        $require_signature = true;
                    }

                }elseif ($getFormApproval->mengetahui_2=='atasan_2' && $getData->is_mengetahui_2 == 0) {
                    if ($permission[1]==$user->id) {
                        // can approve
                        $can_approve = true;
                        $type_approve ='mengetahui_2';
                        $require_signature = true;
                    }
                }
            }
        }
        if ($user->id==$getData->to_user_id && $getData->is_penerima == 0) {
            $can_approve = true;
            $type_approve ='penerima';
            $require_signature = true;
        }
        $getData->can_approve = $can_approve;
        $getData->type_approve = $type_approve;
        $getData->require_signature = $require_signature;
        return view('iframe.internal-memo.detail', [
            'data'=> $getData,
            'user'=>$user
        ]);
    }
    public function create($user_id)
    {
        $user = User::find($user_id);

        // get anak perusahaan
        $subCompany = SubCompany::where('company_id', $user->company_id)->select('name','id')->get();

        // get department
        $department = Department::where('company_id', $user->company_id)->select('team_name','id')->get();

        // get wilayah
        $wilayah = Wilayah::where('company_id', $user->company_id)->select('name','id')->get();

        // get user
        $data_user = \DB::table('users')->get();

        return view('iframe.internal-memo.create',[
            "user_id"=> $user_id,
            "subCompany"=> $subCompany,
            "department"=> $department,
            "wilayah"=> $wilayah,
            "data_user"=> $data_user,
        ]);
    }
    public function store(request $request, $user_id){
        $this->validate($request, [
            'anak_perusahaan' => 'required',
            'department' => 'required',
            'wilayah' => 'required',
            'dari' => 'required',
            'kepada' => 'required',
            'tanggal' => 'required',
            'tempat' => 'required',
            'perihal' => 'required',
            'sifat' => 'required',
            'berita' => 'required',
            'tanda_tangan' => 'required',
        ], [
            'anak_perusahaan.required' => 'Anak Perusahaan tidak boleh kosong',
            'department.required' => 'Department tidak boleh kosong',
            'wilayah.required' => 'Wilayah tidak boleh kosong',
            'dari.required' => 'Dari tidak boleh kosong',
            'kepada.required' => 'Kepada tidak boleh kosong',
            'tanggal.required' => 'Tanggal tidak boleh kosong',
            'tempat.required' => 'Tempat tidak boleh kosong',
            'perihal.required' => 'Perihal tidak boleh kosong',
            'sifat.required' => 'Sifat tidak boleh kosong',
            'berita.required' => 'Berita tidak boleh kosong',
            'tanda_tangan.required' => 'Tanda Tangan tidak boleh kosong',
        ]);
        // logic store 
        $store = InternalMemo::store($request->all(), $user_id);

        return redirect()->route('internal-memo.create',[
            $user_id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function approve(request $request,$user_id, $id){
        $data = InternalMemo::find($id);
        if (!isset($data) && empty($data)) {
            return redirect()->route('internal-memo.detail',[
                $user_id,
                $id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }

        $approval = InternalMemo::approve($request->all(),$user_id,$data);
        return redirect()->route('internal-memo.detail',[
            $user_id,
            $id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
    public function reject(request $request,$user_id, $id){
        $data = InternalMemo::find($id);
        if (!isset($data) && empty($data)) {
            return redirect()->route('internal-memo.detail',[
                $user_id,
                $id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }
        $approval = InternalMemo::reject($request->all(),$user_id, $data);
        return redirect()->route('internal-memo.detail',[
            $user_id,
            $id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
}
