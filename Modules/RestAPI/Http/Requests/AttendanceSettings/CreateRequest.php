<?php namespace Modules\RestAPI\Http\Requests\AttendanceSettings;

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
        return in_array('attendance', $user->modules);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            // 'team_name' => 'required',
        ];

    }

    public function messages()
    {
        return [
            //
        ];
    }

}
