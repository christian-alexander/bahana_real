<?php

namespace Modules\RestAPI\Http\Requests\Notice;

use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

	public function authorize()
	{
		$user = api_user();
		// Either user has role admin or has permission edit_notice
		// Plus he needs to have notices module enabled from settings
		// create_pengumuman
		$create_pengumuman = false;
		$additional_field = json_decode($user->employeeDetail->additional_field, true);
		if ($additional_field) {
			if (isset($additional_field['create_pengumuman'])){ 
				if ($additional_field['create_pengumuman']==1) {
					$create_pengumuman = true;
				}
			}
		}
		return $create_pengumuman;
		// return in_array('notices', $user->modules) && ($user->hasRole('admin') || $user->can('add_notice') || $user->employeeDetail->menambahkan_pengumuman == 1);

		// return 1;
	}

	public function rules()
	{
		return [
			'heading' => 'required',
			'to' => 'required|in:employee,client',
		];
	}
}
