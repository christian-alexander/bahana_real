<?php

namespace App\Observers;

use App\Leave;
use App\LeaveActivity;
use App\Notifications\LeaveApplication;
use App\Notifications\LeaveStatusApprove;
use App\Notifications\LeaveStatusReject;
use App\Notifications\LeaveStatusUpdate;
use App\Notifications\NewLeaveRequest;
use App\User;
use App\EmployeeDetails;

class LeaveObserver
{
    /**
     * Handle the leave "saving" event.
     *
     * @param  \App\Leave  $leave
     * @return void
     */
    public function saving(Leave $leave)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $leave->company_id = company()->id;
        }
    }

    public function created(Leave $leave)
    {
        if (!isRunningInConsoleOrSeeding()) {
            // $leave->user->notify(new LeaveApplication($leave));

            // Send notification to user
            //$notifyUsers = User::allAdmins();
            //foreach ($notifyUsers as $notifyUser) {
            //$notifyUser->notify(new NewLeaveRequest($leave));
            //}
        }
        // TODO: kirim ke semua atasan user leave ini
        // get atasan
        //   $getUser = EmployeeDetails::where('user_id',$leave->user_id)
        //   ->first();
        //   $getAtasan = json_decode($getUser->permission_require);
        //   if(count($getAtasan)>0){
        //     foreach($getAtasan as $atasan){
        //       if(!empty($atasan)){
        //        	$notifTo = User::find($atasan);
        //     	$notifTo->notify(new NewLeaveRequest($leave)); 
        //       }  
        //     }
        //   }
        // insert into activity
        $leave_activities = new LeaveActivity;
        $leave_activities->leave_id = $leave->id;
        $leave_activities->triggered_by = $leave->user_id;
        // find user
        $user = User::find($leave->user_id);
        $leave_activities->event = $user->name . ' mengajukan izin ini';
        $leave_activities->save();
    }

    public function updated(Leave $leave)
    {
        $flagErrorMail = false;
        if (!app()->runningInConsole()) {
            // Send from ManageLeavesController
            if ($leave->isDirty('status')) {

                if ($leave->status == 'approved') {
                    try {
                        $leave->user->notify(new LeaveStatusApprove($leave));
                    } catch (\Throwable $th) {
                        $flagErrorMail = true;
                    }
                    // $leave->user->notify(new LeaveStatusApprove($leave));
                } else {
                    try {
                        $leave->user->notify(new LeaveStatusReject($leave));
                    } catch (\Throwable $th) {
                        $flagErrorMail = true;
                    }
                    // $leave->user->notify(new LeaveStatusReject($leave));
                }
            } else {
                // Send notification to user
                try {
                    $leave->user->notify(new LeaveStatusUpdate($leave));
                } catch (\Throwable $th) {
                    $flagErrorMail = true;
                }
                // $leave->user->notify(new LeaveStatusUpdate($leave));
            }
        }
    }
}
