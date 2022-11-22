<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Http\Requests\API\APIRequest;
use App\SubCompany;
use Froiden\RestAPI\ApiController;
use Modules\RestAPI\Entities\Department;
use Froiden\RestAPI\ApiResponse;
use Modules\RestAPI\Entities\User;

// use Modules\RestAPI\Http\Requests\Department\IndexRequest;
// use Modules\RestAPI\Http\Requests\Department\CreateRequest;
// use Modules\RestAPI\Http\Requests\Department\UpdateRequest;
// use Modules\RestAPI\Http\Requests\Department\ShowRequest;
// use Modules\RestAPI\Http\Requests\Department\DeleteRequest;

class SubCompanyController extends ApiBaseController
{
    protected $model = SubCompany::class;

    public function list(APIRequest $request){
        $user_detail = api_user()->employeeDetail;
        $data = SubCompany::where('company_id', $user_detail->company_id)->get();
        return ApiResponse::make('Sub Company found', [
            'data' => $data
        ]);
    }
}
