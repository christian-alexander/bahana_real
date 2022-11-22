<?php

namespace Modules\RestAPI\Http\Requests\Tickets;

use Modules\RestAPI\Http\Requests\BaseRequest;

class CreateRequest extends BaseRequest
{

    /**
     * @return bool
     * @throws \Froiden\RestAPI\Exceptions\UnauthorizedException
     */
    public function authorize()
    {

        $user = api_user();
        // Either user has role admin or has permission create_projects
        // Plus he needs to have projects module enabled from settings
        // dd($user->employeeDetail);
      	return true;
        return in_array('projects', $user->modules) && ($user->hasRole('admin') || $user->can('create_projects') || $user->employeeDetail->atur_tugas == 1);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'user_id' => 'required',
            'subject' => 'required',
            'type_id' => 'required',
            'priority' => 'required',
        ];


        return $rules;
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
