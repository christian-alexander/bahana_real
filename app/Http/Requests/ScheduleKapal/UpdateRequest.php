<?php

namespace App\Http\Requests\ScheduleKapal;

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
            'date_start' => 'required',
            'date_end' => 'required',
            'kapal_id' => 'required',
            'user_id' => 'required',
        ];
    }
}
