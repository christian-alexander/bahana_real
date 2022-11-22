<?php

namespace App;

use App\Notifications\LeaveApproved;
use App\Notifications\LeaveReject;
use App\Observers\LeaveObserver;
use App\Scopes\CompanyScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Leave extends BaseModel
{
    protected $dates = ['leave_date', 'leave_date_end'];
    protected $appends = ['date'];

    protected static function boot()
    {
        parent::boot();

        static::observe(LeaveObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function type()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }
    public function getDateAttribute()
    {
        return $this->leave_date->toDateString();
    }

    public function getLeavesTakenCountAttribute()
    {
        $userId = $this->user_id;
        $setting = Setting::first();
        $user = User::withoutGlobalScope('active')->findOrFail($userId);

        if ($setting->leaves_start_from == 'joining_date') {
            $fullDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->where('duration', '<>', 'half day')
                ->count();

            $halfDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->where('duration', 'half day')
                ->count();

            return ($fullDay + ($halfDay / 2));
        } else {
            $fullDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', Carbon::today()->endOfYear()->format('Y-m-d'))
                ->where('status', 'approved')
                ->where('duration', '<>', 'half day')
                ->count();

            $halfDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', Carbon::today()->endOfYear()->format('Y-m-d'))
                ->where('status', 'approved')
                ->where('duration', 'half day')
                ->count();

            return ($fullDay + ($halfDay / 2));
        }
    }

    public static function byUser($userId)
    {

        $user = User::withoutGlobalScope('active')->findOrFail($userId);
        $setting = Company::find($user->company_id);

        if ($setting->leaves_start_from == 'joining_date' && isset($user->employee[0])) {
            return Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->get();
        } else {
            return Leave::where('user_id', $userId)
                ->where('leave_date', '<=', Carbon::today()->endOfYear()->format('Y-m-d'))
                ->where('status', 'approved')
                ->get();
        }
    }
  
  	public static function checkHRD($team_id){
        $user = auth()->user();
        $resp = true;
        $checkHrd = Team::where('is_hrd',1)->where('id',$team_id)->count();
        if ($checkHrd==0) {
            $resp = false;
        }
        $employee_detail = EmployeeDetails::where('user_id', $user->id)->first();
        if ($employee_detail->is_atasan==0) {
            $resp = false;
        }
        return $resp;
    } 

    public static function byUserCount($userId)
    {
        $setting = Setting::first();
        $user = User::withoutGlobalScope('active')->findOrFail($userId);

        if ($setting->leaves_start_from == 'joining_date') {
            $fullDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->where('duration', '<>', 'half day')
                ->get();

            $halfDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->where('duration', 'half day')
                ->get();

            return (count($fullDay) + (count($halfDay) / 2));
        } else {
            $fullDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', Carbon::today()->endOfYear()->format('Y-m-d'))
                ->where('status', 'approved')
                ->where('duration', '<>', 'half day')
                ->get();

            $halfDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->where('duration', 'half day')
                ->get();

            return (count($fullDay) + (count($halfDay) / 2));
        }
    }
    public static function approveLeave($leave_id)
    {
        $flagErrorMail = false;
        $user = auth()->user();
        $timezone=7;
        $timezone_attendance = Attendance::where('user_id', $user->id)->orderBy('id','desc')->first();
        if ($timezone_attendance) {
            $timezone = $timezone_attendance->clock_in_timezone;
        }
        // get leave
        $getLeave = Leave::join('employee_details as ed', 'ed.user_id', 'leaves.user_id')
            ->join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
            ->where('leaves.id', $leave_id)
            ->select('leaves.*', 'ed.permission_require', 'lt.type_name')
            ->first();
        if (!isset($getLeave) && empty($getLeave)) {
            return response()->json([
                'error' => [
                    'status' => 404,
                    'message' => 'Data not found',
                ]
            ]);
        }
        DB::beginTransaction();
        try {
            // check if this user can approve this data 
            $arr_permission_require = $getLeave->permission_require;
            $arr_permission_require = json_decode($arr_permission_require);
            // check status
            if ($getLeave->status == 'pending') {
                if (isset($arr_permission_require[0]) && !empty($arr_permission_require[0])) {
                    if ($arr_permission_require[0] == $user->id) {
                        // approval tingkat 1
                        $getLeave->status = 'approved_atasan_satu';
                        $getLeave->masking_status = 'in progress';
                        // update approved by
                        $getLeave->approved_by = self::setApproved($user->id, $getLeave->approved_by);

                        if(isset($getLeave->approved_at_with_gmt)){
                            $temp_arr=json_decode($getLeave->approved_at_with_gmt);
                            array_push($temp_arr,Carbon::now()->addHours($timezone)->format('Y-m-d H:i'));
                            $getLeave->approved_at_with_gmt = $temp_arr;
                        }else{
                            $getLeave->approved_at_with_gmt = [Carbon::now()->addHours($timezone)->format('Y-m-d H:i')];
                        }
                        
                        $getLeave->save();

                        // insert into log
                        LeaveActivity::logActivityAccepted($getLeave->id, $user);

                        // check if this was dinas sementara
                        if ($getLeave->type_name == 'Dinas sementara') {

                            // update leave set is final to 1
                            $getLeave->is_final = 1;
                            $getLeave->masking_status = 'done';
                            $getLeave->save();

                            $arr_cc =[];

                            // send notif to atasan 2
                            // check atasan 2 exist
                            if (isset($arr_permission_require[1]) && !empty($arr_permission_require[1])) {
                                // if exist then send notif
                                $userNotif = User::find($arr_permission_require[1]);
                                // $userRequest = User::find($getLeave->user_id);
                                // $approvedBy = User::find($user->id);
                                // $userNotif->notify(new LeaveApproved($getLeave, $userRequest,$approvedBy));
                                if(!isset($userNotif)){
                                    return response()->json([
                                        'error' => [
                                            'status' => 500,
                                            'message' => 'User atasan tidak aktif,silahkan hubungi Admin.',
                                        ]
                                    ]);
                                }   

                                array_push($arr_cc, $userNotif->email);
                            }

                            // send notif HRD
                            // get user in HRD
                            $getMemberHRD = EmployeeDetails::join('teams as t','t.id','employee_details.department_id')
                                ->join('users as u','u.id','employee_details.user_id')
                                ->where('t.is_hrd',1)
                                ->where('employee_details.is_atasan',1)
                                ->where('employee_details.company_id',$user->company_id)
                                ->where('employee_details.sub_company_id',$user->employeeDetail->sub_company_id)
                                ->selectRaw('employee_details.*,u.email')
                                ->get();
                            $arr_email_hrd=[];
                            $arr_email_hrd_id=[];
                            if (count($getMemberHRD)==0) {
                                // check hrd in sub company
                                // get cub company
                                $sub_company = SubCompany::find($user->employeeDetail->sub_company_id);
                                if ($sub_company) {
                                    // exist
                                    if (!empty($sub_company->hrd)) {
                                        // find the user
                                        $userHrdSubCompany = DB::table('users')->find($sub_company->hrd);

                                        array_push($arr_email_hrd,$userHrdSubCompany->email);
                                        array_push($arr_email_hrd_id,$userHrdSubCompany->id);
                                    }
                                }
                            }else{
                                foreach ($getMemberHRD as $val) {
                                    array_push($arr_email_hrd,$val->email);
                                    array_push($arr_email_hrd_id,$val->user_id);
                                }
                            }
                            if (count($arr_email_hrd)>0) {
                                // send email
                                // $userNotif = User::find($val->user_id);
                                $userNotif = User::where('id',$arr_email_hrd_id[0])->first();
                                // $userRequest = User::find($getLeave->user_id);
                                $userRequest = \DB::table('users')->where('id',$getLeave->user_id)->first();
                                // $approvedBy = User::find($user->id);
                                $approvedBy = \DB::table('users')->where('id',$user->user_id)->first();
                                // unset key 0
                                unset($arr_email_hrd[0]);

                                // merge 2 array
                                $arr_combine = array_merge($arr_cc,$arr_email_hrd);
                                try {
                                    $userNotif->notify(new LeaveApproved($getLeave, $userRequest,$user, $arr_combine,$userNotif));
                                } catch (\Throwable $th) {
                                    $flagErrorMail = true;
                                }
                                // $userNotif->notify(new LeaveApproved($getLeave, $userRequest,$user, $arr_combine));
                            }

                            
                        }elseif($getLeave->type_name == 'Dinas Luar Kota'){
                            if (!empty($arr_permission_require[1])) {
                                // send notif to atasan 2 / manager
                                $userNotif = User::find($arr_permission_require[1]);
                                $userRequest = User::find($getLeave->user_id);
                                $approvedBy = User::find($user->id);
                                try {
                                    $userNotif->notify(new LeaveApproved($getLeave, $userRequest,$approvedBy,[],$userNotif));
                                } catch (\Throwable $th) {
                                    $flagErrorMail = true;
                                }
                                // $userNotif->notify(new LeaveApproved($getLeave, $userRequest,$approvedBy));
                            }
                        }else{
                            // ijin, cuti dll
                            // send notif to atasan 2
                            if (isset($arr_permission_require[1]) && !empty($arr_permission_require[1])) {
                                // if exist then send notif
                                $userNotif = User::find($arr_permission_require[1]);
                                $userRequest = User::find($getLeave->user_id);
                                $approvedBy = User::find($user->id);
                                try {
                                    $userNotif->notify(new LeaveApproved($getLeave, $userRequest,$approvedBy,[],$userNotif));
                                } catch (\Throwable $th) {
                                    $flagErrorMail = true;
                                }
                                // $userNotif->notify(new LeaveApproved($getLeave, $userRequest,$approvedBy));
                            }
                        }

                        // send notif to requester
                        $userNotifRequester = User::find($getLeave->user_id);
                        $userRequest = User::find($getLeave->user_id);
                        $approvedBy = User::find($user->id);
                        try {
                            $userNotifRequester->notify(new LeaveApproved($getLeave, $userRequest,$approvedBy,[],$userNotifRequester));
                        } catch (\Throwable $th) {
                            $flagErrorMail = true;
                        }

                        // todo: if next permission require didnt exist then make this request as a final. mean leave create or dinas started
                        if (empty($arr_permission_require[1])) {
                            // empty
                            $getLeave->status = 'approved_atasan_dua';
                            $getLeave->is_final = 1;
                            $getLeave->save();

                        }

                        DB::commit();
                        if ($flagErrorMail) {
                            return response()->json([
                                'success' => [
                                    'status' => 200,
                                    'message' => 'Leave approved, Email error silahkan hubungi developer',
                                    'data' => [
                                        'leave' => $getLeave
                                    ],
                                ]
                            ]);
                        }else{
                            return response()->json([
                                'success' => [
                                    'status' => 200,
                                    'message' => 'Leave approved',
                                    'data' => [
                                        'leave' => $getLeave
                                    ],
                                ]
                            ]);
                        }
                    }
                }
                
            } elseif ($getLeave->status == 'approved_atasan_satu') {
                // check if masking status was done
                // if ($getLeave->masking_status=='done') {
                //     // commit this action beacuse this is not error
                //     DB::commit();
                //     // if done then return this leave dont need approval
                //     return response()->json([
                //         'error' => [
                //             'status' => 501,
                //             'message' => 'This leave dont need approval',
                //         ]
                //     ]);
                // }
                // check if this request wasn dinas sementara
                if ($getLeave->type_name != 'Dinas sementara') {
                    if (isset($arr_permission_require[1]) && !empty($arr_permission_require[1])) {
                        if ($arr_permission_require[1] == $user->id) {
                            // approval tingkat 2
                            $getLeave->status = 'approved_atasan_dua';
                            // update approved by
                            $getLeave->approved_by = self::setApproved($user->id, $getLeave->approved_by);

                            if(isset($getLeave->approved_at_with_gmt)){
                                $temp_arr=json_decode($getLeave->approved_at_with_gmt);
                                array_push($temp_arr,Carbon::now()->addHours($timezone)->format('Y-m-d H:i'));
                                $getLeave->approved_at_with_gmt = $temp_arr;
                            }else{
                                $getLeave->approved_at_with_gmt = [Carbon::now()->addHours($timezone)->format('Y-m-d H:i')];
                            }

                            // check if this type is Dinas Luar Kota (need approval hrd)
                            if ($getLeave->type_name == 'Dinas Luar Kota') {
                                $getLeave->is_final = 0;
                            }else{
                                // ijin and cuti
                                // $getLeave->masking_status = 'done';
                                $getLeave->is_final = 1;
                            }
                            
                            $getLeave->save();

                            // send notif to requester
                            $userNotifRequester = User::find($getLeave->user_id);
                            $userRequest = User::find($getLeave->user_id);
                            $approvedBy = User::find($user->id);
                            try {
                                $userNotifRequester->notify(new LeaveApproved($getLeave, $userRequest,$approvedBy,[],$userNotifRequester));
                            } catch (\Throwable $th) {
                                $flagErrorMail = true;
                            }
    
                            // insert into log
                            LeaveActivity::logActivityAccepted($getLeave->id, $user);
    
                            DB::commit();
                            // OLD
                            // if($getLeave->type_name == 'Dinas Luar Kota'){
                            // send notif to HRD
                            // $getMemberHRD = EmployeeDetails::join('teams as t','t.id','employee_details.department_id')
                            //     ->where('t.is_hrd',1)
                            //     ->where('employee_details.is_atasan',1)
                            //     ->where('employee_details.company_id',$user->company_id)
                            //     ->selectRaw('employee_details.*')
                            //     ->get();
                            // foreach ($getMemberHRD as $val) {
                            //     $userNotif = User::find($val->user_id);
                            //     $userRequest = User::find($getLeave->user_id);
                            //     $approvedBy = User::find($user->id);
                            //     $userNotif->notify(new LeaveApproved($getLeave, $userRequest,$approvedBy));
                            // }
                            // }
                            $getMemberHRD = EmployeeDetails::join('teams as t','t.id','employee_details.department_id')
                                ->join('users as u','u.id','employee_details.user_id')
                                ->where('t.is_hrd',1)
                                ->where('employee_details.is_atasan',1)
                                ->where('employee_details.company_id',$user->company_id)
                                ->where('employee_details.sub_company_id',$user->employeeDetail->sub_company_id)
                                ->selectRaw('employee_details.*,u.email')
                                ->get();
                            $arr_email_hrd=[];
                            $arr_email_hrd_id=[];
                            if (count($getMemberHRD)==0) {
                                // check hrd in sub company
                                // get cub company
                                $sub_company = SubCompany::find($user->employeeDetail->sub_company_id);
                                if ($sub_company) {
                                    // exist
                                    if (!empty($sub_company->hrd)) {
                                        // find the user
                                        $userHrdSubCompany = DB::table('users')->find($sub_company->hrd);

                                        array_push($arr_email_hrd,$userHrdSubCompany->email);
                                        array_push($arr_email_hrd_id,$userHrdSubCompany->id);
                                    }
                                }
                            }else{
                                foreach ($getMemberHRD as $val) {
                                    array_push($arr_email_hrd,$val->email);
                                    array_push($arr_email_hrd_id,$val->user_id);
                                }
                            }
                            if (count($arr_email_hrd)>0) {
                                // send email
                                // $userNotif = User::find($val->user_id);
                                $userNotif = User::where('id',$arr_email_hrd_id[0])->first();
                                // $userRequest = User::find($getLeave->user_id);
                                $userRequest = \DB::table('users')->where('id',$getLeave->user_id)->first();
                                // $approvedBy = User::find($user->id);
                                $approvedBy = \DB::table('users')->where('id',$user->user_id)->first();
                                // unset key 0
                                unset($arr_email_hrd[0]);

                                // merge 2 array
                                // $arr_combine = array_merge($arr_cc,$arr_email_hrd);
                                try {
                                    $userNotif->notify(new LeaveApproved($getLeave, $userRequest,$user, $arr_email_hrd,$userNotif));
                                } catch (\Throwable $th) {
                                    $flagErrorMail = true;
                                }
                                // $userNotif->notify(new LeaveApproved($getLeave, $userRequest,$user, $arr_email_hrd));
                            }
                            // DB::commit();
                            return response()->json([
                                'success' => [
                                    'status' => 200,
                                    'message' => 'Leave approved',
                                    'data' => [
                                        'leave' => $getLeave
                                    ],
                                ]
                            ]);
                        }
                    }
                    // TIDAK DIGUNAKAN
                    // only admin can approve this action
                    // if (!$user->isAdmin($user->id) || $user->company_id != $getLeave->company_id) {
                    //     DB::rollback();
                    //     // data already approved
                    //     return response()->json([
                    //         'error' => [
                    //             'status' => 501,
                    //             'message' => 'You dont have permission to using this function',
                    //         ]
                    //     ]);
                    // }
                    // // get table dinas sementara
                    // $getDinasSementara = LeaveDinasSementara::where('leave_id', $getLeave->id)
                    // ->where('is_approved_hrd', null)
                    // ->first();
                    // if (!isset($getDinasSementara) && empty($getDinasSementara)) {
                    //     DB::rollback();
                    //     return response()->json([
                    //         'error' => [
                    //             'status' => 501,
                    //             'message' => 'Someone already take action for this request',
                    //         ]
                    //     ]);
                    // }
                    // // if ($getDinasSementara->is_approved_hrd == 1) {
                    // //     DB::rollback();
                    // //     // data already approved
                    // //     return response()->json([
                    // //         'error' => [
                    // //             'status' => 501,
                    // //             'message' => 'Someone already approved this request',
                    // //         ]
                    // //     ]);
                    // // }
                    // $getDinasSementara->is_approved_hrd = 1;
                    // $getDinasSementara->approved_by = $user->id;
                    // $getDinasSementara->approved_at = Carbon::now();
                    // $getDinasSementara->save();

                    // // update leave set is final to 1
                    // $getLeave->is_final = 1;
                    // $getLeave->save();

                    // // insert into log
                    // LeaveActivity::logActivityAccepted($getLeave->id, $user);

                    // DB::commit();
                    // return response()->json([
                    //     'success' => [
                    //         'status' => 200,
                    //         'message' => 'Leave approved',
                    //         'data' => [
                    //             'leave' => $getLeave
                    //         ],
                    //     ]
                    // ]);
                }
                // TIDAK DIGUNAKAN
                // else{
                //     if (isset($arr_permission_require[1]) && !empty($arr_permission_require[1])) {
                //         if ($arr_permission_require[1] == $user->id) {
                //             // approval tingkat 2
                //             $getLeave->status = 'approved_atasan_dua';
                //             // update approved by
                //             $getLeave->approved_by = self::setApproved($user->id, $getLeave->approved_by);
                //             // check if this type is Dinas Luar Kota (need approval hrd)
                //             if ($getLeave->type_name == 'Dinas Luar Kota') {
                //                 $getLeave->is_final = 0;
                //             }
                //             $getLeave->save();
    
                //             // insert into log
                //             LeaveActivity::logActivityAccepted($getLeave->id, $user);
    
                //             DB::commit();
                //             return response()->json([
                //                 'success' => [
                //                     'status' => 200,
                //                     'message' => 'Leave approved',
                //                     'data' => [
                //                         'leave' => $getLeave
                //                     ],
                //                 ]
                //             ]);
                //         }
                //     }
                // }
            } elseif ($getLeave->status == 'approved_atasan_dua') {
                // section for HRD
                // this just for dinas luar kota
                if ($getLeave->type_name == 'Dinas Luar Kota') {
                    // check this user was HRD
                    if (!self::checkHRD($user->employeeDetail->department_id) || $user->company_id != $getLeave->company_id || $user->employeeDetail->is_atasan != 1) {
                        DB::rollback();
                        // data already approved
                        return response()->json([
                            'error' => [
                                'status' => 501,
                                'message' => 'You dont have permission to using this function',
                            ]
                        ]);
                    }
                    // get table dinas luar kota
                    $getDinasLuarKota = LeaveDinasLuarKota::where('leave_id', $getLeave->id)
                    ->where('is_approved_hrd', null)
                    ->first();
                    if (!isset($getDinasLuarKota) && empty($getDinasLuarKota)) {
                        DB::rollback();
                        return response()->json([
                            'error' => [
                                'status' => 501,
                                'message' => 'Someone already take action for this request',
                            ]
                        ]);
                    }
                    // if ($getDinasLuarKota->is_approved_hrd == 1) {
                    //     DB::rollback();
                    //     // data already approved
                    //     return response()->json([
                    //         'error' => [
                    //             'status' => 501,
                    //             'message' => 'Someone already approved this request',
                    //         ]
                    //     ]);
                    // }
                    $getDinasLuarKota->is_approved_hrd = 1;
                    $getDinasLuarKota->approved_by = $user->id;
                    $getDinasLuarKota->approved_at = Carbon::now();
                    $getDinasLuarKota->save();

                    // update leave set is final to 1
                    $getLeave->is_final = 1;
                    $getLeave->masking_status = 'done';
                    $getLeave->save();


                    // insert into log
                    LeaveActivity::logActivityAccepted($getLeave->id, $user);

                    DB::commit();

                    // send notif to sekertaris, atasan 3 and karyawan

                    // send notif to sekertaris
                    $getMemberSekertaris = EmployeeDetails::join('designations as d','d.id','employee_details.designation_id')
                        ->join('users as u','u.id','employee_details.user_id')
                        ->where('d.name', 'Sekretaris')
                        ->where('employee_details.company_id',$user->company_id)
                        ->selectRaw('employee_details.*,u.email')
                        ->get();
                    $arr_cc=[];
                    $arr_cc_id=[];
                    foreach ($getMemberSekertaris as $val) {
                        array_push($arr_cc,$val->email);
                        array_push($arr_cc_id,$val->user_id);
                        // $userNotif = User::find($val->user_id);
                        // $userRequest = User::find($getLeave->user_id);
                        // $approvedBy = User::find($user->id);
                        // $userNotif->notify(new LeaveApproved($getLeave, $userRequest,$approvedBy));
                    }

                    // send notif to karyawan
                    $userNotif = User::find($getLeave->user_id);
                    $userRequest = User::find($getLeave->user_id);
                    $approvedBy = User::find($user->id);
                    $userNotif->notify(new LeaveApproved($getLeave, $userRequest,$approvedBy,$arr_cc,$userNotif));

                    return response()->json([
                        'success' => [
                            'status' => 200,
                            'message' => 'Leave approved',
                            'data' => [
                                'leave' => $getLeave
                            ],
                        ]
                    ]);
                }elseif($getLeave->type_name == 'Ijin'){
                    // code
                    if (!self::checkHRD($user->employeeDetail->department_id) || $user->company_id != $getLeave->company_id || $user->employeeDetail->is_atasan != 1) {
                        DB::rollback();
                        // data already approved
                        return response()->json([
                            'error' => [
                                'status' => 501,
                                'message' => 'You dont have permission to using this function',
                            ]
                        ]);
                    }
                    // get table ijin
                    $getIjin = LeaveIjin::where('leave_id', $getLeave->id)
                    ->where('is_approved_hrd', null)
                    ->first();
                    if (!isset($getIjin) && empty($getIjin)) {
                        DB::rollback();
                        return response()->json([
                            'error' => [
                                'status' => 501,
                                'message' => 'Someone already take action for this request',
                            ]
                        ]);
                    }
                    $getIjin->is_approved_hrd = 1;
                    $getIjin->approved_by = $user->id;
                    $getIjin->approved_at = Carbon::now();
                    $getIjin->save();

                    // update leave set is final to 1 and set masking status to done
                    $getLeave->is_final = 1;
                    $getLeave->masking_status = 'done';
                    $getLeave->save();

                    // send notif to requester
                    $userNotifRequester = User::find($getLeave->user_id);
                    $userRequest = User::find($getLeave->user_id);
                    $approvedBy = User::find($user->id);
                    try {
                        $userNotifRequester->notify(new LeaveApproved($getLeave, $userRequest,$approvedBy,[],$userNotifRequester));
                    } catch (\Throwable $th) {
                        $flagErrorMail = true;
                    }

                    // insert into log
                    LeaveActivity::logActivityAccepted($getLeave->id, $user);

                    DB::commit();
                    return response()->json([
                        'success' => [
                            'status' => 200,
                            'message' => 'Leave approved',
                            'data' => [
                                'leave' => $getLeave
                            ],
                        ]
                    ]);
                }elseif($getLeave->type_name == 'Cuti' || $getLeave->type_name == 'Cuti 3 Bulanan'|| $getLeave->type_name == 'Cuti Custom'){
                    if (!self::checkHRD($user->employeeDetail->department_id) || $user->company_id != $getLeave->company_id || $user->employeeDetail->is_atasan != 1) {
                        DB::rollback();
                        // data already approved
                        return response()->json([
                            'error' => [
                                'status' => 501,
                                'message' => 'You dont have permission to using this function',
                            ]
                        ]);
                    }
                    // get table ijin
                    $getCuti = LeaveCuti::where('leave_id', $getLeave->id)
                    ->where('is_approved_hrd', null)
                    ->first();
                    if (!isset($getCuti) && empty($getCuti)) {
                        DB::rollback();
                        return response()->json([
                            'error' => [
                                'status' => 501,
                                'message' => 'Someone already take action for this request',
                            ]
                        ]);
                    }
                    $getCuti->is_approved_hrd = 1;
                    $getCuti->approved_by = $user->id;
                    $getCuti->approved_at = Carbon::now();
                    $getCuti->save();

                    // update leave set is final to 1 and set masking status to done
                    $getLeave->is_final = 1;
                    $getLeave->masking_status = 'done';
                    $getLeave->save();

                    // send notif to requester
                    $userNotifRequester = User::find($getLeave->user_id);
                    $userRequest = User::find($getLeave->user_id);
                    $approvedBy = User::find($user->id);
                    try {
                        $userNotifRequester->notify(new LeaveApproved($getLeave, $userRequest,$approvedBy,[],$userNotifRequester));
                    } catch (\Throwable $th) {
                        $flagErrorMail = true;
                    }

                    // insert into log
                    LeaveActivity::logActivityAccepted($getLeave->id, $user);

                    DB::commit();
                    return response()->json([
                        'success' => [
                            'status' => 200,
                            'message' => 'Leave approved',
                            'data' => [
                                'leave' => $getLeave
                            ],
                        ]
                    ]);
                }
                // TIDAK DIPAKAI
                // else {
                //     // approval atasan tiga
                //     if (isset($arr_permission_require[2]) && !empty($arr_permission_require[2])) {
                //         if ($arr_permission_require[2] == $user->id) {
                //             // approval terakhir
                //             // approval tingkat 3
                //             $getLeave->status = 'approved_atasan_tiga';
                //             // update approved by
                //             $getLeave->approved_by = self::setApproved($user->id, $getLeave->approved_by);
                //             // check if this type is Dinas Luar Kota (need approval hrd)
                //             if ($getLeave->type_name == 'Dinas Luar Kota') {
                //                 $getLeave->is_final = 0;
                //             } else {
                //                 $getLeave->is_final = 1;
                //                 // mark done all excepted dinas luar kota and dinas sementara
                //                 if ($getLeave->type_name != 'Dinas sementara') {
                //                     $getLeave->masking_status = 'done';
                //                 }
                //             }
                //             $getLeave->save();

                //             // insert into log
                //             LeaveActivity::logActivityAccepted($getLeave->id, $user);

                //             DB::commit();
                //             return response()->json([
                //                 'success' => [
                //                     'status' => 200,
                //                     'message' => 'Leave approved',
                //                     'data' => [
                //                         'leave' => $getLeave
                //                     ],
                //                 ]
                //             ]);
                //         }
                //     }
                // }
            }
            DB::rollback();
            return response()->json([
                'error' => [
                    'status' => 501,
                    'message' => 'You dont have permission to using this function',
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => [
                    'status' => 500,
                    'message' => $e->getMessage(),
                ]
            ]);
        }
    }
    public static function setApproved($data, $dataBefore)
    {
        $dataBefore = json_decode($dataBefore);
        if (empty($dataBefore)) {
            $dataBefore = [];
        }
        array_push($dataBefore, $data);
        return json_encode($dataBefore);
    }
    public static function rejectLeave($request)
    {
        $flagErrorMail = false;
        $user = auth()->user();
        // get leave
        $getLeave = Leave::join('employee_details as ed', 'ed.user_id', 'leaves.user_id')
            ->join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
            ->where('leaves.id', $request->leave_id)
            ->whereIn('leaves.status', ['pending', 'approved_atasan_satu', 'approved_atasan_dua'])
            ->select('leaves.*', 'ed.permission_require', 'lt.type_name')
            ->first();
        if (!isset($getLeave) && empty($getLeave)) {
            return response()->json([
                'error' => [
                    'status' => 404,
                    'message' => 'Data not found',
                ]
            ]);
        }
        $timezone=7;
        $timezone_attendance = Attendance::where('user_id', $user->id)->orderBy('id','desc')->first();
        if ($timezone_attendance) {
            $timezone = $timezone_attendance->clock_in_timezone;
        }
        DB::beginTransaction();
        try {
            // check if this user can approve this data 
            $arr_permission_require = $getLeave->permission_require;
            $arr_permission_require = json_decode($arr_permission_require);
            // check status
            if ($getLeave->status == 'pending') {
                if (isset($arr_permission_require[0]) && !empty($arr_permission_require[0])) {
                    if ($arr_permission_require[0] == $user->id) {
                        // approval tingkat 1
                        $getLeave->status = 'rejected_atasan_satu';
                        $getLeave->masking_status = 'rejected';
                        // update rejected by
                        $getLeave->rejected_by = $user->id;
                        $getLeave->rejected_at_with_gmt = Carbon::now()->addHours($timezone)->format('Y-m-d H:i');
                        $getLeave->reject_reason = $request->reason;
                        $getLeave->save();

                        // send notif to requester
                        $userNotifRequester = User::find($getLeave->user_id);
                        $userRequest = User::find($getLeave->user_id);
                        $approvedBy = User::find($user->id);
                        try {
                            $userNotifRequester->notify(new LeaveReject($getLeave, $userRequest,$approvedBy,[],$userNotifRequester, $getLeave->reject_reason));
                        } catch (\Throwable $th) {
                            $flagErrorMail = true;
                        }

                        // insert into log
                        LeaveActivity::logActivityRejected($getLeave->id, $user, $getLeave->reject_reason);

                        DB::commit();
                        return response()->json([
                            'success' => [
                                'status' => 200,
                                'message' => 'Leave rejected',
                                'data' => [
                                    'leave' => $getLeave
                                ],
                            ]
                        ]);
                    }
                }
            } elseif ($getLeave->status == 'approved_atasan_satu') {
                // if ($getLeave->type_name == 'Dinas sementara') {
                //     // get table dinas sementara
                //     $getDinasSementara = LeaveDinasSementara::where('leave_id', $getLeave->id)
                //         ->where('is_approved_hrd', null)
                //         ->first();
                //     if (!isset($getDinasSementara) && empty($getDinasSementara)) {
                //         DB::rollback();
                //         return response()->json([
                //             'error' => [
                //                 'status' => 501,
                //                 'message' => 'Someone already take action for this request',
                //             ]
                //         ]);
                //     }
                //     // only admin can reject this action
                //     if (!$user->isAdmin($user->id) || $user->company_id != $getLeave->company_id) {
                //         DB::rollback();
                //         // data already approved/ dont have permission
                //         return response()->json([
                //             'error' => [
                //                 'status' => 501,
                //                 'message' => 'You dont have permission to using this function',
                //             ]
                //         ]);
                //     }

                //     $getDinasSementara->is_approved_hrd = 0;
                //     $getDinasSementara->rejected_by = $user->id;
                //     $getDinasSementara->rejected_at = Carbon::now();
                //     $getDinasSementara->rejected_reason = $request->reason;
                //     $getDinasSementara->save();

                //     // update leave set is final to 1
                //     $getLeave->is_final = 1;
                //     $getLeave->masking_status = 'rejected';
                //     $getLeave->save();

                //     // insert into log
                //     LeaveActivity::logActivityRejected($getLeave->id, $user, $getLeave->reject_reason);

                //     DB::commit();
                //     return response()->json([
                //         'success' => [
                //             'status' => 200,
                //             'message' => 'Leave rejected',
                //             'data' => [
                //                 'leave' => $getLeave
                //             ],
                //         ]
                //     ]);
                // }else{
                    if ($getLeave->type_name == 'Ijin' || $getLeave->type_name == 'Cuti' || $getLeave->type_name == 'Cuti 3 Bulanan' || 
                    $getLeave->type_name == 'Cuti Custom' || $getLeave->type_name == 'Dinas Luar Kota') {
                        if (isset($arr_permission_require[1]) && !empty($arr_permission_require[1])) {
                            if ($arr_permission_require[1] == $user->id) {
                                // approval tingkat 2
                                $getLeave->status = 'rejected_atasan_dua';
                                $getLeave->masking_status = 'rejected';
                                // update rejected by
                                $getLeave->rejected_by = $user->id;
                                $getLeave->rejected_at_with_gmt = Carbon::now()->addHours($timezone)->format('Y-m-d H:i');
                                $getLeave->reject_reason = $request->reason;
                                $getLeave->save();

                                // send notif to requester
                                $userNotifRequester = User::find($getLeave->user_id);
                                $userRequest = User::find($getLeave->user_id);
                                $approvedBy = User::find($user->id);
                                try {
                                    $userNotifRequester->notify(new LeaveReject($getLeave, $userRequest,$approvedBy,[],$userNotifRequester, $getLeave->reject_reason));
                                } catch (\Throwable $th) {
                                    $flagErrorMail = true;
                                }
        
                                // insert into log
                                LeaveActivity::logActivityRejected($getLeave->id, $user, $getLeave->reject_reason);
        
                                DB::commit();
                                return response()->json([
                                    'success' => [
                                        'status' => 200,
                                        'message' => 'Leave rejected',
                                        'data' => [
                                            'leave' => $getLeave
                                        ],
                                    ]
                                ]);
                            }
                        }
                    }
                // }
            } elseif ($getLeave->status == 'approved_atasan_dua') {
                // section for HRD
                if ($getLeave->type_name == 'Dinas Luar Kota') {
                    // check this user was HRD
                    if (!self::checkHRD($user->employeeDetail->department_id) || $user->company_id != $getLeave->company_id) {
                        DB::rollback();
                        // data already approved
                        return response()->json([
                            'error' => [
                                'status' => 501,
                                'message' => 'You dont have permission to using this function',
                            ]
                        ]);
                    }
                    // get table dinas luar kota
                    $getDinasLuarKota = LeaveDinasLuarKota::where('leave_id', $getLeave->id)
                    ->where('is_approved_hrd', null)
                    ->first();
                    if (!isset($getDinasLuarKota) && empty($getDinasLuarKota)) {
                        DB::rollback();
                        return response()->json([
                            'error' => [
                                'status' => 501,
                                'message' => 'Someone already take action for this request',
                            ]
                        ]);
                    }
                    $getDinasLuarKota->is_approved_hrd = 0;
                    $getDinasLuarKota->rejected_by = $user->id;
                    $getDinasLuarKota->rejected_at = Carbon::now();
                    $getDinasLuarKota->rejected_reason = $request->reason;
                    $getDinasLuarKota->save();

                    // update leave set is final to 1
                    $getLeave->is_final = 1;
                    $getLeave->masking_status = 'rejected';
                    $getLeave->save();

                    // send notif to requester
                    $userNotifRequester = User::find($getLeave->user_id);
                    $userRequest = User::find($getLeave->user_id);
                    $approvedBy = User::find($user->id);
                    try {
                        $userNotifRequester->notify(new LeaveReject($getLeave, $userRequest,$approvedBy,[],$userNotifRequester, $getDinasLuarKota->rejected_reason));
                    } catch (\Throwable $th) {
                        $flagErrorMail = true;
                    }

                    // insert into log
                    LeaveActivity::logActivityRejected($getLeave->id, $user, $getLeave->reject_reason);

                    DB::commit();
                    return response()->json([
                        'success' => [
                            'status' => 200,
                            'message' => 'Leave rejected',
                            'data' => [
                                'leave' => $getLeave
                            ],
                        ]
                    ]);
                }elseif($getLeave->type_name == 'Ijin'){ 
                    // code
                    if (!self::checkHRD($user->employeeDetail->department_id) || $user->company_id != $getLeave->company_id) {
                        DB::rollback();
                        // data already approved
                        return response()->json([
                            'error' => [
                                'status' => 501,
                                'message' => 'You dont have permission to using this function',
                            ]
                        ]);
                    }
                    // get table ijin
                    $getIjin = LeaveIjin::where('leave_id', $getLeave->id)
                    ->where('is_approved_hrd', null)
                    ->first();
                    if (!isset($getIjin) && empty($getIjin)) {
                        DB::rollback();
                        return response()->json([
                            'error' => [
                                'status' => 501,
                                'message' => 'Someone already take action for this request',
                            ]
                        ]);
                    }
                    $getIjin->is_approved_hrd = 0;
                    $getIjin->rejected_by = $user->id;
                    $getIjin->rejected_at = Carbon::now();
                    $getIjin->rejected_reason = $request->reason;
                    $getIjin->save();

                    // update leave set is final to 1
                    $getLeave->is_final = 1;
                    $getLeave->masking_status = 'rejected';
                    $getLeave->save();

                    // send notif to requester
                    $userNotifRequester = User::find($getLeave->user_id);
                    $userRequest = User::find($getLeave->user_id);
                    $approvedBy = User::find($user->id);
                    try {
                        $userNotifRequester->notify(new LeaveReject($getLeave, $userRequest,$approvedBy,[],$userNotifRequester, $getIjin->rejected_reason));
                    } catch (\Throwable $th) {
                        $flagErrorMail = true;
                    }

                    // insert into log
                    LeaveActivity::logActivityRejected($getLeave->id, $user, $getLeave->reject_reason);

                    DB::commit();
                    return response()->json([
                        'success' => [
                            'status' => 200,
                            'message' => 'Leave rejected',
                            'data' => [
                                'leave' => $getLeave
                            ],
                        ]
                    ]);
                }elseif($getLeave->type_name == 'Cuti' || $getLeave->type_name == 'Cuti 3 Bulanan' || $getLeave->type_name == 'Cuti Custom'){
                    if (!self::checkHRD($user->employeeDetail->department_id) || $user->company_id != $getLeave->company_id) {
                        DB::rollback();
                        // data already approved
                        return response()->json([
                            'error' => [
                                'status' => 501,
                                'message' => 'You dont have permission to using this function',
                            ]
                        ]);
                    }
                    // get table ijin
                    $getCuti = LeaveCuti::where('leave_id', $getLeave->id)
                    ->where('is_approved_hrd', null)
                    ->first();
                    if (!isset($getCuti) && empty($getCuti)) {
                        DB::rollback();
                        return response()->json([
                            'error' => [
                                'status' => 501,
                                'message' => 'Someone already take action for this request',
                            ]
                        ]);
                    }
                    $getCuti->is_approved_hrd = 0;
                    $getCuti->rejected_by = $user->id;
                    $getCuti->rejected_at = Carbon::now();
                    $getCuti->rejected_reason = $request->reason;
                    $getCuti->save();

                    // update leave set is final to 1
                    $getLeave->is_final = 1;
                    $getLeave->masking_status = 'rejected';
                    $getLeave->save();

                    // send notif to requester
                    $userNotifRequester = User::find($getLeave->user_id);
                    $userRequest = User::find($getLeave->user_id);
                    $approvedBy = User::find($user->id);
                    try {
                        $userNotifRequester->notify(new LeaveReject($getLeave, $userRequest,$approvedBy,[],$userNotifRequester, $getCuti->rejected_reason));
                    } catch (\Throwable $th) {
                        $flagErrorMail = true;
                    }

                    // insert into log
                    LeaveActivity::logActivityRejected($getLeave->id, $user, $getLeave->reject_reason);

                    DB::commit();
                    return response()->json([
                        'success' => [
                            'status' => 200,
                            'message' => 'Leave rejected',
                            'data' => [
                                'leave' => $getLeave
                            ],
                        ]
                    ]);
                }
            }
            DB::rollback();
            return response()->json([
                'error' => [
                    'status' => 501,
                    'message' => 'You dont have permission to using this function',
                ]
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
    public static function leaveTaken($user_id, $year = null){
        try {
            $user = DB::table('users')->where('id', $user_id)->first();
            if (!isset($user) && empty($user)) {
                throw new \Exception("User not found");
            }
            if (empty($year)) {
                $year = Carbon::now()->format('Y');
            }
            $approvedLeave = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
            ->leftjoin('leave_cutis as lc','lc.leave_id','leaves.id')
            ->leftjoin('tipe_cutis as tc','tc.id','lc.kategori_cuti')
            ->whereIn('lt.type_name', ['Cuti','Cuti 3 Bulanan','Cuti Custom'])
            ->whereIn('leaves.masking_status', ['in progress', 'done'])
            ->where('leaves.user_id',$user->id)
            ->where('leaves.company_id',$user->company_id)
            ->where(function($query) use ($year){
                $query->whereYear('leaves.leave_date',$year)
                ->orWhereYear('leaves.leave_date',$year);
            })
            ->selectRaw('leaves.*,lt.type_name,lt.no_of_leaves,tc.name as type_cuti')
            ->get();
            $tipeCutiOriginal = TipeCuti::where('company_id', $user->company_id)->pluck('limit','name')->toArray();
            $leave_type = LeaveType::where('company_id', $user->company_id)->pluck('no_of_leaves','type_name')->toArray();
            $tipeCutiArr = array_merge(array(), $tipeCutiOriginal);
            $arrCutiNormal=[
                "Cuti Tahunan" => [
                    "limit" => $leave_type['Cuti'],
                    "leave_taken" => 0,
                    "leave_remaining" => $leave_type['Cuti'],
                ],
                "Cuti 3 Bulanan" => [
                    "limit" => $leave_type['Cuti 3 Bulanan'],
                    "leave_taken" => 0,
                    "leave_remaining" => $leave_type['Cuti 3 Bulanan'],
                ],
            ];
            foreach ($approvedLeave as $val) {
                $diff =0;
                // parse to carbon object
                $start_date = Carbon::parse($val->leave_date);
                $end_date = Carbon::parse($val->leave_date_end);

                if ($val->type_name=='Cuti 3 Bulanan') {
                    $date_start_counter = Carbon::now();
                    $date_start_counter_month = (int)$date_start_counter->copy()->format('m');
                    if ($date_start_counter_month>=1 && $date_start_counter_month <=3) {
                        $date_start_counter = Carbon::parse($year.'-01-01');
                    }elseif($date_start_counter_month>=4 && $date_start_counter_month <=6){
                        $date_start_counter = Carbon::parse($year.'-04-01');
                        
                        
                    }elseif($date_start_counter_month>=7 && $date_start_counter_month <=9){
                        $date_start_counter = Carbon::parse($year.'-07-01');
                        
                    }elseif($date_start_counter_month>=10 && $date_start_counter_month <=12){
                        $date_start_counter = Carbon::parse($year.'-10-01');
                    }
                    
                    $date_end_counter = $date_start_counter->copy()->addMonths(2)->endOfMonth();
                    
                    // dd($date_end_counter);
                    // if ($val->id == '2165') {
                    //     # code...
                    //     dd($date_end_counter->copy()->format('Y-m-d')
                    //     ,'<=', $end_date->copy()->format('Y-m-d'));
                    // }
                    // if ($start_date->copy()->format('Y-m-d') >= $date_start_counter->copy()->format('Y-m-d') && $date_end_counter->copy()->format('Y-m-d')
                    // <= $end_date->copy()->format('Y-m-d')) {
                    if ($start_date->copy()->format('Y-m-d') >= $date_start_counter->copy()->format('Y-m-d')  && 
                    $end_date->copy()->format('Y-m-d') <=$date_end_counter->copy()->format('Y-m-d')) {
                        if ($start_date->copy()->format('Y')==$year && $end_date->copy()->format('Y')==$year) {
                            // logic date diff from start until end
                            $diff = $end_date->copy()->diffInDays($start_date)+1;
                        }else{
                            $dateEndOfYear = Carbon::now();
                            $dateEndOfYear = $dateEndOfYear->endOfYear();
                            $diff = $dateEndOfYear->diffInDays($start_date)+1;
                        }
                    }
                }else{
                    if ($start_date->copy()->format('Y')==$year && $end_date->copy()->format('Y')==$year) {
                        // logic date diff from start until end
                        $diff = $end_date->copy()->diffInDays($start_date)+1;
                    }else{
                        $dateEndOfYear = Carbon::now();
                        $dateEndOfYear = $dateEndOfYear->endOfYear();
                        $diff = $dateEndOfYear->diffInDays($start_date)+1;
                    }
                }
                // if ($start_date->copy()->format('Y')==$year && $end_date->copy()->format('Y')==$year) {
                //     // logic date diff from start until end
                //     $diff = $end_date->copy()->diffInDays($start_date)+1;
                // }else{
                //     $dateEndOfYear = Carbon::now();
                //     $dateEndOfYear = $dateEndOfYear->endOfYear();
                //     $diff = $dateEndOfYear->diffInDays($start_date)+1;
                // }
                if ($val->type_name=='Cuti') {
                    $arrCutiNormal["Cuti Tahunan"] = [
                        "limit" => $val->no_of_leaves,
                        "leave_taken" => $arrCutiNormal["Cuti Tahunan"]['leave_taken']+$diff,
                        "leave_remaining" => $val->no_of_leaves - ($arrCutiNormal["Cuti Tahunan"]['leave_taken']+$diff),
                    ];
                }elseif($val->type_name=='Cuti 3 Bulanan'){
                    // cuti 3 bulanan
                    // dd($diff);
                    $arrCutiNormal["Cuti 3 Bulanan"] = [
                        "limit" => $val->no_of_leaves,
                        "leave_taken" => $arrCutiNormal["Cuti 3 Bulanan"]['leave_taken']+$diff,
                        "leave_remaining" => $val->no_of_leaves - ($arrCutiNormal["Cuti 3 Bulanan"]['leave_taken']+$diff),
                    ];
                }else{
                    if (empty($val->type_cuti)) {
                        $tipeCutiArr['umum'] = [
                            "limit" => 0,
                            "leave_taken" => 0+$diff,
                        ];
                    }else{
                        $tipeCutiArr[$val->type_cuti] = [
                            "limit" => $tipeCutiOriginal[$val->type_cuti],
                            "leave_taken" => isset($tipeCutiArr[$val->type_cuti]['leave_taken']) && !empty($tipeCutiArr[$val->type_cuti]['leave_taken'])?$tipeCutiArr[$val->type_cuti]['leave_taken']+$diff:0+$diff,
                        ];
                    }
                }
            }
            foreach ($tipeCutiArr as $key => $val) {
                if (!isset($val['limit'])) {
                    $tipeCutiArr[$key]=[
                        "limit" => $tipeCutiOriginal[$key],
                        "leave_taken" => 0,
                        "leave_remaining" => $tipeCutiOriginal[$key],
                    ];
                }else{
                    $tipeCutiArr[$key]=[
                        "limit" => $tipeCutiArr[$key]['limit'],
                        "leave_taken" => $tipeCutiArr[$key]['leave_taken'],
                        // "leave_remaining" => $tipeCutiArr[$key]['limit'] - $tipeCutiArr[$key]['leave_taken'],
                        "leave_remaining" => ($tipeCutiArr[$key]['limit'] - $tipeCutiArr[$key]['leave_taken'])<0?0:$tipeCutiArr[$key]['limit'] - $tipeCutiArr[$key]['leave_taken'],
                    ];
                }
            }
            return [
                'status' => 200,
                'message' => 'Data found',
                'data' => array_merge($arrCutiNormal,$tipeCutiArr)
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 500,
                'message' => $e->getMessage(),
            ];
        }
        
    }
    public static function getLaporanLeadtime(
            $tanggal_mulai_pembuatan_ijin,
            $tanggal_berakhir_pembuatan_ijin,
            $pembuat_ijin,
            $sub_company_id,
            $wilayah_id,
            $department_id,
            $status,
            $atasan_1,
            $atasan_2,
            $target_hrd,
            $office_id
        ){
        // dd($tanggal_mulai_pembuatan_ijin,$tanggal_berakhir_pembuatan_ijin,$pembuat_ijin,$sub_company_id,$wilayah_id,$department_id,$status);
        try {
            $leave_start_created = Carbon::parse($tanggal_mulai_pembuatan_ijin);
            $leave_end_created = Carbon::parse($tanggal_berakhir_pembuatan_ijin);
            $status = str_replace('+',' ',$status);
            // get leave by user
            $leaves = Leave::join('employee_details as ed','ed.user_id','leaves.user_id')
                ->join('users as u','u.id','ed.user_id')
                ->join('leave_types as lt','lt.id','leaves.leave_type_id')
                ->leftjoin('sub_company as sc','sc.id','ed.sub_company_id')
                ->leftjoin('wilayah as w','w.id','ed.wilayah_id')
                ->leftjoin('teams as t','t.id','ed.department_id')
                ->where('ed.sub_company_id',$sub_company_id);
            if ($status!=0) {
                $leaves = $leaves ->where('leaves.masking_status',$status);
            }
            if ($wilayah_id!=0) {
                $leaves = $leaves->where('ed.wilayah_id',$wilayah_id);
            }
            if ($department_id!=0) {
                $leaves = $leaves->where('ed.department_id',$department_id);
            }
            if ($pembuat_ijin!=0) {
                $leaves = $leaves->where('leaves.user_id',$pembuat_ijin);
            }
            if ($atasan_1!=0) {
                $leaves = $leaves->where('ed.permission_require','like',"[\"" . $atasan_1 . "\"%");
            }
            if ($atasan_2!=0) {
                $leaves = $leaves->where('ed.permission_require','like',"%,\"" . $atasan_2 . "\",%");
            }
            if ($office_id!=0) {
                $leaves = $leaves->where('ed.office_id', $office_id);
            }
            $leaves = $leaves->whereBetween('leaves.created_at',[$leave_start_created->copy()->format('Y-m-d'),$leave_end_created->copy()->format('Y-m-d')])
                ->select(
                    'leaves.*',
                    'u.name as pembuat_ijin',
                    'ed.permission_require',
                    'lt.type_name',
                    'lt.display_name as jenis_ijin',
                    'sc.name as sub_company_name',
                    'w.name as wilayah_name',
                    't.team_name as department_name',
                    'leaves.created_at as leave_created_at',
                    )
                ->get();
            $response =[];
            foreach ($leaves as $leave) {
                $timezone=7;
                $timezone_attendance = Attendance::where('user_id', $leave->user_id)->orderBy('id','desc')->first();
                if ($timezone_attendance) {
                    $timezone = $timezone_attendance->clock_in_timezone;
                }
                $leave_created = Carbon::parse($leave->leave_created_at);
                // get permission require
                $permission_require = json_decode($leave->permission_require);
                // init
                $atasan_1_id= null;
                $atasan_2_id= null;
                $atasan_3_id= null;
                $atasan_1= '-';
                $atasan_2= '-';
                $atasan_3= '-';
                $approved_date_atasan_1= null;
                $approved_date_atasan_2= null;
                $approved_date_atasan_3= null;
                $approved_time_atasan_1= null;
                $approved_time_atasan_2= null;
                $approved_time_atasan_3= null;
                $lead_time_atasan_1= 0;
                $lead_time_atasan_2= 0;
                $lead_time_atasan_3= 0;
                $approved_at_atasan_1= null;
                $approved_at_atasan_2= null;
                $approved_at_atasan_3= null;
                $jenis_ijin =$leave->jenis_ijin;

                if (isset($permission_require[0]) && !empty($permission_require[0])){ 
                    //atasan 1
                    $atasan_1 = Db::table('users')->find($permission_require[0]);
                    if ($atasan_1) {
                        $atasan_1_id = $atasan_1->id;
                        $atasan_1= $atasan_1->name;
                    }
                }
                if (isset($permission_require[1]) && !empty($permission_require[1])){ 
                    //atasan 2
                    $atasan_2 = Db::table('users')->find($permission_require[1]);
                    if ($atasan_2) {
                        $atasan_2_id = $atasan_2->id;
                        $atasan_2= $atasan_2->name;
                    }
                }
                if (isset($permission_require[2]) && !empty($permission_require[2])){ 
                    //atasan 3
                    $atasan_3 = Db::table('users')->find($permission_require[2]);
                    if ($atasan_3) {
                        $atasan_3_id = $atasan_3->id;
                        $atasan_3= $atasan_3->name;
                    }
                }
                if ($leave->approved_by=='system') {
                    if ($timezone>0) {
                        $approved_at_all_atasan = Carbon::parse($leave->created_at)->addHours($timezone);
                    }else{
                        $approved_at_all_atasan = Carbon::parse($leave->created_at)->subHours(abs($timezone));
                    }
                    $approved_date_atasan_1 = $approved_at_all_atasan->copy()->format('d-m-Y');
                    $approved_time_atasan_1 = $approved_at_all_atasan->copy()->format('H:i');
                    $lead_time_atasan_1 = $approved_at_all_atasan->copy()->diffInDays($leave_created);

                    $approved_date_atasan_2 = $approved_at_all_atasan->copy()->format('d-m-Y');
                    $approved_time_atasan_2 = $approved_at_all_atasan->copy()->format('H:i');
                    $lead_time_atasan_2 = $approved_at_all_atasan->copy()->diffInDays($leave_created);

                    $approved_date_atasan_3 = $approved_at_all_atasan->copy()->format('d-m-Y');
                    $approved_time_atasan_3 = $approved_at_all_atasan->copy()->format('H:i');
                    $lead_time_atasan_3 = $approved_at_all_atasan->copy()->diffInDays($leave_created);
                }else{
                    $approved_at = json_decode($leave->approved_at_with_gmt);
                    if (isset($approved_at[0]) && !empty($approved_at[0])){ 
                        //atasan 1
                        $approved_at_atasan_1 = Carbon::parse($approved_at[0]);
                        $approved_date_atasan_1 = $approved_at_atasan_1->copy()->format('d-m-Y');
                        $approved_time_atasan_1 = $approved_at_atasan_1->copy()->format('H:i');
                        $lead_time_atasan_1 = $approved_at_atasan_1->copy()->diffInDays($leave_created);
                    }
                    if (isset($approved_at[1]) && !empty($approved_at[1])){ 
                        //atasan 2
                        $approved_at_atasan_2 = Carbon::parse($approved_at[1]);
                        $approved_date_atasan_2 = $approved_at_atasan_2->copy()->format('d-m-Y');
                        $approved_time_atasan_2 = $approved_at_atasan_2->copy()->format('H:i');
                        if (!empty($approved_at_atasan_1)) {
                            $lead_time_atasan_2 = $approved_at_atasan_2->copy()->diffInDays($approved_at_atasan_1);
                        }
                    }
                }
                // if (isset($approved_at[2]) && !empty($approved_at[2])){ 
                //     //atasan 3
                //     $approved_at_atasan_3 = Carbon::parse($approved_at[2]);
                //     $approved_date_atasan_2 = $approved_at_atasan_3->copy()->format('d-m-Y');
                //     $approved_time_atasan_2 = $approved_at_atasan_3->copy()->format('H:i');
                //     $lead_time_atasan_3 = $approved_at_atasan_3->copy()->diffInDays($approved_at_atasan_2);
                // }

                // check type of leave for hrd
                $hrd_id = null;
                $hrd = null;
                $hrd_approved_at = null;
                $hrd_date_approved_at = null;
                $hrd_time_approved_at = null;
                $hrd_lead_time = 0;
                
                if ($leave->type_name=='Ijin') {
                    $child = LeaveIjin::where('leave_id', $leave->id)->first();
                    $hrd = EmployeeDetails::join('teams as t','t.id','employee_details.department_id')
                            ->join('users as u','u.id','employee_details.user_id')
                            ->where('t.is_hrd',1)
                            ->where('employee_details.is_atasan',1)
                            ->where('employee_details.company_id',$leave->company_id)
                            ->where('employee_details.sub_company_id',$sub_company_id)
                            ->selectRaw('u.*')
                            ->first();
                    if (empty($hrd)) {
                        $sub_company = SubCompany::find($sub_company_id);
                        if ($sub_company) {
                            // exist
                            if (!empty($sub_company->hrd)) {
                                // find the user
                                $hrd = DB::table('users')->find($sub_company->hrd);
                            }
                        }
                    }
                    
                    $hrd_id= $hrd->id;
                    $hrd= $hrd->name;
                    if (!empty($child->approved_by)) {
                        $hrd_approved_at = Carbon::parse($child->approved_at);

                        // get timezone
                        $timezone= Attendance::getTimezoneByUserId($child->approved_by);
                        if ($timezone>0) {
                            $hrd_approved_at = $hrd_approved_at->addHours($timezone);
                        }else{
                            $hrd_approved_at = $hrd_approved_at->subHours(abs($timezone));
                        }
                        
                        // $hrd = DB::table('users')->find($child->approved_by);

                        $hrd_date_approved_at = $hrd_approved_at->copy()->format('d-m-Y');
                        $hrd_time_approved_at = $hrd_approved_at->copy()->format('H:i');
                        if (!empty($approved_at_atasan_2)) {
                            $hrd_lead_time = $hrd_approved_at->copy()->diffInDays($approved_at_atasan_2);
                        }
                    }
                }elseif($leave->type_name=='Cuti' || $leave->type_name=='Cuti 3 Bulanan' || $leave->type_name=='Cuti Custom'){
                    $child = LeaveCuti::leftjoin('tipe_cutis as tc','tc.id','leave_cutis.kategori_cuti')
                        ->where('leave_id', $leave->id)
                        ->select('leave_cutis.*','tc.name as tipe_cuti')
                        ->first();
                    $jenis_ijin = !empty($child->tipe_cuti)?$child->tipe_cuti:$jenis_ijin;
                    $hrd = EmployeeDetails::join('teams as t','t.id','employee_details.department_id')
                            ->join('users as u','u.id','employee_details.user_id')
                            ->where('t.is_hrd',1)
                            ->where('employee_details.is_atasan',1)
                            ->where('employee_details.company_id',$leave->company_id)
                            ->where('employee_details.sub_company_id',$sub_company_id)
                            ->selectRaw('u.*')
                            ->first();
                    if (empty($hrd)) {
                        $sub_company = SubCompany::find($sub_company_id);
                        if ($sub_company) {
                            // exist
                            if (!empty($sub_company->hrd)) {
                                // find the user
                                $hrd = DB::table('users')->find($sub_company->hrd);
                            }
                        }
                    }
                    
                    $hrd_id= $hrd->id;
                    $hrd= $hrd->name;
                    if (!empty($child->approved_by)) {
                        $hrd_approved_at = Carbon::parse($child->approved_at);

                        // get timezone
                        $timezone= Attendance::getTimezoneByUserId($child->approved_by);
                        if ($timezone>0) {
                            $hrd_approved_at = $hrd_approved_at->addHours($timezone);
                        }else{
                            $hrd_approved_at = $hrd_approved_at->subHours(abs($timezone));
                        }

                        $hrd_date_approved_at = $hrd_approved_at->copy()->format('d-m-Y');
                        $hrd_time_approved_at = $hrd_approved_at->copy()->format('H:i');
                        if (!empty($approved_at_atasan_2)) {
                            $hrd_lead_time = $hrd_approved_at->copy()->diffInDays($approved_at_atasan_2);
                        }
                    }
                }elseif($leave->type_name=='Dinas sementara'){
                    // tanpa HRD
                }elseif($leave->type_name=='Dinas Luar Kota'){
                    $child = LeaveDinasLuarKota::where('leave_id', $leave->id)->first();
                    $hrd = EmployeeDetails::join('teams as t','t.id','employee_details.department_id')
                            ->join('users as u','u.id','employee_details.user_id')
                            ->where('t.is_hrd',1)
                            ->where('employee_details.is_atasan',1)
                            ->where('employee_details.company_id',$leave->company_id)
                            ->where('employee_details.sub_company_id',$sub_company_id)
                            ->selectRaw('u.*')
                            ->first();
                    if (empty($hrd)) {
                        $sub_company = SubCompany::find($sub_company_id);
                        if ($sub_company) {
                            // exist
                            if (!empty($sub_company->hrd)) {
                                // find the user
                                $hrd = DB::table('users')->find($sub_company->hrd);
                            }
                        }
                    }
                    
                    $hrd_id= $hrd->id;
                    $hrd= $hrd->name;
                    if (!empty($child->approved_by)) {
                        $hrd_approved_at = Carbon::parse($child->approved_at);

                        // get timezone
                        $timezone= Attendance::getTimezoneByUserId($child->approved_by);
                        if ($timezone>0) {
                            $hrd_approved_at = $hrd_approved_at->addHours($timezone);
                        }else{
                            $hrd_approved_at = $hrd_approved_at->subHours(abs($timezone));
                        }

                        $hrd_date_approved_at = $hrd_approved_at->copy()->format('d-m-Y');
                        $hrd_time_approved_at = $hrd_approved_at->copy()->format('H:i');
                        if (!empty($approved_at_atasan_2)) {
                            $hrd_lead_time = $hrd_approved_at->copy()->diffInDays($approved_at_atasan_2);
                        }
                    }
                }else{
                    //code
                }
                // deskripsi_ijin
                if ($leave->approved_by=='system') {
                    $leave->reason = $leave->reason.' (SYSTEM'.')';
                }
                if ($target_hrd != 0) {
                    if ($target_hrd==$hrd_id) {
                        $response[] = [
                            "tanggal_pembuatan_ijin"=>$leave_created->copy()->format('d-m-Y'),
                            "nama_pt"=>$leave->sub_company_name,
                            "departemen"=>$leave->department_name,
                            "wilayah"=>$leave->wilayah_name,
                            "pembuat_ijin"=>$leave->pembuat_ijin,
                            "jenis_ijin"=>$leave->jenis_ijin,
                            "deskripsi_ijin"=>$leave->reason,
                            "tgl_awal_ijin"=>Carbon::parse($leave->leave_date)->format('d-m-Y'),
                            "tgl_akhir_ijin"=>Carbon::parse($leave->leave_date_end)->format('d-m-Y'),
                            "status"=>$leave->masking_status,
                            "approval_1"=>[
                                "nama"=>$atasan_1,
                                "tanggal"=>$approved_date_atasan_1,
                                "jam"=>$approved_time_atasan_1,
                                "leadtime"=>$lead_time_atasan_1,
                            ],
                            "approval_2"=>[
                                "nama"=>$atasan_2,
                                "tanggal"=>$approved_date_atasan_2,
                                "jam"=>$approved_time_atasan_2,
                                "leadtime"=>$lead_time_atasan_2,
                            ],
                            "hrd"=>[
                                "nama"=>$hrd,
                                "tanggal"=>$hrd_date_approved_at,
                                "jam"=>$hrd_time_approved_at,
                                "leadtime"=>$hrd_lead_time,
                            ],
                            "total"=>$lead_time_atasan_1+$lead_time_atasan_2+$hrd_lead_time
                        ];
                    }
                }else{
                    $response[] = [
                        "tanggal_pembuatan_ijin"=>$leave_created->copy()->format('d-m-Y'),
                        "nama_pt"=>$leave->sub_company_name,
                        "departemen"=>$leave->department_name,
                        "wilayah"=>$leave->wilayah_name,
                        "pembuat_ijin"=>$leave->pembuat_ijin,
                        "jenis_ijin"=>$leave->jenis_ijin,
                        "deskripsi_ijin"=>$leave->reason,
                        "tgl_awal_ijin"=>Carbon::parse($leave->leave_date)->format('d-m-Y'),
                        "tgl_akhir_ijin"=>Carbon::parse($leave->leave_date_end)->format('d-m-Y'),
                        "status"=>$leave->masking_status,
                        "approval_1"=>[
                            "nama"=>$atasan_1,
                            "tanggal"=>$approved_date_atasan_1,
                            "jam"=>$approved_time_atasan_1,
                            "leadtime"=>$lead_time_atasan_1,
                        ],
                        "approval_2"=>[
                            "nama"=>$atasan_2,
                            "tanggal"=>$approved_date_atasan_2,
                            "jam"=>$approved_time_atasan_2,
                            "leadtime"=>$lead_time_atasan_2,
                        ],
                        "hrd"=>[
                            "nama"=>$hrd,
                            "tanggal"=>$hrd_date_approved_at,
                            "jam"=>$hrd_time_approved_at,
                            "leadtime"=>$hrd_lead_time,
                        ],
                        "total"=>$lead_time_atasan_1+$lead_time_atasan_2+$hrd_lead_time
                    ];
                }
            }
            return [
                'status' => 200,
                'message' => 'Data found',
                'data' => $response
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 500,
                'message' => $e->getMessage(),
            ];
        }
    }
}
