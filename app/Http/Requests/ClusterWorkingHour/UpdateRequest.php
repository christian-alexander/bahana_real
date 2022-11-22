<?php

namespace App\Http\Requests\ClusterWorkingHour;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'cluster_name' => 'required',
            'start_hour' => 'required',
            'end_hour' => 'required',
        ];
    }
}
