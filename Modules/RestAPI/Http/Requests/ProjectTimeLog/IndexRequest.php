<?php

namespace Modules\RestAPI\Http\Requests\ProjectTimeLog;

use Modules\RestAPI\Http\Requests\BaseRequest;

class IndexRequest extends BaseRequest
{

	public function authorize()
	{
        $user = api_user();

        // Either user has role admin or has permission view_projects
        // Plus he needs to have projects module enabled from settings
		return true;
       // 1 Juli dikomen sementara sampai permission dibuat
      //return in_array('timelogs', $user->modules);
	}

	public function rules()
	{
		return [
			//
		];
	}

}
