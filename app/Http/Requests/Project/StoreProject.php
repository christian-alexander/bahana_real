<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\CoreRequest;

class StoreProject extends CoreRequest
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
        $rules = [
            'project_name' => 'required',
            'alert_kuning' => 'required',
            'alert_merah' => 'required',
            // 'start_date' => 'required',
        ];

        if (!$this->has('without_deadline')) {
            // $rules['deadline'] = 'required';
        }

        return $rules;
    }
}
