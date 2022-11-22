<?php

namespace Modules\RestAPI\Http\Controllers;

use App\EmployeeDetails;
use App\Http\Requests\API\APIRequest;
use App\Team;
use Froiden\RestAPI\ApiController;
use Modules\RestAPI\Entities\Department;
use Froiden\RestAPI\ApiResponse;
use Modules\RestAPI\Entities\User;

// use Modules\RestAPI\Http\Requests\Department\IndexRequest;
// use Modules\RestAPI\Http\Requests\Department\CreateRequest;
// use Modules\RestAPI\Http\Requests\Department\UpdateRequest;
// use Modules\RestAPI\Http\Requests\Department\ShowRequest;
// use Modules\RestAPI\Http\Requests\Department\DeleteRequest;

class DepartmentController extends ApiBaseController
{
    protected $model = Department::class;

    // protected $indexRequest = IndexRequest::class;
    // protected $storeRequest = CreateRequest::class;
    // protected $updateRequest = UpdateRequest::class;
    // protected $showRequest = ShowRequest::class;
    // protected $deleteRequest = DeleteRequest::class;

    public function list(APIRequest $request){
        $user_detail = api_user()->employeeDetail;
        if (isset($request->sub_company_id) && !empty($request->sub_company_id)){ 
            $employeeDetailDepartmentId = EmployeeDetails::where('sub_company_id', $request->sub_company_id)->groupBy('department_id')->pluck('department_id');
      	    $data = Team::whereIn('id', $employeeDetailDepartmentId)->get();
        }else{
            $data = Department::where('company_id', $user_detail->company_id)->get();
        }
        return ApiResponse::make('Department found', [
            'data' => $data
        ]);
    }
    public function getMember(APIRequest $request){
        $user_detail = api_user()->employeeDetail;
        if ($request->module=='MEETING' || $request->module=='meeting') {
            $getUser = User::join('employee_details as ed','ed.user_id','users.id');
            if ($user_detail->department_id==$request->department_id) {
                $getUser = $getUser->where('ed.department_id',$request->department_id);
            }else{
                $getUser = $getUser->where('ed.department_id',$request->department_id)
                ->where('ed.is_atasan', 1);
            }
            $getUser = $getUser->selectRaw('users.*')
            ->get();
        }else{
            $getUser = User::join('employee_details as ed','ed.user_id','users.id')
            ->where('ed.department_id',$request->department_id)
            ->selectRaw('users.*')
            ->get();
        }
        return ApiResponse::make('Assignee found', [
            'user' => $getUser
        ]);
    }
}
