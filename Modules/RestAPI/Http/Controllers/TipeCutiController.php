<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use App\Http\Requests\API\APIRequest;
use App\TipeCuti;
use Illuminate\Support\Facades\DB;

class TipeCutiController extends ApiBaseController
{
    public function list()
    {
        $data = TipeCuti::where('name','!=','Permohonan Cuti Default')->get();
        return ApiResponse::make('Successfully get tipe cuti', [
            'tipeCuti' => $data
        ]);
    }
}
