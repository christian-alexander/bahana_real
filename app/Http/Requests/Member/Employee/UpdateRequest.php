<?php

namespace App\Http\Requests\Member\Employee;

use App\EmployeeDetails;
use Froiden\LaravelInstaller\Request\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class UpdateRequest extends CoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $detailID = EmployeeDetails::where('user_id', $this->route('abk'))->first();
        return [
            'employee_id' => 'required|unique:employee_details,employee_id,'.$detailID->id,
            'email' => 'required|unique:users,email,'.$detailID->user_id,
            'slack_username' => 'nullable|unique:employee_details,slack_username,'.$detailID->id,
            'name'  => 'required',
            'hourly_rate' => 'nullable|numeric',
            'department' => 'required',
            'designation' => 'required',
        ];
    }
}
