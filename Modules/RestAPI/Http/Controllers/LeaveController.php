<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Designation;
use App\EmployeeDetails;
use Froiden\RestAPI\ApiResponse;
use Modules\RestAPI\Entities\Leave;
use Modules\RestAPI\Http\Requests\Leave\IndexRequest;
use Modules\RestAPI\Http\Requests\Leave\CreateRequest;
use Modules\RestAPI\Http\Requests\Leave\UpdateRequest;
use Modules\RestAPI\Http\Requests\Leave\ShowRequest;
use Modules\RestAPI\Http\Requests\Leave\DeleteRequest;
use App\Http\Requests\API\APIRequest;
use App\Helper\Files;
use App\Leave as AppLeave;
use App\LeaveAccomodation;
use App\LeaveActivity;
use App\LeaveCuti;
use App\LeaveDinasLuarKota;
use App\LeaveDinasSementara;
use App\LeaveIjin;
use App\LeavePengeluaran;
use App\Notifications\LeaveApplicationCustom;
use App\Notifications\LeaveApproved;
use App\Notifications\LeaveCreate;
use App\Notifications\LeaveNotification;
use App\SubCompany;
use App\Team;
use App\TipeCuti;
use App\User;
use Carbon\Carbon;
use Froiden\RestAPI\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\Attendance;
use Modules\RestAPI\Entities\LeaveType;

class LeaveController extends ApiBaseController
{
  protected $model = Leave::class;

