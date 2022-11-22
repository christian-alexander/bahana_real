<?php namespace Modules\RestAPI\Http\Requests\Tickets;

use Modules\RestAPI\Http\Requests\BaseRequest;
class UpdateRequest extends BaseRequest
{

    /**
     * @return bool
     * @throws \Froiden\RestAPI\Exceptions\UnauthorizedException
     */
    public function authorize()
    {
        $user = api_user();
        // Either user has role admin or has permission view_projects
        // Plus he needs to have projects module enabled from settings
        return true;
        return in_array('projects', $user->modules) && ($user->hasRole('admin') || $user->can('edit_projects'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
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
