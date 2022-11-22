<?php

namespace App\Http\Requests\Office;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends CoreRequest
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
            'office_name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius' => 'required',
            'jam_istirahat_awal' => 'required',
            'jam_istirahat_akhir' => 'required',
        ];
    }
}
