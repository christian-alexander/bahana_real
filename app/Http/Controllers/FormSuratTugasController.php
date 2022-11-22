<?php

namespace App\Http\Controllers;

use App\formsurattugas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Traits\Upload;
use App\SubCompany;
use App\User;
use App\Wilayah;
use Modules\RestAPI\Entities\Department;
use App\EmployeeDetails;
use Illuminate\Support\Facades\Storage;
use App\Helper\Files;
use App\GeneralSetting;
use App\Http\Requests\API\APIRequest;
use Modules\RestAPI\Entities\Leave;
use Modules\RestAPI\Entities\LeaveType;
use App\Notifications\LeaveCreate;
use Carbon\Carbon;
use App\TipeCuti;
use App\LeaveCuti;
use App\LeaveDinasLuarKota;
use App\LeaveDinasSementara;
use App\LeaveIjin;
use Froiden\RestAPI\ApiResponse;
use App\Notifications\LeaveApplicationCustom;
use PDF;

class FormSuratTugasController extends Controller
{
    public function detail($user_id,$id)
    {
        $user = User::find($user_id);
        $getData = formsurattugas::leftjoin('users as u_pembuat','u_pembuat.id','form_surat_tugas.pembuat')
            ->leftjoin('sub_company as s','s.id','form_surat_tugas.subcompany_id')
            ->leftjoin('users as u_nama','u_nama.id','form_surat_tugas.user_id')
            ->leftjoin('users as u_name_bertugas','u_name_bertugas.id','form_surat_tugas.nama_bertugas')
            ->leftjoin('teams as t','t.id','form_surat_tugas.team_id')
            ->leftjoin('users as u_mengetahui_1','u_mengetahui_1.id','form_surat_tugas.mengetahui_1')
            ->leftjoin('users as u_mengetahui_2','u_mengetahui_2.id','form_surat_tugas.mengetahui_2')
            ->leftjoin('users as u_acc_atasan_1','u_acc_atasan_1.id','form_surat_tugas.acc_atasan_1')
            ->leftjoin('users as u_acc_atasan_2','u_acc_atasan_2.id','form_surat_tugas.acc_atasan_2')
            ->leftjoin('users as u_penerima','u_penerima.id','form_surat_tugas.penerima')
            ->where('form_surat_tugas.id', $id)
            ->selectRaw('form_surat_tugas.*,
            u_pembuat.name as name_pembuat,
            u_penerima.name as name_penerima,
            u_name_bertugas.name as name_bertugas,
            u_mengetahui_1.name as name_mengetahui_1,
            u_mengetahui_2.name as name_mengetahui_2,
            u_acc_atasan_1.name as acc_atasan_1,
            u_acc_atasan_2.name as acc_atasan_2,
            t.team_name as department,
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
        elseif ($user->id==$getData->nama_bertugas && $getData->is_penerima == 0) {
            $can_approve = true;
            $type_approve ='penerima';
            $require_signature = true;
        }


        $getData->can_approve = $can_approve;
        $getData->type_approve = $type_approve;
        $getData->require_signature = $require_signature;
        return view('iframe.surat-tugas.detail', [
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

        return view('iframe.surat-tugas.create',[
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
            'jabatan_satu'=>'required',
            'nik_satu'=>'required',
            'jabatan_dua'=>'required',
            'nik_dua'=>'required',
            'nama_bertugas'=>'required',
            'rute_awal'=>'required',
            'rute_akhir'=>'required',
            'tanggal_mulai'=>'required',
            'tanggal_selesai'=>'required',
            'estimasi_biaya'=>'required',
            'keperluan' => 'required',
            'acc_atasan_1' => 'required',
            'acc_atasan_2' => 'required',
            'tanda_tangan' => 'required',
        ], [
            'subcompany.required' => 'Anak Perusahaan tidak boleh kosong',
            'user_id.required' => 'Nama tidak boleh kosong',
            'jabatan_satu.required'=>'Jabatan 1 tidak boleh kosong',
            'nik_satu.required'=> 'NIK 1 tidak boleh kosong',
            'jabatan_dua.required'=>'Jabatan 2 tidak boleh kosong',
            'nik_dua.required'=> 'NIK 2 tidak boleh kosong',
            'nama_bertugas.required' =>'Nama Bertugas tidak boleh kosong',
            'rute_awal.required'=>'Rute Awal tidak boleh kosong',
            'rute_akhir.required'=>'Rute Akhir tidak boleh kosong',
            'tanggal_mulai.required'=>'Tanggal Mulai tidak boleh kosong',
            'tanggal_selesai.required'=>'Tanggal Selesai tidak boleh kosong',
            'estimasi_biaya.required'=>'Estimasi Biaya tidak boleh kosong',
            'keperluan.required'=> 'Keperluan tidak boleh kosong',
            'acc_atasan_1.required' => 'ACC Atasan 1 tidak boleh kosong',
            'acc_atasan_2.required' => 'ACC Atasan 2 tidak boleh kosong',
            'tanda_tangan.required' => 'Tanda Tangan tidak boleh kosong',
        ]);
        // logic store 
        $store = formsurattugas::store($request->all(), $user_id);

        return redirect()->route('surat-tugas.create',[
            $user_id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function approve(request $request,$user_id, $id){
        $data = formsurattugas::find($id);
        if (!isset($data) && empty($data)) {
            return redirect()->route('surat_tugas.detail',[
                $user_id,
                $id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }

        $approval = formsurattugas::approve($request->all(),$user_id,$data);
        return redirect()->route('surat-tugas.detail',[
            $user_id,
            $id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
    public function reject(request $request,$user_id, $id){
        $data = formsurattugas::find($id);
        if (!isset($data) && empty($data)) {
            return redirect()->route('surat-tugas.detail',[
                $user_id,
                $id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }
        $approval = formsurattugas::reject($request->all(),$user_id, $data);
        return redirect()->route('surat-tugas.detail',[
            $user_id,
            $id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }

    public function cetak_pdf($user_id,$id)
    {
        $user = User::find($user_id);
        $getData = formsurattugas::leftjoin('users as u_pembuat','u_pembuat.id','form_surat_tugas.pembuat')
            ->leftjoin('sub_company as s','s.id','form_surat_tugas.subcompany_id')
            ->leftjoin('users as u_nama','u_nama.id','form_surat_tugas.user_id')
            ->leftjoin('users as u_name_bertugas','u_name_bertugas.id','form_surat_tugas.nama_bertugas')
            ->leftjoin('teams as t','t.id','form_surat_tugas.team_id')
            ->leftjoin('users as u_mengetahui_1','u_mengetahui_1.id','form_surat_tugas.mengetahui_1')
            ->leftjoin('users as u_mengetahui_2','u_mengetahui_2.id','form_surat_tugas.mengetahui_2')
            ->leftjoin('users as u_acc_atasan_1','u_acc_atasan_1.id','form_surat_tugas.acc_atasan_1')
            ->leftjoin('users as u_acc_atasan_2','u_acc_atasan_2.id','form_surat_tugas.acc_atasan_2')
            ->leftjoin('users as u_penerima','u_penerima.id','form_surat_tugas.penerima')
            ->where('form_surat_tugas.id', $id)
            ->selectRaw('form_surat_tugas.*,
            u_pembuat.name as name_pembuat,
            u_penerima.name as name_penerima,
            u_name_bertugas.name as name_bertugas,
            u_mengetahui_1.name as name_mengetahui_1,
            u_mengetahui_2.name as name_mengetahui_2,
            u_acc_atasan_1.name as acc_atasan_1,
            u_acc_atasan_2.name as acc_atasan_2,
            t.team_name as department,
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
        elseif ($user->id==$getData->nama_bertugas && $getData->is_penerima == 0) {
            $can_approve = true;
            $type_approve ='penerima';
            $require_signature = true;
        }


        $getData->can_approve = $can_approve;
        $getData->type_approve = $type_approve;
        $getData->require_signature = $require_signature;
 
    	$pdf = PDF::loadview('iframe/surat-tugas/detail-pdf',['data'=>$getData]);
    	return $pdf->stream('laporan-pegawai-pdf.pdf');
    }

    public function storeLeave(APIRequest $request)
    {
      $request->validate([
        'leave_type_id' => 'required',
        'leave_date' => 'required|date_format:Y-m-d',
        'leave_date_end' => 'required|after_or_equal:leave_date|date_format:Y-m-d',
      ]);
  
      // check if leave_type_id is ijin
      $checkLeaveType = LeaveType::find($request->leave_type_id);
  
      if ($checkLeaveType->type_name == 'Ijin') {
        $request->validate([
          'alasan_izin' => 'required',
        ]);
      } 
      // // check tipe_cuti_id exist
      $checkTipeCuti = TipeCuti::find($request->tipe_cuti_id);
      if (!isset($checkTipeCuti) && empty($checkTipeCuti)) {
        return response()->json([
            'error' => [
            'status' => 404,
            'message' => 'Tipe cuti not found',
          ]
        ]);
      }
      elseif ($checkLeaveType->type_name == 'Dinas Luar Kota') {
        $request->validate([
          'rute_awal' => 'required',
          'rute_akhir' => 'required',
          'alasan' => 'required',
          'biaya' => 'required',
        ]);
      }
      $user = auth()->user();
  
      // // get this year
      $thisYear = Carbon::now()->format('Y');
      // get approved leave
      $approvedLeave = Leave::leftjoin('leave_types as lt','lt.id','leaves.leave_type_id')
        ->whereIn('leaves.masking_status',['pending','in progress','done'])
        ->where('leaves.leave_type_id',$checkLeaveType->id)
        ->where('leaves.user_id',$user->id)
        ->where('leaves.company_id',$user->company_id)
        ->where(function($query) use ($thisYear){
          $query->whereYear('leaves.leave_date',$thisYear)
          ->orWhereYear('leaves.leave_date',$thisYear);
        })
        ->select('leaves.*','lt.type_name')
        ->get();
  
      $totalLeaveTaken = 0;
      $arr_3_month =[];
      $start_date_request = Carbon::parse($request->leave_date);
      $end_date_request = Carbon::parse($request->leave_date_end);
  
      foreach ($approvedLeave as $val) {
        // parse to carbon object
        $start_date = Carbon::parse($val->leave_date);
        $end_date = Carbon::parse($val->leave_date_end);
  
  
        // check user already apply for leave
        if ($val->type_name=='Ijin' ||$val->type_name=='Cuti' || $val->type_name=='Cuti 3 Bulanan' || $val->type_name=='Cuti Custom') {
          if ($start_date_request->between($start_date, $end_date) || $end_date_request->between($start_date, $end_date)) {
            return response()->json([
              'error' => [
                'status' => 404,
                'message' => 'Anda telah melakukan ijin/cuti untuk tanggal tersebut',
              ]
            ]);
          }
        }
  
        if ($start_date->copy()->format('Y')==$thisYear && $end_date->copy()->format('Y')==$thisYear) {
          $diff = $end_date->copy()->diffInDays($start_date)+1;
          if ($checkLeaveType->type_name =='Cuti 3 Bulanan') {
            $start_month = $start_date->copy()->format('m');
            $end_month = $end_date->copy()->format('m');
            if ($start_month==$end_month) {
              array_push($arr_3_month,[
                "month"=>$start_month,
                "count"=>$diff,
              ]);
            }else{
              $endOfMonth = $start_date->copy()->endOfMonth();
              $diff = $endOfMonth->copy()->diffInDays($start_date)+1;
              array_push($arr_3_month,[
                "month"=>$start_month,
                "count"=>$diff,
              ]);
  
              $startOfMonth =$end_date->copy()->startOfMonth();
              $diff = $end_date->copy()->diffInDays($startOfMonth)+1;
              array_push($arr_3_month,[
                "month"=>$end_month,
                "count"=>$diff,
              ]);
            }
          }
        }else{
          $dateEndOfYear = Carbon::now();
          $dateEndOfYear = $dateEndOfYear->endOfYear();
          $diff = $dateEndOfYear->diffInDays($start_date)+1;
        }
        $totalLeaveTaken = $totalLeaveTaken + $diff;
      }
      if ($checkLeaveType->type_name =='Cuti 3 Bulanan') {
        $startDateMonth = (int)Carbon::parse($request->leave_date)->copy()->format('m');
        $endDateMonth = (int)Carbon::parse($request->leave_date_end)->copy()->format('m');
  
        $one_three= 0;
        $four_six= 0;
        $seven_nine= 0;
        $ten_twelve= 0;
        // for current leave
        if ($startDateMonth!=$endDateMonth){
          $endOfMonth = $start_date_request->copy()->endOfMonth();
          $diff = $endOfMonth->copy()->diffInDays($start_date_request)+1;
          if (in_array($startDateMonth,[1,2,3])) {
            $one_three+=$diff;
          }elseif(in_array($startDateMonth,[4,5,6])){
            $four_six+=$diff;
          }elseif(in_array($startDateMonth,[7,8,9])){
            $seven_nine+=$diff;
          }elseif(in_array($startDateMonth,[10,11,12])){
            $ten_twelve+=$diff;
          }
  
          $startOfMonth =$end_date_request->copy()->startOfMonth();
          $diff = $end_date_request->copy()->diffInDays($startOfMonth)+1;
          if (in_array($endDateMonth,[1,2,3])) {
            $one_three+=$diff;
          }elseif(in_array($endDateMonth,[4,5,6])){
            $four_six+=$diff;
          }elseif(in_array($endDateMonth,[7,8,9])){
            $seven_nine+=$diff;
          }elseif(in_array($endDateMonth,[10,11,12])){
            $ten_twelve+=$diff;
          }
        }else{
          // bug here
          $diff = $end_date_request->diffInDays($start_date_request)+1;
          if (in_array($startDateMonth,[1,2,3])) {
            $one_three+=$diff;
          }elseif(in_array($startDateMonth,[4,5,6])){
            $four_six+=$diff;
          }elseif(in_array($startDateMonth,[7,8,9])){
            $seven_nine+=$diff;
          }elseif(in_array($startDateMonth,[10,11,12])){
            $ten_twelve+=$diff;
          }
        }
        $can_take_leave =true;
        foreach ($arr_3_month as &$val) {
        // date diff
        $dateDiff3month = $end_date_request->diffInDays($start_date_request)+1;
              if (in_array((int)$val['month'],[1,2,3])) {
                $one_three+=$val['count'];
                if ($one_three>$checkLeaveType->no_of_leaves) {
                  $can_take_leave =false;
                }
              }elseif(in_array((int)$val['month'],[4,5,6])){
                $four_six+=$val['count'];
                if ($four_six>$checkLeaveType->no_of_leaves) {
                  $can_take_leave =false;
                }
              }elseif(in_array((int)$val['month'],[7,8,9])){
                $seven_nine+=$val['count'];
                if ($seven_nine>$checkLeaveType->no_of_leaves) {
                  $can_take_leave =false;
                }
              }elseif(in_array((int)$val['month'],[10,11,12])){
                $ten_twelve+=$val['count'];
                if ($ten_twelve>$checkLeaveType->no_of_leaves) {
                  $can_take_leave =false;
                }
              }
        }
      }
      
      // check user still have leave 
      if ($checkLeaveType->type_name =='Cuti 3 Bulanan') {
      if (!$can_take_leave) {
          return response()->json([
            'error' => [
              'status' => 404,
              'message' => 'Jatah ijin anda telah habis',
            ]
          ]);
        }
      }elseif($checkLeaveType->type_name=='Cuti Custom'){
        // check leave taken
        $leave_taken = Leave::leaveTaken($user->id);
        if ($leave_taken['status']!=200) {
          throw new \Exception("Error when try to get data leave taken");
        }
        $leave_taken = $leave_taken['data'][$checkTipeCuti->name];
  
        // add diff now
        $requestStartDate = Carbon::parse($request->leave_date);
        $requestEndDate = Carbon::parse($request->leave_date_end);
        $diff = $requestEndDate->copy()->diffInDays($requestStartDate)+1;
  
        $temp_leave_taken = $leave_taken['leave_taken']+$diff;
        if ($temp_leave_taken>$leave_taken['limit']) {
          return response()->json([
            'error' => [
              'status' => 404,
              'message' => 'Jatah ijin anda telah habis',
            ]
          ]);
        }
        
      }else{
        // add diff now
        $requestStartDate = Carbon::parse($request->leave_date);
        $requestEndDate = Carbon::parse($request->leave_date_end);
        $diff = $requestEndDate->copy()->diffInDays($requestStartDate)+1;
        $totalLeaveTaken += $diff;
        // dd($totalLeaveTaken,$checkLeaveType->no_of_leaves);
        
        if ($totalLeaveTaken>$checkLeaveType->no_of_leaves) {
          return response()->json([
            'error' => [
              'status' => 404,
              'message' => 'Jatah ijin anda telah habis',
            ]
          ]);
        }
      }
      // check leave
      DB::beginTransaction();
      try {
        // create leave
        $model = new Leave;
        $model->user_id = $user->id;
        $model->leave_type_id = $request->leave_type_id;
        $model->leave_date = Carbon::parse($request->leave_date);
        $model->leave_date_end = $request->leave_date_end;
        $model->duration = 'single';
        $model->reason = isset($request->deskripsi) && !empty($request->deskripsi) ? $request->deskripsi : '-';
        $model->status = 'pending';
        $model->save();
  
  
        if ($checkLeaveType->type_name == 'Ijin') {
          // insert into leave_ijins
          $child = new LeaveIjin;
          $child->leave_id = $model->id;
          $child->alasan_ijin = $request->alasan_izin;
          $child->is_sakit = $request->alasan_izin == 'sakit' ? 1 : 0;
          if (isset($request->surat_keterangan_sakit) && !empty($request->surat_keterangan_sakit)) {
            $filename = Files::uploadLocalOrS3($request->surat_keterangan_sakit, "user-leaves/$user->id");
            $child->surat_keterangan_sakit = "user-leaves/$user->id/$filename";
          }
          $child->save();
        } elseif ($checkLeaveType->type_name == 'Cuti' || $checkLeaveType->type_name == 'Cuti 3 Bulanan' || $checkLeaveType->type_name == 'Cuti Custom') {
          // ini adalah cuti tahunan
          // insert into leave_cutis
          $child = new LeaveCuti;
          $child->leave_id = $model->id;
          if ($checkLeaveType->type_name == 'Cuti Custom') {
            $child->kategori_cuti = $request->tipe_cuti_id;
          }
          // get jumlah cuti tersedia
          $limitCuti = $checkLeaveType->no_of_leaves;
          // get jumlah user ini telah cuti
          // $getUserCuti = Leave::join('leave_cutis as lc', 'lc.leave_id', 'leaves.id')
          //   ->count();
          // if ($getUserCuti >= $limitCuti) {
            
          //   // cuti habis
          //   // set is potong gaji = 1
          //   $child->is_potong_gaji = 1;
          // }
          $child->save();
        } elseif ($checkLeaveType->type_name == 'Dinas sementara') {
          // insert into leave_dinas_sementaras
          $child = new LeaveDinasSementara;
          $child->leave_id = $model->id;
          $child->start_hour = $request->jam_mulai;
          $child->end_hour = $request->jam_selesai;
          $child->destination = $request->tujuan_dinas;
          $child->save();
        } elseif ($checkLeaveType->type_name == 'Dinas Luar Kota') {
          // insert into leave_dinas_luar_kotas
          $child = new LeaveDinasLuarKota;
          $child->leave_id = $model->id;
          $child->rute_awal = $request->rute_awal;
          $child->rute_akhir = $request->rute_akhir;
          $child->alasan = $request->alasan;
          $child->biaya = $request->biaya;
          $child->save();
        }
  
        
  
        // $leave->user->notify(new LeaveApplication($leave));
        
        $arr_cc=[];
        // send notif to atasan 1
        // get atasan
        // get employeeDetail
        $getEmployeeDetail = EmployeeDetails::where('user_id', $user->id)->first();
        $arr_permission_require = $getEmployeeDetail->permission_require;
        $arr_permission_require = json_decode($arr_permission_require);
        if (isset($arr_permission_require[0]) && !empty($arr_permission_require[0])) {
  
          // send notif to atasan 1
          $userNotif = User::find($arr_permission_require[0]);
          // $userNotif->notify(new LeaveCreate($model, $user));
          $userNotif->notify(new LeaveCreate($model, $user,false));
          array_push($arr_cc,$userNotif->email);
        }
  
        // send notif to atasan 3
        // check atasan 3 exist
        if (isset($arr_permission_require[2]) && !empty($arr_permission_require[2])) {
            // if exist then send notif
            $userNotif = User::find($arr_permission_require[2]);
            // $userRequest = User::find($model->user_id);
            // $approvedBy = User::find($user->id);
            // $userNotif->notify(new LeaveCreate($model, $userRequest,$approvedBy));
            $requester = User::find($model->user_id);
            $userNotif->notify(new LeaveCreate($model, $requester,false));
            array_push($arr_cc,$userNotif->email);
        }
  
        // send to diri sendiri
        // $userNotif = User::find($arr_permission_require[0]);
        // $user->notify(new LeaveApplicationCustom($model,$arr_cc));
        // CODE UNTUK CEK APAKAH EMAIL YG DIKIRIM/NOTIFICATION YG DIKIRIM ADA ERROR ATAU TIDAK
        // HARUS DI IMPLEMENTASIKAN DI SEMUA CODE YG ADA NOTIFIKASI NYA
        // dd($flagErrorMail);
        DB::commit();
        $flagErrorMail = false;
        try {
          $user->notify(new LeaveApplicationCustom($model,$arr_cc,$user->id));
        } catch (\Throwable $th) {
          $flagErrorMail = true;
        }
        $type_leave = $checkLeaveType->type_name;
        if ($type_leave=='Cuti Custom') {
          $tipeCutiMsg = TipeCuti::find($request->tipe_cuti_id);
          if (isset($tipeCutiMsg) && !empty($tipeCutiMsg)){  
           $type_leave =$tipeCutiMsg->name;
          }
        }
        $msgApproval="";
        if ($type_leave=='Dinas sementara') {
          $msgApproval ='Atasan 1';  
        }else{
          $msgApproval ='Atasan 1, Atasan 2 dan HRD';  
        }
        if ($flagErrorMail) {
          $msg = "Pengajuan $type_leave Berhasil! Menunggu ACC $msgApproval, Email error silahkan hubungi developer";
        }else{
          $msg = "Pengajuan $type_leave Berhasil! Menunggu ACC $msgApproval";
        }
        return ApiResponse::make($msg, [
          'leave' => $model,
          'child' => isset($child) && !empty($child)?$child:null,
        ]);
      } catch (\Throwable $e) {
        DB::rollback();
        return response()->json([
          'error' => [
            'status' => 500,
            'message' => 'Internal server error',
          ]
        ]);
      }
      
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
