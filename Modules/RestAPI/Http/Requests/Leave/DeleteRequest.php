<?php

namespace Modules\RestAPI\Http\Requests\Leave;

use Modules\RestAPI\Http\Requests\BaseRequest;

class DeleteRequest extends BaseRequest
{

	public function authorize()
	{
		$user = api_user();

		// Either user has role admin or has permission view_projects
		// Plus he needs to have projects module enabled from settings

		return true;
		// return in_array('tasks', $user->modules);
	}

	public function rules()
	{
		return [
			//
		];
	}
}
