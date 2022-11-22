<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveActivity extends Model
{
    //
    protected $table = 'leave_activities';
    public static function logActivityAccepted($activity_id, $user)
    {
        // insert into activity
        $leave_activities = new LeaveActivity;
        $leave_activities->leave_id = $activity_id;
        $leave_activities->triggered_by = $user->id;
        $leave_activities->event = $user->name . ' menyetujui pengajuan ini';
        $leave_activities->save();
    }
    public static function logActivityRejected($activity_id, $user, $reason)
    {
        // insert into activity
        $leave_activities = new LeaveActivity;
        $leave_activities->leave_id = $activity_id;
        $leave_activities->triggered_by = $user->id;
        $leave_activities->event = $user->name . ' menolak pengajuan ini dengan alasan ' . $reason;
        $leave_activities->save();
    }
    public static function logActivityMarkToDone($activity_id, $user)
    {
        // insert into activity
        $leave_activities = new LeaveActivity;
        $leave_activities->leave_id = $activity_id;
        $leave_activities->triggered_by = $user->id;
        $leave_activities->event = $user->name . ' menyelesaikan pengajuan ini';
        $leave_activities->save();
    }
    public static function logActivityNeedAccommodation($activity_id, $user)
    {
        // insert into activity
        $leave_activities = new LeaveActivity;
        $leave_activities->leave_id = $activity_id;
        $leave_activities->triggered_by = $user->id;
        $leave_activities->event = $user->name . ' membutuhkan akomodasi untuk pengajuan ini';
        $leave_activities->save();
    }
}
