<?php

namespace App\Http\Controllers;

use App\forminternalmemo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Helper\Files;
use App\Traits\Upload;
use App\SubCompany;
use App\User;
use App\Wilayah;
use Modules\RestAPI\Entities\Department;
use App\EmployeeDetails;
use App\GeneralSetting;
use PDF;

class FormInternalMemoController extends Controller
{
    public function detail($user_id,$id)
    {
        $user = User::find($user_id);
        $getData = forminternalmemo::leftjoin('users as u_pembuat','u_pembuat.id','form_internal_memo.pembuat')
            ->leftjoin('sub_company as s','s.id','form_internal_memo.subcompany_id')
            ->leftjoin('teams as t','t.id','form_internal_memo.team_id')
            ->leftjoin('sub_company as s2','s2.id','form_internal_memo.subcompany_id_2')
            ->leftjoin('teams as t2','t2.id','form_internal_memo.team_id_2')
            ->leftjoin('users as u_from','u_from.id','form_internal_memo.from_user_id')
            ->leftjoin('users as u_to','u_to.id','form_internal_memo.to_user_id')
            ->leftjoin('users as al_1','al_1.id','form_internal_memo.atasan_langsung_1')
            ->leftjoin('users as al_2','al_2.id','form_internal_memo.atasan_langsung_2')
            ->leftjoin('users as u_penerima','u_penerima.id','form_internal_memo.penerima')
            ->leftjoin('users as u_mengetahui_1','u_mengetahui_1.id','form_internal_memo.mengetahui_1')
            ->leftjoin('users as u_mengetahui_2','u_mengetahui_2.id','form_internal_memo.mengetahui_2')
            ->where('form_internal_memo.id', $id)
            ->selectRaw('form_internal_memo.*,
            u_pembuat.name as name_pembuat,
            u_penerima.name as name_penerima,
            u_mengetahui_1.name as name_mengetahui_1,
            u_mengetahui_2.name as name_mengetahui_2,
            u_from.name as name_from,
            u_to.name as name_to,
            s.name as subcompany,
            s2.name as subcompany_2,
            t.team_name as department,
            t2.team_name as department_2,
            al_1.name as atasan_langsung_1,
            al_2.name as atasan_langsung_2')
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
        if ($getData->is_penerima==0) {
            array_push($activity,"Waiting action $getData->name_penerima (penerima)");
        }elseif($getData->is_penerima==1){
            array_push($activity,"Approved by $getData->name_penerima (penerima)");
        }elseif($getData->is_penerima==2){
            array_push($activity,"Rejected by $getData->name_penerima (penerima)");
        }
        //mengetahui_1
        if ($getData->is_mengetahui_1==0) {
            array_push($activity,"Waiting action $getData->name_mengetahui_1 (atasan langsung 1)");
        }elseif($getData->is_mengetahui_1==1){
            array_push($activity,"Approved by $getData->name_mengetahui_1 (atasan langsung 1)");
        }elseif($getData->is_mengetahui_1==2){
            array_push($activity,"Rejected by $getData->name_mengetahui_1 (atasan langsung 1)");
        }
        //mengetahui_1
        if ($getData->is_mengetahui_2==0) {
            array_push($activity,"Waiting action $getData->name_mengetahui_2 (atasan langsung 2)");
        }elseif($getData->is_mengetahui_2==1){
            array_push($activity,"Approved by $getData->name_mengetahui_2 (atasan langsung 2)");
        }elseif($getData->is_mengetahui_2==2){
            array_push($activity,"Rejected by $getData->name_mengetahui_2 (atasan langsung 2)");
        }

        $can_approve = false;
        $require_signature = false;
        $type_approve = '';
        // $getFormApproval = GeneralSetting::where('company_id', $user->company_id)->first();
        // $getFormApproval = json_decode($getFormApproval->form_approval);

        // if (isset($getFormApproval) && !empty($getFormApproval)) {
        //     foreach ($getFormApproval as $val) {
        //         if (isset($val->type) && !empty($val->type)) {
        //             if ($val->type=='internal_memo') {
        //                 $getFormApproval =$val;
        //             }
        //         }
        //     }
        // }
        // get atasan user
        $permission = EmployeeDetails::where('user_id', $getData->pembuat)->first();
        $permission = json_decode($permission->permission_require, true);



        $disetujui = EmployeeDetails::where('department_id',4)->where('is_atasan',1)->first();
        $disetujui = json_decode($disetujui->user_id,true);

        if ($permission[0]==$user->id && $getData->is_mengetahui_1 == 0 ) {
            // can approve
            $can_approve = true;
            $type_approve ='mengetahui_1';
            $require_signature = true;
        }
        elseif ($permission[1]==$user->id && $getData->is_mengetahui_2 == 0) {
            // can approve
            $can_approve = true;
            $type_approve ='mengetahui_2';
            $require_signature = true;
        }
        elseif ($user->id==$getData->to_user_id && $getData->is_penerima == 0) {
            $can_approve = true;
            $type_approve ='penerima';
            $require_signature = true;
        }


        $getData->can_approve = $can_approve;
        $getData->type_approve = $type_approve;
        $getData->require_signature = $require_signature;

        return view('iframe.forminternal-memo.detail', [
            'data'=> $getData,
            'user'=>$user,
            'user_id'=>$user_id,
            'activity' =>$activity
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

        $permission_detail = EmployeeDetails::where('user_id',$user_id)->first();
        $permission_require = $permission_detail->permission_require;
        $atasan_satu = substr($permission_require,2,strpos($permission_require,'"',2)-2);
        $temp_satu = strstr(substr($permission_require,2),'"');
        $atasan_dua = substr($temp_satu,3,strpos($temp_satu,'"',3)-3);
        // $temp_dua = strstr(substr($temp_satu,4),'"');
        // $atasan_tiga = substr($temp_dua,3,strpos($temp_dua,'"',3)-3);


        $atasan1 = DB::table('users')->where('id',$atasan_satu)->first();
        $atasan2 = DB::table('users')->where('id',$atasan_dua)->first();

        return view('iframe.forminternal-memo.create',[
            "user_id"=> $user_id,
            "subCompany"=> $subCompany,
            "department"=> $department,
            "atasan1"=>$atasan1,
            "atasan2"=>$atasan2,
            "data_user"=> $data_user,
        ]);
    }
    public function store(request $request, $user_id){
        $this->validate($request, [
            'anak_perusahaan' => 'required',
            'anak_perusahaan_2' => 'required',
            'department' => 'required',
            'department_2' => 'required',
            // 'wilayah' => 'required',
            'dari' => 'required',
            'kepada' => 'required',
            'tanggal' => 'required',
            // 'tempat' => 'required',
            'perihal' => 'required',
            'sifat' => 'required',
            'berita' => 'required',
            'atasan_langsung_1' => 'required',
            'file' => 'required',
            'tanda_tangan' => 'required',
        ], [
            'anak_perusahaan.required' => 'Anak Perusahaan tidak boleh kosong',
            'anak_perusahaan_2.required' => 'Anak Perusahaan tidak boleh kosong',
            'department.required' => 'Department tidak boleh kosong',
            'department_2.required' => 'Department tidak boleh kosong',
            // 'wilayah.required' => 'Wilayah tidak boleh kosong',
            'dari.required' => 'Dari tidak boleh kosong',
            'kepada.required' => 'Kepada tidak boleh kosong',
            'tanggal.required' => 'Tanggal tidak boleh kosong',
            // 'tempat.required' => 'Tempat tidak boleh kosong',
            'perihal.required' => 'Perihal tidak boleh kosong',
            'sifat.required' => 'Sifat tidak boleh kosong',
            'berita.required' => 'Berita tidak boleh kosong',
            'atasan_langsung_1.required' => 'Atasan Langsung 1 tidak boleh kosong',
            'file.required' => 'File tidak boleh kosong',
            'tanda_tangan.required' => 'Tanda Tangan tidak boleh kosong',
        ]);
        // logic store
        // dd($request->all());
        $store = forminternalmemo::store($request->all(), $user_id);

        return redirect()->route('forminternal-memo.create',[
            $user_id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function approve(request $request,$user_id, $id){
        $data = forminternalmemo::find($id);
        if (!isset($data) && empty($data)) {
            return redirect()->route('forminternal-memo.detail',[
                $user_id,
                $id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }

        $approval = forminternalmemo::approve($request->all(),$user_id,$data);
        return redirect()->route('forminternal-memo.detail',[
            $user_id,
            $id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
    public function reject(request $request,$user_id, $id){
        $data = forminternalmemo::find($id);
        if (!isset($data) && empty($data)) {
            return redirect()->route('forminternal-memo.detail',[
                $user_id,
                $id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }
        $approval = forminternalmemo::reject($request->all(),$user_id, $data);
        return redirect()->route('forminternal-memo.detail',[
            $user_id,
            $id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
    public function cetak_pdf($user_id,$id)
    {
    	$user = User::find($user_id);
        $getData = forminternalmemo::leftjoin('users as u_pembuat','u_pembuat.id','form_internal_memo.pembuat')
            ->leftjoin('sub_company as s','s.id','form_internal_memo.subcompany_id')
            ->leftjoin('teams as t','t.id','form_internal_memo.team_id')
            ->leftjoin('sub_company as s2','s2.id','form_internal_memo.subcompany_id_2')
            ->leftjoin('teams as t2','t2.id','form_internal_memo.team_id_2')
            ->leftjoin('users as u_from','u_from.id','form_internal_memo.from_user_id')
            ->leftjoin('users as u_to','u_to.id','form_internal_memo.to_user_id')
            ->leftjoin('users as al_1','al_1.id','form_internal_memo.atasan_langsung_1')
            ->leftjoin('users as al_2','al_2.id','form_internal_memo.atasan_langsung_2')
            ->leftjoin('users as u_penerima','u_penerima.id','form_internal_memo.penerima')
            ->leftjoin('users as u_mengetahui_1','u_mengetahui_1.id','form_internal_memo.mengetahui_1')
            ->leftjoin('users as u_mengetahui_2','u_mengetahui_2.id','form_internal_memo.mengetahui_2')
            ->where('form_internal_memo.id', $id)
            ->selectRaw('form_internal_memo.*,
            u_pembuat.name as name_pembuat,
            u_penerima.name as name_penerima,
            u_mengetahui_1.name as name_mengetahui_1,
            u_mengetahui_2.name as name_mengetahui_2,
            u_from.name as name_from,
            u_to.name as name_to,
            s.name as subcompany,
            s2.name as subcompany_2,
            t.team_name as department,
            t2.team_name as department_2,
            al_1.name as atasan_langsung_1,
            al_2.name as atasan_langsung_2')
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
        if ($getData->is_penerima==0) {
            array_push($activity,"Waiting action $getData->name_penerima (penerima)");
        }elseif($getData->is_penerima==1){
            array_push($activity,"Approved by $getData->name_penerima (penerima)");
        }elseif($getData->is_penerima==2){
            array_push($activity,"Rejected by $getData->name_penerima (penerima)");
        }
        //mengetahui_1
        if ($getData->is_mengetahui_1==0) {
            array_push($activity,"Waiting action $getData->name_mengetahui_1 (atasan langsung 1)");
        }elseif($getData->is_mengetahui_1==1){
            array_push($activity,"Approved by $getData->name_mengetahui_1 (atasan langsung 1)");
        }elseif($getData->is_mengetahui_1==2){
            array_push($activity,"Rejected by $getData->name_mengetahui_1 (atasan langsung 1)");
        }
        //mengetahui_1
        if ($getData->is_mengetahui_2==0) {
            array_push($activity,"Waiting action $getData->name_mengetahui_2 (atasan langsung 2)");
        }elseif($getData->is_mengetahui_2==1){
            array_push($activity,"Approved by $getData->name_mengetahui_2 (atasan langsung 2)");
        }elseif($getData->is_mengetahui_2==2){
            array_push($activity,"Rejected by $getData->name_mengetahui_2 (atasan langsung 2)");
        }

        $can_approve = false;
        $require_signature = false;
        $type_approve = '';
        // $getFormApproval = GeneralSetting::where('company_id', $user->company_id)->first();
        // $getFormApproval = json_decode($getFormApproval->form_approval);

        // if (isset($getFormApproval) && !empty($getFormApproval)) {
        //     foreach ($getFormApproval as $val) {
        //         if (isset($val->type) && !empty($val->type)) {
        //             if ($val->type=='internal_memo') {
        //                 $getFormApproval =$val;
        //             }
        //         }
        //     }
        // }
        // get atasan user
        $permission = EmployeeDetails::where('user_id', $getData->pembuat)->first();
        $permission = json_decode($permission->permission_require, true);

        $disetujui = EmployeeDetails::where('department_id',4)->where('is_atasan',1)->first();
        $disetujui = json_decode($disetujui->user_id,true);

        if ($permission[0]==$user->id && $getData->is_mengetahui_1 == 0 ) {
            // can approve
            $can_approve = true;
            $type_approve ='mengetahui_1';
            $require_signature = true;
        }
        elseif ($permission[1]==$user->id && $getData->is_mengetahui_2 == 0) {
            // can approve
            $can_approve = true;
            $type_approve ='mengetahui_2';
            $require_signature = true;
        }
        if ($user->id==$getData->to_user_id && $getData->is_penerima == 0) {
            $can_approve = true;
            $type_approve ='penerima';
            $require_signature = true;
        }


        $getData->can_approve = $can_approve;
        $getData->type_approve = $type_approve;
        $getData->require_signature = $require_signature;

 
    	$pdf = PDF::loadview('iframe/forminternal-memo/detail-pdf',['data'=>$getData]);
    	return $pdf->stream('laporan-pegawai-pdf.pdf');
    }
}
