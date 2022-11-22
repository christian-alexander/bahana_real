<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use App\Http\Requests\API\APIRequest;
use App\Pertanyaan;
use Illuminate\Support\Facades\DB;

class PertanyaanController extends ApiBaseController
{
    public function list()
    {
        $data = Pertanyaan::all();
        return ApiResponse::make('Successfully get pertanyaan', [
            'pertanyaan' => $data
        ]);
    }
}
