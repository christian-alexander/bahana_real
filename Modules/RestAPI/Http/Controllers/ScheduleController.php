<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Helper\Files;
use App\Http\Requests\API\APIRequest;
use App\Notifications\ScheduleCreate;
use App\Notifications\ScheduleUpdated;
use App\Schedule;
use App\ScheduleFinish;
use App\ScheduleFinishInvitation;
use App\ScheduleFinishMedia;
use App\ScheduleInvitation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\User;
use Froiden\RestAPI\ApiResponse;

class ScheduleController extends ApiBaseController
{
    public function indexSchedule(APIRequest $request)
    {
        $request->validate([
            'limit' => 'required',
        ]);
        $data = Schedule::join('users as u', 'u.id', 'schedules.pic')
            ->join('users as u2', 'u2.id', 'schedules.created_by')
            ->selectRaw('schedules.*,u.name as pic_name,u2.name as created_by_name')
            ->paginate($request->limit);
        return ApiResponse::make('Successfully added schedule meeting', [
            'schedule' => $data
        ]);
    }
    public function createSchedule(APIRequest $request)
    {
        $flagErrorMail = false;
        $request->validate([
            'subject' => 'required',
            'description' => 'required',
            'date' => 'required|date_format:Y-m-d', // Y-m-d
            'time' => 'required|date_format:H:i', // H:i
            'pic' => 'required',
            'invitation' => 'required',
            // 'parent_id' => 'required',
        ]);
        $user = auth()->user();
        // check pic exist
        // pic = user
        $getPIC = User::find($request->pic);
        if (!isset($getPIC) && empty($getPIC)) {
            return response()->json([
                'error' => [
                    'status' => 404,
                    'message' => 'PIC not found',
                ]
            ]);
        }
        // check invitation valid
        // expected type is array
        $invitation = $request->invitation;
        foreach ($invitation as $invit) {
            $checkUserInvit = User::find($invit);
            if (!isset($checkUserInvit) && empty($checkUserInvit)) {
                return response()->json([
                    'error' => [
                        'status' => 404,
                        'message' => 'Invitation not found',
                    ]
                ]);
            }
        }
        // check parent
        if (isset($request->parent_id) && !empty($request->parent_id)) {
            // check parent exist
            $checkParent = Schedule::find($request->parent_id);
            if (!isset($checkParent) && empty($checkParent)) {
                return response()->json([
                    'error' => [
                        'status' => 404,
                        'message' => 'Parent not found',
                    ]
                ]);
            }
        }
        // data ready to save
        DB::beginTransaction();
        try {
            $model = new Schedule;
            $model->parent_id = $request->parent_id;
            $model->pic = $request->pic;
            $model->subject = $request->subject;
            $model->description = $request->description;
            $model->date = $request->date;
            $model->time = $request->time;
            $model->save();

            // save to invitation
            foreach ($invitation as $val) {
                $model_invitation = new ScheduleInvitation;
                $model_invitation->schedules_id = $model->id;
                $model_invitation->user_id = $val;
                $model_invitation->save();

                // send notif to invitation (not tested)
                $userNotif = User::find($val);
                try {
                    $userNotif->notify(new ScheduleCreate($model));
                } catch (\Throwable $th) {
                    $flagErrorMail = true;
                }
                // $userNotif->notify(new ScheduleCreate($model));
            }
            DB::commit();
            if ($flagErrorMail) {
                return ApiResponse::make('Successfully added schedule meeting, Email error silahkan hubungi developer', [
                    'schedule' => $model
                ]);
            }else{
                return ApiResponse::make('Successfully added schedule meeting', [
                    'schedule' => $model
                ]);
            }
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
    public function editSchedule(APIRequest $request)
    {
        $flagErrorMail = false;
        $request->validate([
            'schedule_id' => 'required',
            'subject' => 'required',
            'description' => 'required',
            'date' => 'required|date_format:Y-m-d', // Y-m-d
            'time' => 'required|date_format:H:i', // H:i
            'pic' => 'required',
            'invitation' => 'required',
            // 'parent_id' => 'required',
        ]);
        $user = auth()->user();
        // check pic exist
        // pic = user
        $getPIC = User::find($request->pic);
        if (!isset($getPIC) && empty($getPIC)) {
            return response()->json([
                'error' => [
                    'status' => 404,
                    'message' => 'PIC not found',
                ]
            ]);
        }
        // check invitation valid
        // expected type is array
        $invitation = $request->invitation;
        foreach ($invitation as $invit) {
            $checkUserInvit = User::find($invit);
            if (!isset($checkUserInvit) && empty($checkUserInvit)) {
                return response()->json([
                    'error' => [
                        'status' => 404,
                        'message' => 'Invitation not found',
                    ]
                ]);
            }
        }
        // check parent
        if (isset($request->parent_id) && !empty($request->parent_id)) {
            // check parent exist
            $checkParent = Schedule::find($request->parent_id);
            if (!isset($checkParent) && empty($checkParent)) {
                return response()->json([
                    'error' => [
                        'status' => 404,
                        'message' => 'Parent not found',
                    ]
                ]);
            }
        }
        // data ready to save
        DB::beginTransaction();
        try {
            $model = Schedule::find($request->schedule_id);
            if (!isset($model) && empty($model)) {
                DB::rollback();
                return response()->json([
                    'error' => [
                        'status' => 404,
                        'message' => 'Schedule not found',
                    ]
                ]);
            }
            // only pic and creator can edit this data
            $have_permission = $this->checkPICorCreator($user->id, $model->pic, $model->created_by);
            if (!$have_permission) {
                DB::rollback();
                return response()->json([
                    'error' => [
                        'status' => 501,
                        'message' => 'Only pic and creator can using this function',
                    ]
                ]);
            }
            $model->parent_id = $request->parent_id;
            $model->pic = $request->pic;
            $model->subject = $request->subject;
            $model->description = $request->description;
            $model->date = $request->date;
            $model->time = $request->time;
            $model->save();

            // remove existing invitation
            ScheduleInvitation::where('schedules_id', $model->id)->get()->each->delete();
            // save invitation
            foreach ($invitation as $val) {
                $model_invitation = new ScheduleInvitation;
                $model_invitation->schedules_id = $model->id;
                $model_invitation->user_id = $val;
                $model_invitation->save();

                // send notif to invitation (not tested)
                $userNotif = User::find($val);
                try {
                    $userNotif->notify(new ScheduleUpdated($model));
                } catch (\Throwable $th) {
                    $flagErrorMail = true;
                }
                // $userNotif->notify(new ScheduleUpdated($model));
            }
            DB::commit();
            if ($flagErrorMail) {
                return ApiResponse::make('Successfully added schedule meeting, Email error silahkan hubungi developer', [
                    'schedule' => $model
                ]);
            }else{
                return ApiResponse::make('Successfully added schedule meeting', [
                    'schedule' => $model
                ]);
            }
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
    public function showSchedule(APIRequest $request)
    {
        $request->validate([
            'schedule_id' => 'required',
        ]);
        $user = auth()->user();
        // check schedule exist
        $getSchedule = Schedule::join('users as u', 'u.id', 'schedules.pic')
            ->join('users as u2', 'u2.id', 'schedules.created_by')
            ->where('schedules.id', $request->schedule_id)
            ->selectRaw('schedules.*,u.name as pic_name,u2.name as created_by_name')
            ->first();
        if (!isset($getSchedule) && empty($getSchedule)) {
            return response()->json([
                'error' => [
                    'status' => 404,
                    'message' => 'Schedule not found',
                ]
            ]);
        }
        // check parent if exist
        if (isset($getSchedule->parent_id) && !empty($getSchedule->parent_id)) {
            $getParent = Schedule::join('users as u', 'u.id', 'schedules.pic')
                ->join('users as u2', 'u2.id', 'schedules.created_by')
                ->where('schedules.id', $getSchedule->parent_id)
                ->selectRaw('schedules.*,u.name as pic_name,u2.name as created_by_name')
                ->first();
            $getSchedule->parent_id = $getParent;
        }
        // get invitation
        $getInvitation = ScheduleInvitation::join('users as u', 'u.id', 'schedules_invitations.user_id')
            ->selectRaw('schedules_invitations.*,u.name as user_name')
            ->where('schedules_invitations.schedules_id', $getSchedule->id)
            ->get();
        return ApiResponse::make('Data schedule meeting found', [
            'schedule' => $getSchedule,
            'invitation' => $getInvitation
        ]);
    }
    public function scheduleToFinish(APIRequest $request)
    {
        $request->validate([
            'schedule_id' => 'required',
            'location' => 'required',
            'start_hour' => 'required|date_format:H:i',
            'end_hour' => 'required|date_format:H:i|after:start_hour',
            'participant' => 'required',
            'topic' => 'required',
        ]);
        $user = auth()->user();
        $getSchedule = Schedule::where('status', 'unfinished')
            ->find($request->schedule_id);
        if (!isset($getSchedule) && empty($getSchedule)) {
            return response()->json([
                'error' => [
                    'status' => 404,
                    'message' => 'Schedule not found',
                ]
            ]);
        }
        $have_permission = $this->checkPICorCreator($user->id, $getSchedule->pic, $getSchedule->created_by);
        if (!$have_permission) {
            return response()->json([
                'error' => [
                    'status' => 501,
                    'message' => 'Only pic and creator can using this function',
                ]
            ]);
        }
        DB::beginTransaction();
        try {
            // insert into schedule_finishes
            $scheduleFinish = new ScheduleFinish;
            $scheduleFinish->schedule_id = $request->schedule_id;
            $scheduleFinish->start_hour = $request->start_hour;
            $scheduleFinish->end_hour = $request->end_hour;
            $scheduleFinish->location = $request->location;
            $scheduleFinish->topic = $request->topic;
            $scheduleFinish->save();

            // insert into schedule_finish_invitations
            // actual popple who attend meeting
            // $request->participant expected type is array
            foreach ($request->participant as $participant) {
                // check participant is a user
                $checkIsUser = User::find($participant);
                $scheduleFinishInvitations =  new ScheduleFinishInvitation;
                $scheduleFinishInvitations->schedule_finish_id = $scheduleFinish->id;
                if (isset($checkIsUser) && !empty($checkIsUser)) {
                    $scheduleFinishInvitations->user_id = $participant;
                    $scheduleFinishInvitations->other = '-';
                } else {
                    $scheduleFinishInvitations->other = $participant;
                }
                $scheduleFinishInvitations->save();
            }

            // update status schedules to finished
            $getSchedule->status = 'finished';
            $getSchedule->finish_at = Carbon::now();
            $getSchedule->save();

            // upload file if exist
            // in request files expected type is array
            if (isset($request->files) && !empty($request->files)) {
                foreach ($request->file('files') as $file) {
                    // insert into schedule_finish_media
                    $scheduleFinishMedia = new ScheduleFinishMedia;
                    $scheduleFinishMedia->schedule_finish_id = $scheduleFinish->id;
                    // upload image to server
                    $filename = Files::uploadLocalOrS3($file, "meeting/$getSchedule->pic");
                    $scheduleFinishMedia->file = public_file("meeting/$getSchedule->pic/$filename");
                    $scheduleFinishMedia->save();
                }
            }

            DB::commit();
            return ApiResponse::make('Successfully change status to finish', [
                'schedule' => $getSchedule,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
            return response()->json([
                'error' => [
                    'status' => 500,
                    'message' => 'Internal server error',
                ]
            ]);
        }
    }
    public function checkPICorCreator($user_id, $pic, $creator_id)
    {
        // only pic and creator can edit this data
        $have_permission = true;
        if ($user_id != $pic) {
            if ($user_id != $creator_id) {
                $have_permission = false;
            }
        }
        return $have_permission;
    }
}