  protected $indexRequest = IndexRequest::class;
  protected $storeRequest = CreateRequest::class;
  protected $updateRequest = UpdateRequest::class;
  protected $showRequest = ShowRequest::class;
  protected $deleteRequest = DeleteRequest::class;

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
    } elseif ($checkLeaveType->type_name == 'Cuti' || $checkLeaveType->type_name =='Cuti 3 Bulanan') {
      // cuti = cuti tahunan
      // $request->validate([
      //   'tipe_cuti_id' => 'required',
      //   // 'kategori_cuti' => 'required',
      // ]);
      // // check tipe_cuti_id exist
      // $checkTipeCuti = TipeCuti::find($request->tipe_cuti_id);
      // if (!isset($checkTipeCuti) && empty($checkTipeCuti)) {
      //   return response()->json([
      //     'error' => [
      //       'status' => 404,
      //       'message' => 'Tipe cuti not found',
      //     ]
      //   ]);
      // }
      
    } elseif ($checkLeaveType->type_name == 'Cuti Custom') {
      $request->validate([
        'tipe_cuti_id' => 'required',
        // 'kategori_cuti' => 'required',
      ]);
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
    } elseif ($checkLeaveType->type_name == 'Dinas sementara') {
      $request->validate([
        'jam_mulai' => 'required|date_format:H:i',
        'jam_selesai' => 'required|date_format:H:i',
        // 'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        'tujuan_dinas' => 'required',
      ]);
    } elseif ($checkLeaveType->type_name == 'Dinas Luar Kota') {
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
  public function getDetail(APIRequest $request)
  {
    $request->validate([
      'leave_id' => 'required',
      ]);
      $user = auth()->user();
    // get leave
    $getLeave = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
      ->join('users as u', 'u.id', 'leaves.user_id')
      ->join('employee_details as ed', 'ed.user_id', 'u.id')
      ->where('leaves.id', $request->leave_id)
      ->selectRaw('leaves.*,lt.type_name,u.name as requested_by,ed.permission_require')
      ->first();
    if (!isset($getLeave) && empty($getLeave)) {
      return response()->json([
        'error' => [
          'status' => 404,
          'message' => 'Data not found',
        ]
      ]);
    }
    // get last attendance to get timezone
    $last_attendance = Attendance::where('user_id',$user->id)->orderBy('id','desc')->first();
    $timezone = 7;
    if ($last_attendance) {
      $timezone=$last_attendance->clock_in_timezone;
    }
    $getLeave->created_at_with_timezone = Carbon::parse($getLeave->created_at)->addHours($timezone);
    $getLeave->updated_at_with_timezone = Carbon::parse($getLeave->updated_at)->addHours($timezone);
    $getLeave->approved_by_name = $this->data_by_name($getLeave->approved_by);
    $getLeave->rejected_by_name = $this->data_by_name($getLeave->rejected_by, 'rejected');
    $getLeave->formated_status =  ucfirst(str_replace('_', ' ', $getLeave->status));

    // set need approval hrd to false
    $getLeave->need_approval_hrd = false;

    // check if login user can approve this request
    $getLeave->can_approve = false;

    // check if login user was sekretaris
    $getLeave->is_sekretaris = false;
    $getLeave->is_sekretaris_can_add_accomodation = false;

    // check user is sekretaris
    $getEmployeeDetail = EmployeeDetails::where('user_id', $user->id)->first();
    // get jabatan
    $getJabatan = Designation::where('company_id', $getLeave->company_id)
      ->where('name', 'Sekretaris')
      ->first();
    if ($getJabatan && $user->company_id == $getLeave->company_id && $getEmployeeDetail->designation_id == $getJabatan->id) {
      $getLeave->is_sekretaris = true;
    }

    // set getAccomodations
    $getAccomodations = [];

    // get child by type leave
    if ($getLeave->type_name == 'Ijin') {
      // set need approval hrd to true
      $getLeave->need_approval_hrd = true;

      $getChild = LeaveIjin::where('leave_id', $getLeave->id)->first();
      if (isset($getChild->surat_keterangan_sakit) && !empty($getChild->surat_keterangan_sakit)) {
        $getChild->surat_keterangan_sakit = asset_url_local_s3($getChild->surat_keterangan_sakit);
      } else {
        $getChild->surat_keterangan_sakit = null;
      }
    } elseif ($getLeave->type_name == 'Cuti' || $getLeave->type_name == 'Cuti 3 Bulanan' || $getLeave->type_name == 'Cuti Custom') {
      // set need approval hrd to true
      $getLeave->need_approval_hrd = true;

      if ($getLeave->type_name == 'Cuti Custom') {
        $getChild = LeaveCuti::leftJoin('tipe_cutis as tc', 'tc.id', 'leave_cutis.kategori_cuti')
          ->where('leave_cutis.leave_id', $getLeave->id)
          ->where('tc.company_id', $getLeave->company_id)
          ->selectRaw('leave_cutis.*,tc.name as tipe_cuti')
          ->first();
      }else{
        $getChild = LeaveCuti::where('leave_id', $getLeave->id)
          ->selectRaw('*')
          ->first();
        $getChild->tipe_cuti = $getLeave->type_name;
      }
    } elseif ($getLeave->type_name == 'Dinas sementara') {
      
      $getChild = LeaveDinasSementara::where('leave_id', $getLeave->id)->first();
      // check if leave status is approved atasan dua
      $getLeave->dinas_mulai = false;
      // if dinas sementara dinas begin when approved atasan satu
      // if ($getLeave->status == 'approved_atasan_satu') {
      //   // this request has begin
      //   $getLeave->dinas_mulai = true;
      //   // if is_done = 1 then perjalanan dinas selesai
      //   if ($getChild->is_done == 1) {
      //     $getLeave->dinas_mulai = false;
      //   }
      // }
    } elseif ($getLeave->type_name == 'Dinas Luar Kota') {
      // set need approval hrd to true
      $getLeave->need_approval_hrd = true;
      $getChild = LeaveDinasLuarKota::where('leave_id', $getLeave->id)->first();
      $getLeave->dinas_mulai = false;
      // if ($getLeave->status == 'approved_atasan_dua') {
      //   if ($getChild->is_approved_hrd == 1) {
      //     // this request has begin
      //     $getLeave->dinas_mulai = true;
      //     // if is_done = 1 then perjalanan dinas selesai
      //     if ($getChild->is_done == 1) {
      //       $getLeave->dinas_mulai = false;
      //     }
      //   } elseif ($getChild->is_approved_hrd == null) {
      //     // check user is hello@bahana.com
      //     if ($user->isAdmin($user->id) && $user->company_id == $getLeave->company_id) {
      //       $getLeave->can_approve = true;
      //     }
      //   }
      // }
      // check if this request need accomodation
      if ($getChild->butuh_akomodasi == 1) {
        // this user can add note and file 
        $getLeave->is_sekretaris_can_add_accomodation = true;
      }
      // get accomodations
      $getAccomodations = LeaveAccomodation::leftjoin('users as u', 'u.id', 'leave_accomodations.created_by')
        ->where('leave_dinas_luar_kotas_id', $getChild->id)
        ->selectRaw('leave_accomodations.*,u.name as created_by_user')
        ->get();
      foreach ($getAccomodations as $val) {
        $val->file = asset_url_local_s3($val->file);
      }
      if (!isset($getAccomodations) && empty($getAccomodations)) {
        $getAccomodations = [];
      }
    }
    $getLeave->child = $getChild;

    // check if this user can approve this data 
    $arr_permission_require = $getLeave->permission_require;
    $arr_permission_require = json_decode($arr_permission_require);
    for ($i = 0; $i < count($arr_permission_require); $i++) {
      // check status
      if ($getLeave->status == 'pending') {
        if (isset($arr_permission_require[0]) && !empty($arr_permission_require[0])) {
          if ($arr_permission_require[0] == $user->id) {
            // approval tingkat 1
            $getLeave->can_approve = true;
          }
        }
      } elseif ($getLeave->status == 'approved_atasan_satu') {
        if ($getLeave->type_name == 'Dinas sementara') {
          // this request has begin
          $getLeave->dinas_mulai = true;
          // if is_done = 1 then perjalanan dinas selesai
          if ($getChild->is_done == 1) {
            $getLeave->dinas_mulai = false;
          }
        }else{
          if (isset($arr_permission_require[1]) && !empty($arr_permission_require[1])) {
            if ($arr_permission_require[1] == $user->id) {
                // approval tingkat 2
                $getLeave->can_approve = true;
            }
          }
        }
        // overide can approve
        // check if masking status was done
        if ($getLeave->masking_status=='done') {
          // set can approve to false
          $getLeave->can_approve = false;
        }
      }elseif ($getLeave->status == 'approved_atasan_dua') {
      //   // check if this request wasnt dinas luar kota
      //   if ($getLeave->type_name != 'Dinas Luar Kota') {
      //     // approval tingkat 3
      //     if ($arr_permission_require[2] == $user->id) {
      //       // approval tingkat 3
      //       $getLeave->can_approve = true;
      //     }
      //   }
      // section for HRD
      // check if login user is hrd
      if ($getLeave->type_name == 'Ijin' || $getLeave->type_name == 'Cuti' || $getLeave->type_name =='Cuti 3 Bulanan' || $getLeave->type_name == 'Cuti Custom') {
        if ($getChild->is_approved_hrd == 1) {
            $getLeave->can_approve = false;
        } elseif ($getChild->is_approved_hrd == null) {
          // check if login user was HRD
          if (Leave::checkHRD($user->employeeDetail->department_id) && $user->company_id == $getLeave->company_id && $user->employeeDetail->is_atasan == 1) {
            $getLeave->can_approve = true;
          }
        }
      }elseif($getLeave->type_name == 'Dinas Luar Kota'){
        if ($getChild->is_approved_hrd == 1) {
          // this request has begin
          $getLeave->dinas_mulai = true;
          // if is_done = 1 then perjalanan dinas selesai
          if ($getChild->is_done == 1) {
            $getLeave->dinas_mulai = false;
          }
        } elseif ($getChild->is_approved_hrd == null) {
          // check if login user was HRD
          if (Leave::checkHRD($user->employeeDetail->department_id) && $user->company_id == $getLeave->company_id && $user->employeeDetail->is_atasan == 1) {
            $getLeave->can_approve = true;
          }
        }
      }
      // else{
      //   // INI SALAH SEHARUSNYA PAS APPROVE
      //   // send notif to HRD
      //   // send notif to atasan 1
      //   // get user in HRD
      //   $getMemberHRD = EmployeeDetails::join('teams as t','t.id','employee_details.department_id')
      //     ->where('t.is_hrd',1)
      //     ->where('employee_details.company_id',$user->company_id)
      //     ->selectRaw('employee_details.*')
      //     ->get();
      //     foreach ($getMemberHRD as $val) {
      //       $userNotif = User::find($getMemberHRD->user_id);
      //       $userNotif->notify(new LeaveApproved($getLeave, $user));
      //     }
      // }
      }
    }
    // get activity
    $getActivity = LeaveActivity::join('users as u', 'u.id', 'leave_activities.triggered_by')
      ->where('leave_activities.leave_id', $getLeave->id)
      ->selectRaw('leave_activities.*,u.name as user_name,u.image')
      ->orderBy('created_at', 'DESC')
      ->get();
    foreach ($getActivity as $val) {
      if (empty($val->image)) {
        $val->image = null;
      } else {
        $val->image = asset_url_local_s3('user-uploads/avatar/' . $val->image);
      }
    }

    return ApiResponse::make('Detail Leave', [
      'data' => $getLeave,
      'activity' => $getActivity,
      'accomodations' => $getAccomodations
    ]);
  }

  public function setApproved($data, $dataBefore)
  {
    $dataBefore = json_decode($dataBefore);
    if (empty($dataBefore)) {
      $dataBefore = [];
    }
    array_push($dataBefore, $data);
    return json_encode($dataBefore);
  }
  public function approveLeave(APIRequest $request)
  {
    $request->validate([
      'leave_id' => 'required',
    ]);
    $user = auth()->user();
    // get leave
    $getLeave = Leave::find($request->leave_id);
    if (!isset($getLeave) && empty($getLeave)) {
      return response()->json([
        'error' => [
          'status' => 404,
          'message' => 'Data not found',
        ]
      ]);
    }
    try {
      $approve = Leave::approveLeave($getLeave->id);
      $approve = $approve->getData(true);
      if (isset($approve['error']) && !empty($approve['error'])) {
        $msg = $approve['error']['message'];
        return response()->json([
          'error' => [
            'status' => 500,
            'message' => $msg,
          ]
        ]);
      }
      return ApiResponse::make($approve['success']['message'], [
        'leave' => $approve['success']['data']['leave'],
      ]);
    } catch (\Throwable $e) {
      return response()->json([
        'error' => [
          'status' => 500,
          'message' => 'Internal server error',
        ]
      ]);
    }
  }
  public function rejectLeave(APIRequest $request)
  {
    $request->validate([
      'leave_id' => 'required',
      'reason' => 'required',
    ]);
    try {
      $reject = Leave::rejectLeave($request);
      $reject = $reject->getData(true);
      if (isset($reject['error']) && !empty($reject['error'])) {
        $msg = $reject['error']['message'];
        return response()->json([
          'error' => [
            'status' => 500,
            'message' => $msg,
          ]
        ]);
      }
      return ApiResponse::make('Leave rejected', [
        'leave' => $reject['success']['data']['leave'],
      ]);
    } catch (\Throwable $e) {
      return response()->json([
        'error' => [
          'status' => 500,
          'message' => 'Internal server error',
        ]
      ]);
    }
  }
  public function data_by_name($data, $for = 'approval')
  {
    if ($for == 'approval') {
      $arr = \json_decode($data);
      $arr_output = [];
      if (!empty($arr)) {
        if (count($arr) > 0) {
          // select from user
          foreach ($arr as $val) {
            $getUser = User::find($val);
            if(isset($getUser->name)){
              array_push($arr_output, $getUser->name);
            }
          }
        }
      }
      return $arr_output;
    } elseif ($for == 'rejected') {
      $output = '';
      if (isset($data) && !empty($data)) {
        $getUser = User::find($data);
        if($getUser){
          $output = $getUser->name;
        }
      }
      return $output;
    }
  }
  public function dinasToDone(APIRequest $request)
  {
    $request->validate([
      'leave_id' => 'required',
    ]);
    DB::beginTransaction();
    try {
      $user = auth()->user();
      $getLeave = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
        ->whereIn('lt.type_name', ['Dinas sementara', 'Dinas Luar Kota'])
        ->where('leaves.id', $request->leave_id)
        ->selectRaw('leaves.*,lt.type_name')
        ->first();
      if (!isset($getLeave) && empty($getLeave)) {
        DB::rollback();
        return response()->json([
          'error' => [
            'status' => 404,
            'message' => 'Data not found',
          ]
        ]);
      }
      // check only requester can using this function
      if ($user->id != $getLeave->user_id) {
        DB::rollback();
        return response()->json([
          'error' => [
            'status' => 404,
            'message' => 'Only user who request this leave can mark to done',
          ]
        ]);
      }

      if ($getLeave->type_name == 'Dinas Luar Kota') {
        $getDinasLuarKota = LeaveDinasLuarKota::where('leave_id', $getLeave->id)->first();
        // check just data with status approved can be using this function
        if ($getDinasLuarKota->is_approved_hrd == 0 || $getDinasLuarKota->is_approved_hrd == null) {
          DB::rollback();
          return response()->json([
            'error' => [
              'status' => 501,
              'message' => 'Only approved data can be mark to done',
            ]
          ]);
        }
        if ($getDinasLuarKota->is_done == 1) {
          DB::rollback();
          return response()->json([
            'error' => [
              'status' => 501,
              'message' => 'Someone already take action for this request',
            ]
          ]);
        }
        $getDinasLuarKota->is_done = 1;
        $getDinasLuarKota->done_at = Carbon::now();
        $getDinasLuarKota->save();

        $getLeave->masking_status = 'done';
        $getLeave->save();

        LeaveActivity::logActivityMarkToDone($getLeave->id, $user);

        DB::commit();
        return ApiResponse::make('Dinas luar kota completed successfully', [
          'leave' => $getLeave,
          'dinasLuarKota' => $getDinasLuarKota,
        ]);
      } else {
        // dinas sementara
        // check just dataa with status approved atasan satu can be using this function
        if ($getLeave->status != 'approved_atasan_satu') {
          DB::rollback();
          return response()->json([
            'error' => [
              'status' => 501,
              'message' => 'Only approved data can be mark to done',
            ]
          ]);
        }
        $getDinasSementara = LeaveDinasSementara::where('leave_id', $getLeave->id)->first();
        if ($getDinasSementara->is_done == 1) {
          DB::rollback();
          return response()->json([
            'error' => [
              'status' => 501,
              'message' => 'Someone already take action for this request',
            ]
          ]);
        }
        $getDinasSementara->is_done = 1;
        $getDinasSementara->done_at = Carbon::now();
        $getDinasSementara->save();

        $getLeave->masking_status = 'done';
        $getLeave->save();

        LeaveActivity::logActivityMarkToDone($getLeave->id, $user);

        DB::commit();
        return ApiResponse::make('Dinas sementara completed successfully', [
          'leave' => $getLeave,
          'dinasSementara' => $getDinasSementara,
        ]);
      }
      DB::rollback();
      return response()->json([
        'error' => [
          'status' => 501,
          'message' => 'Only dinas luar kota and dinas sementara can using this request',
        ]
      ]);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json([
        'error' => [
          'status' => 500,
          'message' => 'Internal server error',
        ]
      ]);
    }
  }
  // khusus dinas luar kota
  public function butuhAkomodasi(APIRequest $request)
  {
    $request->validate([
      'leave_id' => 'required',
    ]);
    
    DB::beginTransaction();
    try {
      $user = auth()->user();
      $getLeave = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
        ->where('lt.type_name', 'Dinas Luar Kota')
        ->where('leaves.id', $request->leave_id)
        ->selectRaw('leaves.*')
        ->first();
      if (!isset($getLeave) && empty($getLeave)) {
        DB::rollback();
        return response()->json([
          'error' => [
            'status' => 404,
            'message' => 'Data not found',
          ]
        ]);
      }
      // check only requester can using this function
      if ($user->id != $getLeave->user_id) {
        DB::rollback();
        return response()->json([
          'error' => [
            'status' => 404,
            'message' => 'Only user who request this leave can using this function',
          ]
        ]);
      }
      // get child
      $getDinasLuarKota = LeaveDinasLuarKota::where('leave_id', $getLeave->id)->first();
      // check just data with status approved can be using this function
      if (
        $getDinasLuarKota->is_approved_hrd == 0 || $getDinasLuarKota->is_approved_hrd == null
      ) {
        DB::rollback();
        return response()->json([
          'error' => [
            'status' => 501,
            'message' => 'Only approved data can be using this function',
          ]
        ]);
      }
      if ($getDinasLuarKota->is_done == 1) {
        DB::rollback();
        return response()->json([
          'error' => [
            'status' => 501,
            'message' => 'Only unfinished request can using this function',
          ]
        ]);
      }
      if ($getDinasLuarKota->butuh_akomodasi == 1) {
        DB::rollback();
        return response()->json([
          'error' => [
            'status' => 501,
            'message' => 'You already take action for this request',
          ]
        ]);
      }
      $getDinasLuarKota->butuh_akomodasi = 1;
      $getDinasLuarKota->save();

      LeaveActivity::logActivityNeedAccommodation($getLeave->id, $user);

      DB::commit();
      return ApiResponse::make('Successfully to require accommodation', [
        'leave' => $getLeave,
        'getDinasLuarKota' => $getDinasLuarKota,
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
  public function createPengeluaran(APIRequest $request)
  {
    $request->validate([
      'leave_id' => 'required',
      'nominal' => 'required|numeric',
      'description' => 'required',
      'image' => 'required',
    ]);
    $user = auth()->user();

    // get data leave and child
    $getLeave = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
      ->join('leave_dinas_luar_kotas as ldlk', 'ldlk.leave_id', 'leaves.id')
      ->where('lt.type_name', 'Dinas Luar Kota')
      ->where('leaves.id', $request->leave_id)
      ->selectRaw('leaves.*,ldlk.id as leave_dinas_luar_kota_id,ldlk.is_done,ldlk.is_approved_hrd,ldlk.is_approved_hrd')
      ->first();
    if (!isset($getLeave) && empty($getLeave)) {
      return response()->json([
        'error' => [
          'status' => 404,
          'message' => 'Data not found',
        ]
      ]);
    }

    // check only Sekretaris can access this function
    $getJabatan = Designation::where('company_id', $getLeave->company_id)
      ->where('name', 'Sekretaris')
      ->first();
    if ($user->company_id != $getLeave->company_id || $user->employeeDetail->designation_id != $getJabatan->id) {
      return response()->json([
        'error' => [
          'status' => 401,
          'message' => 'Only sekretaris can using this function',
        ]
      ]);
    }
    DB::beginTransaction();
    try {
      // check only approved data can using this function
      if (
        $getLeave->is_approved_hrd == 0 || $getLeave->is_approved_hrd == null
      ) {
        DB::rollback();
        return response()->json([
          'error' => [
            'status' => 501,
            'message' => 'Only approved data can be using this function',
          ]
        ]);
      }

      // check just leave with status is_done=0 can using this function 
      if ($getLeave->is_done == 1) {
        DB::rollback();
        return response()->json([
          'error' => [
            'status' => 501,
            'message' => 'Only unfinished request can using this function',
          ]
        ]);
      }
      // insert into leave_pengeluarans
      $leave_pengeluaran = new LeavePengeluaran;
      $leave_pengeluaran->leave_dinas_luar_kotas_id = $getLeave->leave_dinas_luar_kota_id;
      $leave_pengeluaran->nominal = $request->nominal;
      $leave_pengeluaran->description = $request->description;
      if ($request->hasFile('image')) {
        $filename = Files::uploadLocalOrS3($request->image, "user-leaves/$user->id");
        $leave_pengeluaran->image = "user-leaves/$user->id/$filename";
      }
      $leave_pengeluaran->save();
      DB::commit();
      return ApiResponse::make('Successfully added expenses', [
        'leave' => $getLeave,
        'leave_pengeluaran' => $leave_pengeluaran,
      ]);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json([
        'error' => [
          'status' => 500,
          'message' => 'Internal server error',
        ]
      ]);
    }
  }

  public function array_search_partial($arr, $keyword) {
    $arr_idx = [];
    foreach($arr as $index => $string) {
        if (preg_match("/{$keyword}/i", $string) >0)
            array_push($arr_idx,$index);
    }
    return $arr_idx;
}
  public function getList(APIRequest $request)
  {
    $user = auth()->user();
    $employeeDetail = $user->employeeDetail;

    $getLeaves = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
      ->selectRaw('leaves.*, lt.type_name')
      ->orderBy('leaves.created_at', 'desc');

    if (isset($request->start_date) && !empty($request->start_date)) {
      $getLeaves = $getLeaves->whereDate('leaves.created_at', '>=', $request->start_date);
    }
    if (isset($request->end_date) && !empty($request->end_date)) {
      $getLeaves = $getLeaves->whereDate('leaves.created_at', '<=', $request->end_date);
    }
    if (isset($request->user_id) && !empty($request->user_id)) {
      if ($request->user_id == "bawahan") {
        // check if user was HRD (admin)
        if (!Leave::checkHRD($user->employeeDetail->department_id)) {
          $employee = EmployeeDetails::where("permission_require", "LIKE", "%\"" . $user->id . "\"%")->pluck("user_id");
          $user_id_bawahan = json_decode($employee);
          //dd($user_id_bawahan);
          $getLeaves = $getLeaves->whereIn('leaves.user_id', $employee); 
        }else{
          // hrd section here
          // get subcompany
          $subCompany = json_decode($employeeDetail->permission);
          $idx = $this->array_search_partial($subCompany,'subcompany');
          
          $employee = EmployeeDetails::select('*');
          $arr_subcompany=[];
          foreach ($idx as $key => $idx_company) {
            // get subcompany
            $subcompany = str_replace('subcompany.','',$subCompany[$idx_company]);
            $subcompany = str_replace('_',' ',$subcompany);
            $subcompany = SubCompany::where('name','like',"%$subcompany%")->first();

            if(isset($subcompany)) {
              array_push($arr_subcompany,$subcompany->id);
            }

          }
          $employee =$employee->whereIn("sub_company_id", $arr_subcompany);
          $employee = $employee->pluck("user_id");
          $getLeaves = $getLeaves->whereIn('leaves.user_id', $employee); 
        }
      } else {
        $getLeaves = $getLeaves->where('leaves.user_id', $request->user_id);
      }
    }
    else{
        $getLeaves = $getLeaves->where('leaves.user_id', $user->id);
    }

    if (isset($request->masking_status) && !empty($request->masking_status)) {
      if ($request->masking_status=='in progress') {
        if (isset($request->waiting_for) && !empty($request->waiting_for)) {
          if ($request->waiting_for=='atasan 2') {
            $getLeaves = $getLeaves->where('leaves.status', 'approved_atasan_satu');
          }elseif ($request->waiting_for=='atasan 3') {
            $getLeaves = $getLeaves->where('leaves.status', 'approved_atasan_dua');
          }else{
            $getLeaves = $getLeaves->where('leaves.masking_status', $request->masking_status);
          }
        }else{
          $getLeaves = $getLeaves->where('leaves.masking_status', $request->masking_status);
        }
      }
      $getLeaves = $getLeaves->where('leaves.masking_status', $request->masking_status);
    }

    if (isset($request->leave_type_id) && !empty($request->leave_type_id)) {
      $getLeaves = $getLeaves->where('leave_type_id', $request->leave_type_id);
    }
    if (isset($request->limit) && !empty($request->limit)) {
      $getLeaves = $getLeaves->limit($request->limit);
    }
    if (isset($request->offset) && !empty($request->offset)) {
      $getLeaves = $getLeaves->offset($request->offset);
    }
    $getLeaves = $getLeaves->get();

    foreach ($getLeaves as $key => &$getLeave) {
      $waiting_approval_hrd = false;

      $getLeave->approved_by_name = $this->data_by_name($getLeave->approved_by);
      $getLeave->rejected_by_name = $this->data_by_name($getLeave->rejected_by, 'rejected');
      $getLeave->formated_status =  ucfirst(str_replace('_', ' ', $getLeave->status));
      // $getLeave->user = User::find($getLeave->user_id);
      $getLeave->user = DB::table('users')->find($getLeave->user_id);

      // set need approval hrd to false
      $getLeave->need_approval_hrd = false;

      // get child by type leave
      if ($getLeave->type_name == 'Ijin') {
        $getChild = LeaveIjin::where('leave_id', $getLeave->id)->first();
        if (empty($getChild->is_approved_hrd)) {
          $waiting_approval_hrd = true;
        }
      } elseif ($getLeave->type_name == 'Cuti' || $getLeave->type_name =='Cuti 3 Bulanan' || $getLeave->type_name == 'Cuti Custom') {
        // $getChild = LeaveCuti::where('leave_id', $getLeave->id)->first();
        $getChild = LeaveCuti::leftJoin('tipe_cutis as tc', 'tc.id', 'leave_cutis.kategori_cuti')
          ->where('leave_cutis.leave_id', $getLeave->id)
          // ->where('tc.company_id', $getLeave->company_id)
          ->selectRaw('leave_cutis.*,tc.name as tipe_cuti')
          ->first();
        if (empty($getChild->is_approved_hrd)) {
          $waiting_approval_hrd = true;
        }
      } elseif ($getLeave->type_name == 'Dinas sementara') {
        $getChild = LeaveDinasSementara::where('leave_id', $getLeave->id)->first();
      } elseif ($getLeave->type_name == 'Dinas Luar Kota') {
        // set need approval hrd to true
        $getLeave->need_approval_hrd = true;

        $getChild = LeaveDinasLuarKota::where('leave_id', $getLeave->id)->first();
        if (empty($getChild->is_approved_hrd)) {
          $waiting_approval_hrd = true;
        }
      }
      $getLeave->child = $getChild;
      if (isset($request->waiting_for) && !empty($request->waiting_for)) {
        if ($request->waiting_for=='hrd') {
          
          if (!$waiting_approval_hrd) {
            $getLeaves->forget($key);
            // unset($getLeaves[$key]);
          }
        }
      }
    }

    return ApiResponse::make('Get users leave success', [
      'leave' => $getLeaves
    ]);
  }
  public function sekretarisAddAccomodation(APIRequest $request)
  {
    $request->validate([
      'leave_id' => 'required',
      'note' => 'required',
    ]);
    $user = auth()->user();
    // check leave exist
    $getLeave = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
      ->where('leaves.id', $request->leave_id)
      ->selectRaw('leaves.*,lt.type_name')
      ->first();
    if (!isset($getLeave) && empty($getLeave)) {
      return response()->json([
        'error' => [
          'status' => 404,
          'message' => 'Data not found',
        ]
      ]);
    }
    // check if this login user was sekretaris
    $getEmployeeDetail = EmployeeDetails::where('user_id', $user->id)->first();
    // get jabatan
    $getJabatan = Designation::where('company_id', $getLeave->company_id)
      ->where('name', 'Sekretaris')
      ->first();
    if ($user->company_id != $getLeave->company_id || $getEmployeeDetail->designation_id != $getJabatan->id) {
      return response()->json([
        'error' => [
          'status' => 401,
          'message' => 'Only sekretaris can using this function',
        ]
      ]);
    }
    // check if this leave was dinas luar kota
    if ($getLeave->type_name != 'Dinas Luar Kota') {
      return response()->json([
        'error' => [
          'status' => 501,
          'message' => 'Only leave id for dinas luar kota that can use this function',
        ]
      ]);
    }
    // get child
    $getChild = LeaveDinasLuarKota::where('leave_id', $getLeave->id)
      ->first();

    // check if this data butuh_akomodasi == 1
    if ($getChild->butuh_akomodasi != 1) {
      return response()->json([
        'error' => [
          'status' => 501,
          'message' => 'Only data that requires accommodation can use this function',
        ]
      ]);
    }
    DB::beginTransaction();
    try {
      $model = new LeaveAccomodation;
      $model->leave_dinas_luar_kotas_id = $getChild->id;
      $model->note = $request->note;
      if (isset($request->file) && !empty($request->file)) {
        $filename = Files::uploadLocalOrS3($request->file, "user-leaves/$user->id");
        $model->file = "user-leaves/$user->id/$filename";
      }
      $model->created_by = $user->id;
      $model->save();
      DB::commit();
      return ApiResponse::make('Successfully added accomodations', [
        'leave' => $getLeave,
        'accomodation' => $model,
      ]);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json([
        'error' => [
          'status' => 500,
          'message' => 'Internal server error',
        ]
      ]);
    }
  }
  public function checkMyLeave(APIRequest $request)
  {
    $user = auth()->user();
    $now = $request->date;
    // $now = Carbon::now()->format('Y-m-d');
    // get leave where status approved_atasan_satu (temporary) (final)
    $getLeaveToday = Leave::join('leave_types as lt', 'leaves.leave_type_id', 'lt.id')
      ->where('leaves.user_id', $user->id)
      // ->where('status','approved_atasan_satu')
      // ->whereNotIn('status',['pending','rejected_atasan_satu','rejected_atasan_dua','rejected_atasan_tiga'])
      ->selectRaw('leaves.*,lt.type_name')
      ->get();
    // check if dinas sementara
    foreach ($getLeaveToday as $key => &$val) {
      if ($now >= $val->leave_date->format('Y-m-d') && $now <= $val->leave_date_end->format('Y-m-d')) {
        // okey
        // temporaray didnt need to check
        // if ($val->type_name == 'Dinas Luar Kota') {
        //   // check dinas luar kota
        //   $checkDinas = LeaveDinasLuarKota::where('leave_id', $val->id)
        //     ->where('is_approved_hrd', 0)
        //     ->count();
        //   if ($checkDinas > 0) {
        //     // data rejected
        //     unset($getLeaveToday[$key]);
        //   }
        // }
        // else if($val->type_name == 'Dinas sementara'){
        //   $checkDinas = LeaveDinasSementara::where('leave_id', $val->id)->first();
        //   $datetime_start = date("Y-m-d H:i", strtotime(date("Y-m-d", strtotime($val->leave_date))." ".$checkDinas->start_hour));
        //   $datetime_end = date("Y-m-d H:i", strtotime(date("Y-m-d", strtotime($val->leave_date_end))." ".$checkDinas->end_hour));
        //   //dd($val->leave_date." ".$checkDinas->start_hour);
        //   $timeNow = date("Y-m-d H:i", strtotime("NOW +7 Hours"));
        //   $ijinAktif = false;
        //   if($timeNow >= $datetime_start && $timeNow <= $datetime_end){
        //     $ijinAktif = true;
        //   }
        //   $val->start_hour = $checkDinas->start_hour;
        //   $val->end_hour = $checkDinas->end_hour;
        //   if(!$ijinAktif){
        //     unset($getLeaveToday[$key]);
        //   }
        // }
        // if($val->type_name == 'Dinas sementara'){
        //     $checkDinas = LeaveDinasSementara::where('leave_id', $val->id)->first();
        //     $datetime_start = date("Y-m-d H:i", strtotime(date("Y-m-d", strtotime($val->leave_date))." ".$checkDinas->start_hour));
        //     $datetime_end = date("Y-m-d H:i", strtotime(date("Y-m-d", strtotime($val->leave_date_end))." ".$checkDinas->end_hour));
        //     //dd($val->leave_date." ".$checkDinas->start_hour);
        //     $timeNow = date("Y-m-d H:i", strtotime("NOW +7 Hours"));
        //     $ijinAktif = false;
        //     if($timeNow >= $datetime_start && $timeNow <= $datetime_end){
        //       $ijinAktif = true;
        //     }
        //     $val->start_hour = $checkDinas->start_hour;
        //     $val->end_hour = $checkDinas->end_hour;
        //     if(!$ijinAktif){
        //       unset($getLeaveToday[$key]);
        //     }
        //   }
      } else {
        // data rejected
        unset($getLeaveToday[$key]);
      }
    }
    $have_leave = false;
    if (count($getLeaveToday)> 0) {
      $have_leave = true;
      // $getLeaveToday =  $getLeaveToday->first();
      // param need type_name
      $this->getChildLeave($getLeaveToday);
    }
    
    return ApiResponse::make('My leave list', [
      'have_leave' => $have_leave,
      'leave' => $getLeaveToday->values(),
    ]);
  }
  public function getChildLeave($getLeaves){
      $getChild = null;
      foreach ($getLeaves as $getLeave) {
        // get child by type leave
        if ($getLeave->type_name == 'Ijin') {
          $getChild = LeaveIjin::where('leave_id', $getLeave->id)->first();
          if (isset($getChild->surat_keterangan_sakit) && !empty($getChild->surat_keterangan_sakit)) {
            $getChild->surat_keterangan_sakit = asset_url_local_s3($getChild->surat_keterangan_sakit);
          } else {
            $getChild->surat_keterangan_sakit = null;
          }
        } elseif ($getLeave->type_name == 'Cuti' || $getLeave->type_name =='Cuti 3 Bulanan' || $getLeave->type_name == 'Cuti Custom') {
          $getChild = LeaveCuti::leftJoin('tipe_cutis as tc', 'tc.id', 'leave_cutis.kategori_cuti')
            ->where('leave_cutis.leave_id', $getLeave->id)
            // ->where('tc.company_id', $getLeave->company_id)
            ->selectRaw('leave_cutis.*,tc.name as tipe_cuti')
            ->first();
            // if ($checkLeaveType->type_name == 'Cuti Custom') {
            //   $child->kategori_cuti = $request->tipe_cuti_id;
            // }
        } elseif ($getLeave->type_name == 'Dinas sementara') {
          $getChild = LeaveDinasSementara::where('leave_id', $getLeave->id)->first();
        } elseif ($getLeave->type_name == 'Dinas Luar Kota') {
          $getChild = LeaveDinasLuarKota::where('leave_id', $getLeave->id)->first();
        }
      }
      $getLeave->child = $getChild;
    }
    public function myLeave(){
        try {
            $user = auth()->user();
            $leaveTaken = AppLeave::leaveTaken($user->id);
            if ($leaveTaken['status']==200) {
              return ApiResponse::make('Leave remaining', $leaveTaken['data']);
            }
            throw new \Exception("Internal Server Error");
            
        } catch (\Throwable $e) {
            $exception = new ApiException('Error '.$e->getMessages(), null, 403, 403, 2001);
            return ApiResponse::exception($exception);
        }
    }
    public function activity(APIRequest $request){
        try {
            $request->validate([
              'leave_id' => 'required',
            ]);
            $user = auth()->user();
            $leave_id = $request->leave_id;

            $leave = AppLeave::join('leave_types as lt','lt.id','leaves.leave_type_id')
            ->select('leaves.*','lt.type_name')
            ->where('leaves.id',$leave_id)
            ->first();

            if (!isset($leave) && empty($leave)) {
              throw new \Exception("Data not found");
            }

            // get atasan
            $employee = EmployeeDetails::join('sub_company as sc','sc.id','employee_details.sub_company_id')
              ->where('user_id', $leave->user_id)
              ->select('employee_details.*','sc.name as sub_company_name')
              ->first();

            $permission = json_decode($employee->permission_require, true);
            $arr =[
              "atasan_1"=>false,
              "atasan_2"=>false,
              "atasan_3"=>false,
              "hrd"=>false,
            ];
            
            $leave_approvedby = json_decode($leave->approved_by);

            if (!empty($permission)) {
              // check atasan 3 exist or not
              if (isset($permission[2]) && !empty($permission[2])) {
                // get user
                $approved_by_atasan_3 = \DB::table('users')->where('id', $permission[2])->first();
                $arr['atasan_3'] = [
                  "user_id"=>$approved_by_atasan_3->id,
                  "name"=>$approved_by_atasan_3->name,
                  "status"=>"mengetahui",
                ];
              }


              // check already approval atasan 1
              if (isset($leave_approvedby[0]) && !empty($leave_approvedby[0])) {
                // get user
                $approved_by_atasan_1 = \DB::table('users')->where('id', $leave_approvedby[0])->first();
                $approved_by_atasan_1_id = 'system';
                $approved_by_atasan_1_name = 'system';
                if (isset($approved_by_atasan_1) && !empty($approved_by_atasan_1)){ 
                  $approved_by_atasan_1_id = $approved_by_atasan_1->id; 
                  $approved_by_atasan_1_name = $approved_by_atasan_1->name; 
                }
                // $arr['atasan_1'] = "approved by $approved_by_atasan_1";
                $arr['atasan_1'] = [
                  "user_id"=>$approved_by_atasan_1_id,
                  "name"=>$approved_by_atasan_1_name,
                  "status"=>"approved",
                ];
              }else{
                // ada 2 kemungkinan di tolak atau belum di approve
                if ($leave->status=='rejected_atasan_satu') {
                  // berarti ditolak
                  $rejected_by = \DB::table('users')->where('id', $leave_approvedby[0])->first();
                  // $arr['atasan_1'] = "rejected by $rejected_by";
                  $arr['atasan_1'] = [
                    "user_id"=>$rejected_by->id,
                    "name"=>$rejected_by->name,
                    "status"=>"rejected",
                  ];
                }else{
                  if (!isset($leave_approvedby) && empty($leave_approvedby)){ 
                    if ($leave->approved_by=='system') {
                      $arr['atasan_1'] = [
                        "user_id"=>"system",
                        "name"=>"system",
                        "status"=>"approved",
                      ];
                    }else{
                      // berarti belum ada action
                      $need_action = \DB::table('users')->where('id', $permission[0])->first();
                      // $arr['atasan_1'] = "waiting approval $need_action";
                      $arr['atasan_1'] = [
                        "user_id"=>$need_action->id,
                        "name"=>$need_action->name,
                        "status"=>"waiting_approval",
                      ];
                    }
                  }else{
                    // berarti belum ada action
                    $need_action = \DB::table('users')->where('id', $permission[0])->first();
                    // $arr['atasan_1'] = "waiting approval $need_action";
                    $arr['atasan_1'] = [
                      "user_id"=>$need_action->id,
                      "name"=>$need_action->name,
                      "status"=>"waiting_approval",
                    ];
                  }
                }
              }
              if ($leave->type_name != 'Dinas sementara') {
                // check already approval atasan 2
                if (isset($leave_approvedby[1]) && !empty($leave_approvedby[1])) {
                  // get user
                  $approved_by_atasan_2 = \DB::table('users')->where('id', $leave_approvedby[1])->first();
                  $approved_by_atasan_2_id = 'system';
                  $approved_by_atasan_2_name = 'system';
                  if (isset($approved_by_atasan_2) && !empty($approved_by_atasan_2)){ 
                    $approved_by_atasan_2_id = $approved_by_atasan_2->id; 
                    $approved_by_atasan_2_name = $approved_by_atasan_2->name; 
                  }
                  $arr['atasan_2'] = [
                    "user_id"=>$approved_by_atasan_2_id,
                    "name"=>$approved_by_atasan_2_name,
                    "status"=>"approved",
                  ];
                }else{
                  // ada 2 kemungkinan di tolak atau belum di approve
                  if ($leave->status=='rejected_atasan_dua') {
                    // berarti ditolak
                    $rejected_by = \DB::table('users')->where('id', $leave_approvedby[1])->first();
                    // $arr['atasan_2'] = "rejected by $rejected_by";
                    $arr['atasan_2'] = [
                      "user_id"=>$rejected_by->id,
                      "name"=>$rejected_by->name,
                      "status"=>"rejected",
                    ];
                  }else{
                    if (!isset($leave_approvedby) && empty($leave_approvedby)){ 
                      // check if approve by is system
                      if ($leave->approved_by=='system') {
                        $arr['atasan_2'] = [
                          "user_id"=>"system",
                          "name"=>"system",
                          "status"=>"approved",
                        ];
                      }else{
                        // berarti belum ada action
                        $need_action = \DB::table('users')->where('id', $permission[1])->first();
                        // $arr['atasan_2'] = "waiting approval $need_action";
                        $arr['atasan_2'] = [
                          "user_id"=>$need_action->id,
                          "name"=>$need_action->name,
                          "status"=>"waiting_approval",
                        ];
                      }
                    }else{
                      // berarti belum ada action
                      $need_action = \DB::table('users')->where('id', $permission[1])->first();
                      // $arr['atasan_2'] = "waiting approval $need_action";
                      $arr['atasan_2'] = [
                        "user_id"=>$need_action->id,
                        "name"=>$need_action->name,
                        "status"=>"waiting_approval",
                      ];
                    }
                  }
                }

                // check by type
                // get hrd
                $sub_company = str_replace(' ','_',$employee->sub_company_name);
                $sub_company = strtolower($sub_company);
                $sub_company = 'subcompany.'.strtolower($sub_company);
                if ($leave->type_name == 'Dinas Luar Kota') {
                  // get dinas luar kota
                  $dinasLuarKota = LeaveDinasLuarKota::where('leave_id', $leave->id)->first();
                  if ($dinasLuarKota->is_approved_hrd==0) {
                      // belum di approve
                      // $arr['hrd'] = "waiting approval HRD";
                      // get hrd
                      $need_action = EmployeeDetails::join('teams as t','t.id','employee_details.department_id')
                        ->join('users as u','u.id','employee_details.user_id')
                        ->where('t.is_hrd',1)
                        ->where('employee_details.is_atasan',1)
                        ->where('employee_details.company_id',$user->company_id)
                        ->where("permission", "LIKE", "%\"" . $sub_company . "\"%")
                        ->select('u.id','u.name')
                        ->first();
                      if (isset($need_action) && !empty($need_action)){  
                        $arr['hrd'] = [
                          "user_id"=>$need_action->id,
                          "name"=>$need_action->name,
                          "status"=>"waiting_approval",
                        ];
                      }
                  }elseif($dinasLuarKota->is_approved_hrd==1){
                    // di approve
                    $approved_by_hrd = \DB::table('users')->where('id', $dinasLuarKota->approved_by)->first();
                    $approved_by_hrd_id = 'system';
                    $approved_by_hrd_name = 'system';
                    if (isset($approved_by_hrd) && !empty($approved_by_hrd)){ 
                      $approved_by_hrd_id = $approved_by_hrd->id; 
                      $approved_by_hrd_name = $approved_by_hrd->name; 
                    }
                    $arr['hrd'] = [
                      "user_id"=>$approved_by_hrd_id,
                      "name"=>$approved_by_hrd_name,
                      "status"=>"approved",
                    ];
                  }else{
                    //di tolak
                    $rejected_by_hrd = \DB::table('users')->where('id', $dinasLuarKota->rejected_by)->first();
                    // $arr['hrd'] = "rejected by $rejected_by_hrd";
                    $arr['hrd'] = [
                      "user_id"=>$rejected_by_hrd->id,
                      "name"=>$rejected_by_hrd->name,
                      "status"=>"rejected",
                    ];
                  }
                }elseif($leave->type_name == 'Ijin'){
                  // get ijin
                  $dinasLuarKota = LeaveIjin::where('leave_id', $leave->id)->first();
                  if ($dinasLuarKota->is_approved_hrd==0) {
                      // belum di approve
                      // $arr['hrd'] = "waiting approval HRD";
                      $need_action = EmployeeDetails::join('teams as t','t.id','employee_details.department_id')
                        ->join('users as u','u.id','employee_details.user_id')
                        ->where('t.is_hrd',1)
                        ->where('employee_details.is_atasan',1)
                        ->where('employee_details.company_id',$user->company_id)
                        ->where("permission", "LIKE", "%\"" . $sub_company . "\"%")
                        ->select('u.id','u.name')
                        ->first();
                      if (isset($need_action) && !empty($need_action)){  
                        $arr['hrd'] = [
                          "user_id"=>$need_action->id,
                          "name"=>$need_action->name,
                          "status"=>"waiting_approval",
                        ];
                      }
                  }elseif($dinasLuarKota->is_approved_hrd==1){
                    // di approve
                    $approved_by_hrd = \DB::table('users')->where('id', $dinasLuarKota->approved_by)->first();
                    $approved_by_hrd_id = 'system';
                    $approved_by_hrd_name = 'system';
                    if (isset($approved_by_hrd) && !empty($approved_by_hrd)){ 
                      $approved_by_hrd_id = $approved_by_hrd->id; 
                      $approved_by_hrd_name = $approved_by_hrd->name; 
                    }
                    // $arr['hrd'] = "approved by $approved_by_hrd";
                    $arr['hrd'] = [
                      "user_id"=>$approved_by_hrd_id,
                      "name"=>$approved_by_hrd_name,
                      "status"=>"approved",
                    ];
                  }else{
                    //di tolak
                    $rejected_by_hrd = \DB::table('users')->where('id', $dinasLuarKota->rejected_by)->first();
                    // $arr['hrd'] = "rejected by $rejected_by_hrd";
                    $arr['hrd'] = [
                      "user_id"=>$rejected_by_hrd->id,
                      "name"=>$rejected_by_hrd->name,
                      "status"=>"rejected",
                    ];
                  }
                }elseif($leave->type_name == 'Cuti' || $leave->type_name == 'Cuti 3 Bulanan'|| $leave->type_name == 'Cuti Custom'){
                  // get cuti
                  $dinasLuarKota = LeaveCuti::where('leave_id', $leave->id)->first();
                  if ($dinasLuarKota->is_approved_hrd==0) {
                      // belum di approve
                      // $arr['hrd'] = "waiting approval HRD";
                      $need_action = EmployeeDetails::join('teams as t','t.id','employee_details.department_id')
                        ->join('users as u','u.id','employee_details.user_id')
                        ->where('t.is_hrd',1)
                        ->where('employee_details.is_atasan',1)
                        ->where('employee_details.company_id',$user->company_id)
                        ->where("permission", "LIKE", "%\"" . $sub_company . "\"%")
                        ->select('u.id','u.name')
                        ->first();
                      if (isset($need_action) && !empty($need_action)){  
                        $arr['hrd'] = [
                          "user_id"=>$need_action->id,
                          "name"=>$need_action->name,
                          "status"=>"waiting_approval",
                        ];
                      }
                  }elseif($dinasLuarKota->is_approved_hrd==1){
                    // di approve
                    $approved_by_hrd = \DB::table('users')->where('id', $dinasLuarKota->approved_by)->first();
                    $approved_by_hrd_id = 'system';
                    $approved_by_hrd_name = 'system';
                    if (isset($approved_by_hrd) && !empty($approved_by_hrd)){ 
                      $approved_by_hrd_id = $approved_by_hrd->id; 
                      $approved_by_hrd_name = $approved_by_hrd->name; 
                    }
                    $arr['hrd'] = [
                      "user_id"=>$approved_by_hrd_id,
                      "name"=>$approved_by_hrd_name,
                      "status"=>"approved",
                    ];
                  }else{
                    //di tolak
                    $rejected_by_hrd = \DB::table('users')->where('id', $dinasLuarKota->rejected_by)->first();
                    // $arr['hrd'] = "rejected by $rejected_by_hrd";
                    $arr['hrd'] = [
                      "user_id"=>$rejected_by_hrd->id,
                      "name"=>$rejected_by_hrd->name,
                      "status"=>"rejected",
                    ];
                  }
                }
              }
            }
            return ApiResponse::make('Leave activity', $arr);
        } catch (\Throwable $e) {
           return $e;
            $exception = new ApiException('Error '.$e->getMessage(), null, 403, 403, 2001);
            return ApiResponse::exception($exception);
        }
    }
}
