<?php

namespace App\Http\Controllers\Admin;

use App\EmployeeDetails;
use App\Exports\LeaveExport;
use App\Helper\Reply;
use App\Http\Requests\Leaves\StoreLeave;
use App\Http\Requests\Leaves\UpdateLeave;
use App\Leave;
use App\LeaveActivity;
use App\LeaveCuti;
use App\LeaveDinasLuarKota;
use App\LeaveDinasSementara;
use App\LeaveIjin;
use App\LeaveType;
use App\Notifications\LeaveCreate;
use App\Notifications\LeaveStatusApprove;
use App\Notifications\LeaveStatusReject;
use App\Notifications\LeaveStatusUpdate;
use App\TipeCuti;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Http\Controllers\LeaveController;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class ManageLeavesController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('app.menu.leaves');
        $this->pageIcon = 'icon-logout';
        $this->middleware(function ($request, $next) {
            if (!in_array('leaves', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->leaves = Leave::where('status', '<>', 'rejected')
            ->get();
        $this->pendingLeaves = Leave::where('status', 'pending')
            ->orderBy('leave_date', 'asc')
            ->get();
        return view('admin.leaves.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->employees = User::allEmployees();
        $this->leaveTypes = LeaveType::all();
        $this->typeCuti = TipeCuti::all();
        return view('admin.leaves.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeave $request)
    {
        $request->validate([
            'leave_type_id' => 'required',
            'leave_date' => 'required',
            'leave_date_end' => 'required|after_or_equal:leave_date',
          ]);
          $request_leave_date = Carbon::parse($request->leave_date)->format('Y-m-d');
          $request_leave_date_end = Carbon::parse($request->leave_date_end)->format('Y-m-d');
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
          // $user = auth()->user();
          $user = User::find($request->user_id);
          $userLogin = auth()->user();
      
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
          $start_date_request = Carbon::parse($request_leave_date);
          $end_date_request = Carbon::parse($request_leave_date_end);
      
          foreach ($approvedLeave as $val) {
            // parse to carbon object
            $start_date = Carbon::parse($val->leave_date);
            $end_date = Carbon::parse($val->leave_date_end);
      
      
            // check user already apply for leave
            if ($val->type_name=='Ijin' ||$val->type_name=='Cuti' || $val->type_name=='Cuti 3 Bulanan' || $val->type_name=='Cuti Custom') {
              if ($start_date_request->between($start_date, $end_date) || $end_date_request->between($start_date, $end_date)) {
                // dd($val,"start_date_request -->".$start_date_request,"start_date -->".$start_date,"end_date -->".$end_date);
                // return response()->json([
                //   'error' => [
                //     'status' => 404,
                //     'message' => 'Anda telah melakukan ijin/cuti untuk tanggal tersebut',
                //   ]
                // ]);
                return Reply::redirect(route('admin.leaves.pending'), 'User telah melakukan ijin/cuti untuk tanggal tersebut');
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
            $startDateMonth = (int)Carbon::parse($request_leave_date)->copy()->format('m');
            $endDateMonth = (int)Carbon::parse($request_leave_date_end)->copy()->format('m');
      
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
              // return response()->json([
              //   'error' => [
              //     'status' => 404,
              //     'message' => 'Jatah ijin anda telah habis',
              //   ]
              // ]);
              return Reply::redirect(route('admin.leaves.pending'), 'Jatah ijin user telah habis');
            }
          }else{
            // add diff now
            $requestStartDate = Carbon::parse($request_leave_date);
            $requestEndDate = Carbon::parse($request_leave_date_end);
            $diff = $requestEndDate->copy()->diffInDays($requestStartDate)+1;
            $totalLeaveTaken += $diff;
            if ($totalLeaveTaken>$checkLeaveType->no_of_leaves) {
              // return response()->json([
              //   'error' => [
              //     'status' => 404,
              //     'message' => 'Jatah ijin anda telah habis',
              //   ]
              // ]);
              return Reply::redirect(route('admin.leaves.pending'), 'Jatah ijin user telah habis');
            }
          }
          
          // check leave
          DB::beginTransaction();
          try {
            // create leave
            $model = new Leave;
            $model->user_id = $user->id;
            $model->leave_type_id = $request->leave_type_id;
            $model->leave_date = Carbon::parse($request_leave_date);
            $model->leave_date_end = $request_leave_date_end;
            $model->duration = 'single';
            $model->reason = isset($request->deskripsi) && !empty($request->deskripsi) ? $request->deskripsi : '-';
            if ($request->status=='pending') {
                $model->status = 'pending';
            }else{
                $model->status = 'approved_atasan_dua';
                $model->masking_status = 'done';
                $model->is_final = 1;
                $model->approved_by  = "[$userLogin->id,$userLogin->id]";
            }
            $model->save();
      
            //TODO
            // harus dipilah2 per type
      
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
              if ($request->status!='pending') {
                $child->is_approved_hrd=1;
                $child->approved_by=$userLogin->id;
                $child->approved_at=Carbon::now();
              }
              $child->save();
            } elseif ($checkLeaveType->type_name == 'Cuti' || $checkLeaveType->type_name == 'Cuti 3 Bulanan' || $checkLeaveType->type_name == 'Cuti Custom') {
              // dd($model->id);
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
              if ($request->status!='pending') {
                $child->is_approved_hrd=1;
                $child->approved_by=$userLogin->id;
                $child->approved_at=Carbon::now();
              }
              $child->save();
            } elseif ($checkLeaveType->type_name == 'Dinas sementara') {
              // insert into leave_dinas_sementaras
              $child = new LeaveDinasSementara;
              $child->leave_id = $model->id;
              $child->start_hour = $request->jam_mulai;
              $child->end_hour = $request->jam_selesai;
              $child->destination = $request->tujuan_dinas;
              // if ($request->status!='pending') {
              //   $child->is_done=1;
              //   $child->approved_at=Carbon::now();
              // }
              $child->save();
            } elseif ($checkLeaveType->type_name == 'Dinas Luar Kota') {
              // insert into leave_dinas_luar_kotas
              $child = new LeaveDinasLuarKota;
              $child->leave_id = $model->id;
              $child->rute_awal = $request->rute_awal;
              $child->rute_akhir = $request->rute_akhir;
              $child->alasan = $request->alasan;
              $child->biaya = $request->biaya;
              if ($request->status!='pending') {
                $child->is_approved_hrd=1;
                $child->approved_by=$userLogin->id;
                $child->approved_at=Carbon::now();
              }
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
            if ($request->status!='pending') {
              // insert into log twice
              LeaveActivity::logActivityAccepted($model->id, $userLogin);
              LeaveActivity::logActivityAccepted($model->id, $userLogin);
            }
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
            // return redirect()->route('leaves.index')->with('success')
            return Reply::redirect(route('admin.leaves.pending'), 'Berhasil membuat Ijin');
            // return ApiResponse::make($msg, [
            //   'leave' => $model,
            //   'child' => isset($child) && !empty($child)?$child:null,
            // ]);
          } catch (\Throwable $e) {
            DB::rollback();
            // dd($e->getMessage());
            return Reply::redirect(route('admin.leaves.pending'), 'Gagal membuat Ijin,'. $e->getMessage());
          }
        // if ($request->duration == 'multiple') {
        //     $dates = explode(',', $request->multi_date);
        //     foreach ($dates as $date) {
        //         $leave = new Leave();
        //         $leave->user_id = $request->user_id;
        //         $leave->leave_type_id = $request->leave_type_id;
        //         $leave->duration = $request->duration;
        //         $leave->leave_date = Carbon::createFromFormat($this->global->date_format, $date)->format('Y-m-d');
        //         $leave->reason = $request->reason;
        //         $leave->status = $request->status;
        //         $leave->save();
        //     }

        //     return Reply::redirect(route('admin.leaves.index'), __('messages.leaveAssignSuccess'));
        // } else {
        //     $leave = new Leave();
        //     $leave->user_id = $request->user_id;
        //     $leave->leave_type_id = $request->leave_type_id;
        //     $leave->duration = $request->duration;
        //     $leave->leave_date = Carbon::createFromFormat($this->global->date_format, $request->leave_date)->format('Y-m-d');
        //     $leave->reason = $request->reason;
        //     $leave->status = $request->status;
        //     $leave->save();
        //     return Reply::redirect(route('admin.leaves.index'), __('messages.leaveAssignSuccess'));
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leave = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
            ->selectRaw('leaves.*,lt.type_name')
            ->where('leaves.id', $id)
            ->first();
        $this->leave = $leave;
        // get detail by type
        if ($leave->type_name == 'Ijin') {
            $getChild = LeaveIjin::where('leave_id', $leave->id)->first();
        } elseif ($leave->type_name == 'Cuti' || $leave->type_name =='Cuti 3 Bulanan' || $leave->type_name == 'Cuti Custom') {
            $getChild = LeaveCuti::where('leave_id', $leave->id)->first();
        } elseif ($leave->type_name == 'Dinas sementara') {
            $getChild = LeaveDinasSementara::where('leave_id', $leave->id)->first();
        } elseif ($leave->type_name == 'Dinas Luar Kota') {
            $getChild = LeaveDinasLuarKota::where('leave_id', $leave->id)->first();
        }
        $this->detail = $getChild;
        // return $leave;
        return view('admin.leaves.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->employees = User::allEmployees();
        $this->leaveTypes = LeaveType::all();
        $this->leave = Leave::findOrFail($id);
        $view = view('admin.leaves.edit', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLeave $request, $id)
    {
        $leave = Leave::findOrFail($id);
        $oldStatus = $leave->status;

        $leave->user_id = $request->user_id;
        $leave->leave_type_id = $request->leave_type_id;
        $leave->leave_date = Carbon::createFromFormat($this->global->date_format, $request->leave_date)->format('Y-m-d');
        $leave->reason = $request->reason;
        $leave->status = $request->status;
        $leave->save();

        return Reply::redirect(route('admin.leaves.index'), __('messages.leaveAssignSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Leave::destroy($id);
        return Reply::success('messages.leaveDeleteSuccess');
    }

    public function leaveAction(Request $request)
    {
        $leave = Leave::findOrFail($request->leaveId);
        if ($request->action == 'approved') {
            // logic here
            $approve = Leave::approveLeave($leave->id);
            $approve = $approve->getData(true);
            if (isset($approve['error']) && !empty($approve['error'])) {
                $msg = $approve['error']['message'];
                return Reply::error($msg);
            }
            return Reply::success(__('messages.leaveStatusUpdate'));
        } else {
            $formatedData = [
                'leave_id' => $request->leaveId,
                'reason' => $request->reason,
            ];
            $reject = Leave::rejectLeave((object) $formatedData);
            $reject = $reject->getData(true);
            if (isset($reject['error']) && !empty($reject['error'])) {
                $msg = $reject['error']['message'];
                return Reply::error($msg);
            }
            return Reply::success(__('messages.leaveStatusUpdate'));
        }
        // $leave->save();

        // return Reply::success(__('messages.leaveStatusUpdate'));
    }

    public function rejectModal(Request $request)
    {
        $this->leaveAction = $request->leave_action;
        $this->leaveID = $request->leave_id;
        return view('admin.leaves.reject-reason-modal', $this->data);
    }

    public function allLeave()
    {
        $this->employees = User::allEmployees();
        $this->fromDate = Carbon::today()->subDays(30);
        $this->toDate = Carbon::today();
        $this->pendingLeaves = Leave::where('status', 'pending')->count();
        return view('admin.leaves.all-leaves', $this->data);
    }

    public function exportExcel($start_date, $end_date, $employeeId){
        // Generate and return the spreadsheet
        return Excel::download(new LeaveExport($start_date, $end_date, $employeeId), "leave-$start_date-sd-$end_date.xlsx");
    }

    public function data(Request $request, $employeeId = null)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $startDt = '';
        $endDt = '';
        if (!is_null($startDate)) {
            $startDate = Carbon::createFromFormat($this->global->date_format, $startDate)->format('Y-m-d');
            $startDt = 'DATE(leaves.`leave_date`) >= ' . '"' . $startDate . '"';
        }

        if (!is_null($endDate)) {
            $endDate = Carbon::createFromFormat($this->global->date_format, $endDate)->format('Y-m-d');
            $endDt = 'DATE(leaves.`leave_date`) <= ' . '"' . $endDate . '"';
        }
        

        $leavesList = Leave::select(
            'leaves.id',
            'users.name',
            'leaves.leave_date',
            'leaves.leave_date_end',
            'leaves.status',
            'leave_types.type_name',
            'leave_types.color',
            'leaves.duration',
            'leaves.masking_status',
            'ed.permission_require',
            'leaves.created_at',
            'leaves.reason',
        )
            ->where('leaves.status', '<>', 'rejected');
            if (!empty($startDate)) {
                $leavesList =$leavesList->whereRaw($startDt);
            }
            if (!empty($endDt)) {
                $leavesList =$leavesList->whereRaw($endDt);
            }
            $leavesList =$leavesList->join('employee_details as ed', 'ed.user_id', 'leaves.user_id')
            ->join('users', 'users.id', '=', 'leaves.user_id')
            ->join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id');
        if ($employeeId != 0) {
            $leavesList->where('leaves.user_id', $employeeId);
        }

        $leaves = $leavesList->get();
        return DataTables::of($leaves)
            ->addColumn('checkbox', function ($row) {
                return '<div class="form-check">
                <input class="form-check-input checkbox-leave" type="checkbox" value="'.$row->id.'" id="flexCheckDefault">
              </div>';
            })
            ->addColumn('employee', function ($row) {
                return ucwords($row->name);
            })
            ->addColumn('reason', function ($row) {
                return $row->reason;
            })
            ->addColumn('date', function ($row) {
                return $row->leave_date->format('d-m-Y');
            })
            ->addColumn('date_end', function ($row) {
                return $row->leave_date_end->format('d-m-Y');
            })
            ->addColumn('ijin_dipakai', function ($row) {
                return (Carbon::parse($row->leave_date_end)->diffInDays($row->leave_date))+1;
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y');
            })
            ->addColumn('status', function ($row) {
                $label = $row->status == 'pending' ? 'warning' : 'success';
                return '<div class="label label-' . $label . '">' . $row->status . '</div>';
            })
            ->addColumn('leave_type', function ($row) {
                if ($row->type_name=='Cuti Custom') {
                    $detail = LeaveCuti::join('tipe_cutis','tipe_cutis.id','leave_cutis.kategori_cuti')
                    ->where('leave_cutis.leave_id',$row->id)
                    ->select('tipe_cutis.name')
                    ->first();
                    if (isset($detail) && !empty($detail)) {
                        $row->type_name = $row->type_name." ($detail->name)";
                    }
                }
                $type = '<div class="label-' . $row->color . ' label">' . $row->type_name . '</div>';

                if ($row->duration == 'half day') {
                    $type .= ' <div class="label-inverse label">' . __('modules.leaves.halfDay') . '</div>';
                }

                return $type;
            })
            ->addColumn('action', function ($row) {
                $permission = $this->checkCanApproveSingle($row);
                if ($permission) {
                    return '<a href="javascript:;"
                            data-leave-id=' . $row->id . ' 
                            data-leave-action="approved" 
                            class="btn btn-success btn-circle leave-action"
                            data-toggle="tooltip"
                            data-original-title="' . __('app.approved') . '">
                                <i class="fa fa-check"></i>
                            </a>
                            <a href="javascript:;" 
                            data-leave-id=' . $row->id . '
                            data-leave-action="rejected"
                            class="btn btn-danger btn-circle leave-action-reject"
                            data-toggle="tooltip"
                            data-original-title="' . __('app.reject') . '">
                                <i class="fa fa-times"></i>
                            </a>
                            
                            <a href="javascript:;"
                            data-leave-id=' . $row->id . '
                            class="btn btn-info btn-circle show-leave"
                            data-toggle="tooltip"
                            data-original-title="' . __('app.details') . '">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </a>
                            <a href="javascript:;"
                            data-leave-id=' . $row->id . '
                            class="btn btn-danger btn-circle delete-leave"
                            data-toggle="tooltip"
                            data-original-title="Delete">
                                <i class="fa fa-remove" aria-hidden="true"></i>
                            </a>';
                }
                // for now all can approve
                // get type
                if ($row->type_name == 'Dinas Luar Kota') {
                    // get child
                    $getChild = LeaveDinasLuarKota::where('leave_id', $row->id)->first();
                    if ($row->status == 'approved_atasan_dua') {
                        if ($getChild->is_approved_hrd === null) {
                            return '<a href="javascript:;"
                                data-leave-id=' . $row->id . ' 
                                data-leave-action="approved" 
                                class="btn btn-success btn-circle leave-action"
                                data-toggle="tooltip"
                                data-original-title="' . __('app.approved') . '">
                                    <i class="fa fa-check"></i>
                                </a>
                                <a href="javascript:;" 
                                data-leave-id=' . $row->id . '
                                data-leave-action="rejected"
                                class="btn btn-danger btn-circle leave-action-reject"
                                data-toggle="tooltip"
                                data-original-title="' . __('app.reject') . '">
                                    <i class="fa fa-times"></i>
                                </a>
                                
                                <a href="javascript:;"
                                data-leave-id=' . $row->id . '
                                class="btn btn-info btn-circle show-leave"
                                data-toggle="tooltip"
                                data-original-title="' . __('app.details') . '">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </a>
                                <a href="javascript:;"
                                data-leave-id=' . $row->id . '
                                class="btn btn-danger btn-circle delete-leave"
                                data-toggle="tooltip"
                                data-original-title="Delete">
                                    <i class="fa fa-remove" aria-hidden="true"></i>
                                </a>';
                        }
                    }
                }
                // if ($row->) {
                //     # code...
                // }
                // if ($row->status == 'pending' || $row->status == 'approved_atasan_satu') {

                // }

                return '<a href="javascript:;"
                        data-leave-id=' . $row->id . '
                        class="btn btn-info btn-circle show-leave"
                        data-toggle="tooltip"
                        data-original-title="' . __('app.details') . '">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </a>
                        <a href="javascript:;"
                        data-leave-id=' . $row->id . '
                        class="btn btn-danger btn-circle delete-leave"
                        data-toggle="tooltip"
                        data-original-title="Delete">
                            <i class="fa fa-remove" aria-hidden="true"></i>
                        </a>';
            })
            ->addIndexColumn()
            ->rawColumns(['checkbox','date', 'status', 'leave_type', 'action'])
            ->make(true);
    }

    public function pendingLeaves()
    {
        $pendingLeaves = Leave::join('employee_details as ed', 'ed.user_id', 'leaves.user_id')
            ->join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
            ->whereIn('status', ['pending', 'approved_atasan_satu'])
            ->where('leaves.company_id', $this->user->company_id)
            ->selectRaw('leaves.*,ed.permission_require,lt.no_of_leaves')
            ->orderBy('leave_date', 'asc')
            ->get();
        $this->pendingLeaves = $this->checkCanApprove($pendingLeaves);
        // $this->pendingLeaves = $pendingLeaves->each->append('leaves_taken_count');
        // $this->allowedLeaves = LeaveType::sum('no_of_leaves');

        return view('admin.leaves.pending', $this->data);
    }
    public function checkCanApprove($model)
    {
        $user = $this->user;
        // dd('asd');
        foreach ($model as $key => $getLeave) {
            // get sum leave by type
            $countLeave = Leave::where('user_id', $getLeave->user_id)
                ->where('leave_type_id', $getLeave->leave_type_id)
                ->where('is_final', 1)
                ->count();
            $getLeave->leaves_taken_count = $countLeave;
            $getLeave->no_of_leaves = $getLeave->no_of_leaves - $countLeave;
            $permission = \json_decode($getLeave->permission_require);
            // check if this user can approve this data 
            $arr_permission_require = $permission;
            if (!empty($arr_permission_require)) {
                if (count($arr_permission_require) > 0) {
                    for ($i = 0; $i < count($arr_permission_require); $i++) {
                        // check status
                        if ($getLeave->status == 'pending') {
                            if (isset($arr_permission_require[0]) && !empty($arr_permission_require[0])) {
                                if ($arr_permission_require[0] != $user->id) {
                                    // remove this collection
                                    $model->forget($key);
                                    // $getLeave->can_approve = true;
                                }
                            } else {
                                $model->forget($key);
                            }
                        } elseif ($getLeave->status == 'approved_atasan_satu') {
                            if (isset($arr_permission_require[1]) && !empty($arr_permission_require[1])) {
                                if ($arr_permission_require[1] != $user->id) {
                                    // approval tingkat 2
                                    $model->forget($key);
                                    // $getLeave->can_approve = true;
                                }
                            } else {
                                $model->forget($key);
                            }
                        }
                    }
                }
            } else {
                $model->forget($key);
            }
        }
        return $model;
    }
    public function checkCanApproveSingle($row)
    {
        $response = false;
        $user = $this->user;
        $permission = \json_decode($row->permission_require);
        // check if this user can approve this data 
        $arr_permission_require = $permission;
        if (isset($arr_permission_require) && !empty($arr_permission_require)) {
            for ($i = 0; $i < count($arr_permission_require); $i++) {
                // check status
                if ($row->status == 'pending') {
                    if (isset($arr_permission_require[0]) && !empty($arr_permission_require[0])) {
    
                        if ($arr_permission_require[0] == $user->id) {
                            $response = true;
                        }
                    }
                } elseif ($row->status == 'approved_atasan_satu') {
                    if (isset($arr_permission_require[1]) && !empty($arr_permission_require[1])) {
                        if ($arr_permission_require[1] == $user->id) {
                            $response = true;
                        }
                        if ($row->type_name=='Dinas sementara') {
                            $response = false;
                        }
                    }
                }
            }
        }
        return $response;
    }
    public function delete(request $request){
        DB::beginTransaction();
        try {
            $id = $request->id;
            // get leave
            $leave = Leave::find($id);
            // get type
            $type = LeaveType::find($leave->leave_type_id);
    
            if ($type->type_name=='Ijin') {
                // get child
                $child = LeaveIjin::where('leave_id', $leave->id)->first();
                if (isset($child) && !empty($child)){ 
                    $child->delete();
                }
            }elseif($type->type_name=='Cuti' || $type->type_name=='Cuti 3 Bulanan' || $type->type_name=='Cuti Custom'){
                $child = LeaveCuti::where('leave_id', $leave->id)->first();
                if (isset($child) && !empty($child)){ 
                    $child->delete();
                }
            }elseif($type->type_name=='Dinas sementara'){
                $child = LeaveDinasSementara::where('leave_id', $leave->id)->first();
                if (isset($child) && !empty($child)){ 
                    $child->delete();
                }
            }elseif($type->type_name=='Dinas Luar Kota'){
                $child = LeaveDinasLuarKota::where('leave_id', $leave->id)->first();
                if (isset($child) && !empty($child)){ 
                    $child->delete();
                }
            }
            $leave->delete();
            DB::commit();
            return Reply::success("Data Berhasil Dihapus");
        } catch (\Throwable $th) {
            DB::rollback();
            return Reply::error($th->getMessage());
        }
    }
    public function bulkDelete(request $request){
        DB::beginTransaction();
        try {
            $arr_id = $request->id;
            // get leave
            $leaves = Leave::whereIn('id',$arr_id)->get();
            foreach ($leaves as $leave) {
                // get type
                $type = LeaveType::find($leave->leave_type_id);
        
                if ($type->type_name=='Ijin') {
                    // get child
                    $child = LeaveIjin::where('leave_id', $leave->id)->first();
                    if (isset($child) && !empty($child)){ 
                        $child->delete();
                    }
                }elseif($type->type_name=='Cuti' || $type->type_name=='Cuti 3 Bulanan' || $type->type_name=='Cuti Custom'){
                    $child = LeaveCuti::where('leave_id', $leave->id)->first();
                    if (isset($child) && !empty($child)){ 
                        $child->delete();
                    }
                }elseif($type->type_name=='Dinas sementara'){
                    $child = LeaveDinasSementara::where('leave_id', $leave->id)->first();
                    if (isset($child) && !empty($child)){ 
                        $child->delete();
                    }
                }elseif($type->type_name=='Dinas Luar Kota'){
                    $child = LeaveDinasLuarKota::where('leave_id', $leave->id)->first();
                    if (isset($child) && !empty($child)){ 
                        $child->delete();
                    }
                }
                $leave->delete();
            }
            DB::commit();
            return Reply::success("Data Berhasil Dihapus");
        } catch (\Throwable $th) {
            DB::rollback();
            return Reply::error($th->getMessage());
        }
    }
}
