<?php

namespace App\Http\Controllers;

use App\formpermintaandana;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Helper\Files;
use App\SubCompany;
use App\User;
use App\Wilayah;
use App\EmployeeDetails;
use App\GeneralSetting;
use Modules\RestAPI\Entities\Department;
use PDF;

class FormPermintaanDanaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail($user_id,$id)
    {
        $user = User::find($user_id);
        $getData = formpermintaandana::leftjoin('users as u_pembuat','u_pembuat.id','form_permintaan_dana.pembuat')
            ->leftjoin('sub_company as s','s.id','form_permintaan_dana.subcompany_id')
            ->leftjoin('users as u_nama','u_nama.id','form_permintaan_dana.user_id')
            ->leftjoin('teams as t','t.id','form_permintaan_dana.team_id')
            ->leftjoin('users as u_disetujui','u_disetujui.id','form_permintaan_dana.disetujui')
            ->leftjoin('users as u_mengetahui_1','u_mengetahui_1.id','form_permintaan_dana.mengetahui_1')
            ->leftjoin('users as u_diperiksa_1','u_diperiksa_1.id','form_permintaan_dana.diperiksa_1')
            ->leftjoin('users as u_diperiksa','u_diperiksa.id','form_permintaan_dana.diperiksa')
            ->leftjoin('users as u_approval_pajak','u_approval_pajak.id','form_permintaan_dana.approval_pajak')
            ->leftjoin('users as u_mengetahui','u_mengetahui.id','form_permintaan_dana.mengetahui')
            ->leftjoin('users as u_disetujui_1','u_disetujui_1.id','form_permintaan_dana.disetujui_1')
            ->where('form_permintaan_dana.id', $id)
            ->selectRaw('form_permintaan_dana.*,
            u_pembuat.name as name_pembuat,
            u_disetujui.name as name_disetujui,
            u_mengetahui_1.name as name_mengetahui_1,
            u_disetujui_1.name as disetujui_1,
            u_mengetahui.name as mengetahui,
            u_diperiksa_1.name as diperiksa_1,
            u_diperiksa.name as name_diperiksa,
            t.team_name as department,
            u_approval_pajak.name as approval_pajak,
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
        if ($getData->is_mengetahui_1==0) {
            array_push($activity,"Waiting action $getData->name_mengetahui_1 (mengetahui)");
        }elseif($getData->is_mengetahui_1==1){
            array_push($activity,"Approved by $getData->name_mengetahui_1 (mengetahui)");
        }elseif($getData->is_mengetahui_1==2){
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

        // get atasan user
        $permission = EmployeeDetails::where('user_id', $getData->pembuat)->first();
        $permission = json_decode($permission->permission_require, true);

        $disetujui = EmployeeDetails::where('department_id',4)->where('is_atasan',1)->first();
        $disetujui = json_decode($disetujui->user_id,true);
        // dd($disetujui);

        if ($disetujui==$user->id && $getData->is_disetujui == 0) {
            // can approve
            $can_approve = true;
            $type_approve ='disetujui';
            $require_signature = true;
        }
        elseif ($permission[0]==$user->id && $getData->is_diperiksa == 0 ) {
            // can approve
            $can_approve = true;
            $type_approve ='diperiksa';
            $require_signature = true;
        }
        elseif ($permission[1]==$user->id && $getData->is_mengetahui_1 == 0) {
            // can approve
            $can_approve = true;
            $type_approve ='mengetahui_1';
            $require_signature = true;
        }

        $getData->can_approve = $can_approve;
        $getData->type_approve = $type_approve;
        $getData->require_signature = $require_signature;
        return view('iframe.formpermintaan-dana.detail', [
            'data'=> $getData,
            'user'=>$user,
            'activity' =>$activity,
            'user_id'=>$user_id
        ]);
    }
    public function create($user_id)
    {
        $user = User::find($user_id);

        // get anak perusahaan
        $subCompany = SubCompany::where('company_id', $user->company_id)->select('name','id')->get();

        // get department
        $department = Department::where('company_id', $user->company_id)->select('team_name','id')->get();

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

        return view('iframe.formpermintaan-dana.create',[
            "user_id"=> $user_id,
            "subCompany"=> $subCompany,
            "data_user"=> $data_user,
            "department"=> $department,
            "atasan1"=>$atasan1,
            "atasan2"=>$atasan2,
        ]);
    }
    public function store(request $request, $user_id){
        $this->validate($request, [
            'subcompany' => 'required',
            'user_id' => 'required',
            'tanggal' => 'required',
            'keperluan' => 'required',
            'nominal' => 'required',
            'terbilang' => 'required',
            'unsur_pph' => 'required',
            'nominal_pph' => 'required',
            'approval_pajak' => 'required',
            'diperiksa_satu' => 'required',
            'tanda_tangan' => 'required',
        ], [
            'subcompany.required' => 'Anak Perusahaan tidak boleh kosong',
            'user_id.required' => 'Nama tidak boleh kosong',
            'tanggal.required' => 'Tanggal tidak boleh kosong',
            'keperluan.required' => 'Keperluan tidak boleh kosong',
            'nominal.required' => 'Nominal tidak boleh kosong',
            'terbilang.required' => 'Terbilang tidak boleh kosong',
            'unsur_pph.required' => 'Unsur PPH tidak boleh kosong',
            'nominal_pph.required' => 'Nominal PPH tidak boleh kosong',
            'approval_pajak.required' => 'Approval Pajak tidak boleh kosong',
            'diperiksa_satu.required' => 'Diperiksa 1 tidak boleh kosong',
            'tanda_tangan.required' => 'Tanda Tangan tidak boleh kosong',
        ]);
        // logic store 
        $store = formpermintaandana::store($request->all(), $user_id);

        return redirect()->route('formpermintaan-dana.create',[
            $user_id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function approve(request $request,$user_id, $id){
        $data = formpermintaandana::find($id);
        if (!isset($data) && empty($data)) {
            return redirect()->route('formpermintaan-dana.detail',[
                $user_id,
                $id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }

        $approval = formpermintaandana::approve($request->all(),$user_id,$data);
        return redirect()->route('formpermintaan-dana.detail',[
            $user_id,
            $id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
    public function reject(request $request,$user_id, $id){
        $data = formpermintaandana::find($id);
        if (!isset($data) && empty($data)) {
            return redirect()->route('formpermintaan-dana.detail',[
                $user_id,
                $id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }
        $approval = formpermintaandana::reject($request->all(),$user_id, $data);
        return redirect()->route('formpermintaan-dana.detail',[
            $user_id,
            $id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }

    public function cetak_pdf($user_id,$id)
    {
        $user = User::find($user_id);
        $getData = formpermintaandana::leftjoin('users as u_pembuat','u_pembuat.id','form_permintaan_dana.pembuat')
            ->leftjoin('sub_company as s','s.id','form_permintaan_dana.subcompany_id')
            ->leftjoin('users as u_nama','u_nama.id','form_permintaan_dana.user_id')
            ->leftjoin('teams as t','t.id','form_permintaan_dana.team_id')
            ->leftjoin('users as u_disetujui','u_disetujui.id','form_permintaan_dana.disetujui')
            ->leftjoin('users as u_mengetahui_1','u_mengetahui_1.id','form_permintaan_dana.mengetahui_1')
            ->leftjoin('users as u_diperiksa_1','u_diperiksa_1.id','form_permintaan_dana.diperiksa_1')
            ->leftjoin('users as u_diperiksa','u_diperiksa.id','form_permintaan_dana.diperiksa')
            ->leftjoin('users as u_approval_pajak','u_approval_pajak.id','form_permintaan_dana.approval_pajak')
            ->leftjoin('users as u_mengetahui','u_mengetahui.id','form_permintaan_dana.mengetahui')
            ->leftjoin('users as u_disetujui_1','u_disetujui_1.id','form_permintaan_dana.disetujui_1')
            ->where('form_permintaan_dana.id', $id)
            ->selectRaw('form_permintaan_dana.*,
            u_pembuat.name as name_pembuat,
            u_disetujui.name as name_disetujui,
            u_mengetahui_1.name as name_mengetahui_1,
            u_disetujui_1.name as disetujui_1,
            u_mengetahui.name as mengetahui,
            u_diperiksa_1.name as diperiksa_1,
            u_diperiksa.name as name_diperiksa,
            t.team_name as department,
            u_approval_pajak.name as approval_pajak,
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
        if ($getData->is_mengetahui_1==0) {
            array_push($activity,"Waiting action $getData->name_mengetahui_1 (mengetahui)");
        }elseif($getData->is_mengetahui_1==1){
            array_push($activity,"Approved by $getData->name_mengetahui_1 (mengetahui)");
        }elseif($getData->is_mengetahui_1==2){
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

        // get atasan user
        $permission = EmployeeDetails::where('user_id', $getData->pembuat)->first();
        $permission = json_decode($permission->permission_require, true);

        $disetujui = EmployeeDetails::where('department_id',4)->where('is_atasan',1)->first();
        $disetujui = json_decode($disetujui->user_id,true);
        // dd($disetujui);

        if ($disetujui==$user->id && $getData->is_disetujui == 0) {
            // can approve
            $can_approve = true;
            $type_approve ='disetujui';
            $require_signature = true;
        }
        elseif ($permission[0]==$user->id && $getData->is_diperiksa == 0 ) {
            // can approve
            $can_approve = true;
            $type_approve ='diperiksa';
            $require_signature = true;
        }
        elseif ($permission[1]==$user->id && $getData->is_mengetahui_1 == 0) {
            // can approve
            $can_approve = true;
            $type_approve ='mengetahui_1';
            $require_signature = true;
        }

        $getData->can_approve = $can_approve;
        $getData->type_approve = $type_approve;
        $getData->require_signature = $require_signature;

    	$pdf = PDF::loadview('iframe/formpermintaan-dana/detail-pdf',['data'=>$getData]);
    	return $pdf->stream('laporan-pegawai-pdf.pdf');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
