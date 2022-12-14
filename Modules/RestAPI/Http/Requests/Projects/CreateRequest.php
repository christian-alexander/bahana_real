<?php

namespace Modules\RestAPI\Http\Requests\Projects;

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
            'project_name' => 'required',
            'start_date' => 'required',
            'project_summary' => 'required',
            'category_id' => 'required',
            'hours_allocated' => 'nullable|numeric',
            'client.id' => 'sometimes|exists:clients,id',
            'status' => 'required|in:on hold,not started,in progress,cancelled'
        ];

        // if (!$this->has('without_deadline')) {
        //     $rules['deadline'] = 'required';
        // }
        if ($this->project_budget != '') {
            $rules['project_budget'] = 'numeric';
            $rules['currency.id'] = 'sometimes|exists:currencies,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
