<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use Modules\RestAPI\Entities\LeaveType;
use Modules\RestAPI\Http\Requests\LeaveType\IndexRequest;
use App\Http\Requests\API\APIRequest;
use App\Helper\Files;

class LeaveTypeController extends ApiBaseController
{
  protected $model = LeaveType::class;

  protected $indexRequest = IndexRequest::class;
}
