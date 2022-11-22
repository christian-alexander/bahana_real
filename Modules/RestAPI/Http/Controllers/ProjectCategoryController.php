<?php

namespace Modules\RestAPI\Http\Controllers;

use Modules\RestAPI\Http\Requests\ProjectsCategory\CreateRequest;
use Modules\RestAPI\Entities\ProjectCategory;

class ProjectCategoryController extends ApiBaseController
{
    protected $model = ProjectCategory::class;

    protected $storeRequest = CreateRequest::class;
}
