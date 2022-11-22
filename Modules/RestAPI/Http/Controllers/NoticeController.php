<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiController;
use Modules\RestAPI\Entities\Notice;
use Modules\RestAPI\Http\Requests\Notice\IndexRequest;
use Modules\RestAPI\Http\Requests\Notice\CreateRequest;
use Modules\RestAPI\Http\Requests\Notice\ShowRequest;
use Modules\RestAPI\Http\Requests\Notice\UpdateRequest;
use Modules\RestAPI\Http\Requests\Notice\DeleteRequest;
use App\Team;
use Illuminate\Support\Facades\DB;
use Froiden\RestAPI\ApiResponse;
use Froiden\RestAPI\Exceptions\ApiException;
use Illuminate\Http\Request;
use App\Http\Requests\API\APIRequest;
use App\Notifications\NewNotice;
use App\SubCompany;
use Modules\RestAPI\Entities\User;
use App\Helper\Files;
use App\NoticeRead;

class NoticeController extends ApiBaseController
{

    protected $model = Notice::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query)
    {
        return $query->visibility();
    }
    // public function stored(Notice $notice)
    // {
    //     dd($notice);
    // }

    public function getTeams(){
      
        try {
            DB::beginTransaction();
            $user = auth()->user();
          	
          	$teams = Team::where('company_id', $user->company_id)->get();

            return ApiResponse::make('Teams found', [
                        'teams' => $teams
            ]);
        } catch (Exception $e) {
            DB::rollback();
            // return ApiResponse::make('Attendance failed '.$e->getMessages());
            $exception = new ApiException('Teams not found '.$e->getMessages(), null, 403, 403, 2001);
            return ApiResponse::exception($exception);
            
        }
    }

    public function getList(APIRequest $request){
      
        try {
            $user = auth()->user();
            // dd($user);
            // dd($request->all());
            
            $notices = Notice::where('company_id', $user->company_id)
                ->where(function($q) use ($user){
                    $q->orWhere('created_by',$user->id)
                    ->orWhere("team_id", "like", "%".$user->employeeDetail->department_id."%");
                });

            if($request->order){
                $or = explode(" ", $request->order);
                $notices = $notices->orderBy($or[0], $or[1]);
            }

            if($request->limit){
                $notices = $notices->limit($request->limit);
            }

            // if($request->team_id){
            //     $notices = $notices->where("team_id", "like", "%".$request->team_id."%");
            // }

            // if($request->sub_company_id){
            //     $notices = $notices->where("sub_company_id", "like", "%".$request->sub_company_id."%");
            // }

            $notices = $notices->get();

            // $teams = Team::where('company_id', $user->company_id)->get();

            // $details = User::join('employee_details', 'employee_details.user_id', 'users.id')
            //             ->where('employee_details.user_id', $user->id)
            //             ->select('users.*', 'employee_details.*')
            //             ->get();
            // dd($details);

            return ApiResponse::make('Notices found', [
                        'notices' => $notices
            ]);
        } catch (Exception $e) {
            // return ApiResponse::make('Attendance failed '.$e->getMessages());
            $exception = new ApiException('Teams not found '.$e->getMessages(), null, 403, 403, 2001);
            return ApiResponse::exception($exception);
            
        }
    }

    public function storeNotice(APIRequest $request)
    {
        $userLogin = api_user();
        $request->validate([
            'heading' => 'required',
            'to' => 'required',
            'team_id' => 'required',
        ]);
        $create_pengumuman = false;
        $additional_field = json_decode($userLogin->employeeDetail->additional_field, true);
        if ($additional_field) {
            if (isset($additional_field['create_pengumuman'])){ 
                if ($additional_field['create_pengumuman']==1) {
                    $create_pengumuman = true;
                }
            }
        }
        if (!$create_pengumuman) {
            return response()->json([
                'error' => [
                    'status' => 500,
                    'message' => 'User dont have permission to use this function',
                ]
            ]);
        }
        // check leave
        DB::beginTransaction();
        try {
            $flagErrorMail = false;
            if (isset($request->sub_company_id)) {
                if ($request->sub_company_id==0) {
                    $getSubCompany = SubCompany::where('company_id', $userLogin->company_id)->pluck('id');
                }else{
                    $getSubCompany = str_replace(' ','',$request->sub_company_id);
                    $getSubCompany = explode(',',$getSubCompany);
                }
            }else{
                $getSubCompany = null; 
            }
            if ($request->team_id==0) {
                $getDepartment = Team::where('company_id', $userLogin->company_id)->pluck('id');
            }else{
                $getDepartment = str_replace(' ','',$request->team_id);
                $getDepartment = explode(',',$getDepartment);
            }
            
            $notice = new Notice();
            $notice->company_id = $userLogin->company_id;
            $notice->heading = $request->heading;
            $notice->description = $request->description;
            $notice->to = 'employee';
            $notice->team_id = \json_encode($getDepartment);
            $notice->sub_company_id = !empty($getSubCompany)?json_encode($getSubCompany):null;
            $notice->created_by = $userLogin->id;
            $notice->save();

            // store image
            $file = null;
            if ($request->hasFile('file')) {
                $file = Files::uploadLocalOrS3($request->file, 'attendance', 300);
            }
            $notice->files = $file;
            $notice->save();

            if (empty($getSubCompany)) {
                foreach ($getDepartment as $department) {
                    $users = User::join('employee_details', 'employee_details.user_id', 'users.id')
                        ->where('employee_details.department_id', $department)
                        ->select('users.*')
                        ->get();
                        // dd($users);
                    foreach ($users as $user) {
                        try {
                            $user->notify(new NewNotice($notice,$userLogin,$user));
                        } catch (\Throwable $th) {
                            $flagErrorMail = true;
                        }
                    }
                }
            }else{
                foreach ($getSubCompany as $sub_company) {
                    foreach ($getDepartment as $department) {
                        $users = User::join('employee_details', 'employee_details.user_id', 'users.id')
                            ->where('employee_details.department_id', $department)
                            ->where('employee_details.sub_company_id', $sub_company)
                            ->select('users.*')
                            ->get();
                        foreach ($users as $user) {
                            try {
                                $user->notify(new NewNotice($notice,$userLogin,$user));
                            } catch (\Throwable $th) {
                                $flagErrorMail = true;
                            }
                        }
                    }
                }
            }

            // }
            // if ($request->team_id==0) {
            //     // to all department
            //     // get all department
            //     $getDepartment = Team::where('company_id', $userLogin->company_id)
            //         ->get();
            //     if (count($getDepartment) > 0) {
            //         foreach ($getDepartment as $val) {
            //             $notice = new Notice();
            //             $notice->company_id = $userLogin->company_id;
            //             $notice->heading = $request->heading;
            //             $notice->description = $request->description;
            //             $notice->to = 'employee';
            //             $notice->team_id = $val->id;
            //             $notice->sub_company_id = $request->sub_company_id;
            //             $notice->save();
    
            //             $users = User::join('employee_details', 'employee_details.user_id', 'users.id')
            //                 ->where('employee_details.department_id', $val->id)
            //                 ->where('employee_details.sub_company_id', $request->sub_company_id)
            //                 ->select('users.*')
            //                 ->get();
            //             foreach ($users as $user) {
            //                 try {
            //                     $user->notify(new NewNotice($notice,$userLogin,$user));
            //                 } catch (\Throwable $th) {
            //                     $flagErrorMail = true;
            //                 }
            //                 // $user->notify(new NewNotice($notice));
            //             }
            //         }
            //     }
            // } else {
            //     $team_id = str_replace(' ','',$request->team_id);
            //     $team_id = explode(',',$team_id);
            //     // get depart
            //     // dd($team_id);
            //     foreach ($team_id as $val) {
            //         $notice = new Notice();
            //         $notice->company_id = $userLogin->company_id;
            //         $notice->heading = $request->heading;
            //         $notice->description = $request->description;
            //         $notice->to = 'employee';
            //         $notice->team_id = $val;
            //         $notice->sub_company_id = $request->sub_company_id;
            //         $notice->save();
            //         $users = User::join('employee_details', 'employee_details.user_id', 'users.id')
            //             ->where('employee_details.department_id', $val)
            //             ->where('employee_details.sub_company_id', $request->sub_company_id)
            //             ->select('users.*')
            //             ->get();
            //         foreach ($users as $user) {
            //             try {
            //                 $user->notify(new NewNotice($notice,$userLogin,$user));
            //             } catch (\Throwable $th) {
            //                 $flagErrorMail = true;
            //             }
            //         }
            //     }
            // }
        DB::commit();
        return ApiResponse::make('Data berhasil disimpan');
        } catch (\Throwable $e) {
            DB::rollback();
            dd($e->getMessage());
            return response()->json([
                'error' => [
                'status' => 500,
                'message' => 'Internal server error',
                ]
            ]);
        }
        
    }

    public function markRead(APIRequest $request){
        $request->validate([
            'notice_id' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $userLogin = api_user();
            // check data exist
            $check = NoticeRead::where('user_id',$userLogin->id)->where('notice_id',$request->notice_id)->count();
            if ($check==0) {
                $model = new NoticeRead;
                $model->user_id = $userLogin->id;
                $model->notice_id = $request->notice_id;
                $model->save();
            }
            DB::commit();
            return ApiResponse::make('Data berhasil disimpan');
        } catch (\Throwable $th) {
            DB::rollback();
            $exception = new ApiException('Teams not found '.$e->getMessages(), null, 403, 403, 2001);
            return ApiResponse::exception($exception);
        }
    }
}
