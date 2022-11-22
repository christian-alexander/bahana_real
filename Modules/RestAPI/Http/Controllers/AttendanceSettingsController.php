<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiController;
use Modules\RestAPI\Entities\AttendanceSettings;
use Modules\RestAPI\Http\Requests\AttendanceSettings\IndexRequest;
use Modules\RestAPI\Http\Requests\AttendanceSettings\CreateRequest;
use Modules\RestAPI\Http\Requests\AttendanceSettings\UpdateRequest;
use Modules\RestAPI\Http\Requests\AttendanceSettings\ShowRequest;
use Modules\RestAPI\Http\Requests\AttendanceSettings\DeleteRequest;

class AttendanceSettingsController extends ApiBaseController
{
    protected $model = AttendanceSettings::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;
}
