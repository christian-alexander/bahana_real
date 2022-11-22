<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use App\Http\Requests\API\APIRequest;
use App\InputPO;
use App\InternalMemo;
use App\LaporanKerusakan;
use App\LaporanPenangguhanPekerjaan;
use App\LaporanPerbaikanKerusakan;
use App\PermintaanDana;
use App\SBPBBM;
use App\SPK;
use App\SPKActivity;
use App\SPKApproval;
use App\SPKDetail;
use App\SPKPerformance;
use App\SPTT;
use App\TipeCuti;
use Illuminate\Support\Facades\DB;

class InputPOController extends ApiBaseController
{
    public function getList(APIRequest $request)
    {
        $user = auth()->user();

        $data = InputPO::leftjoin('office as o','o.id','input_po.kapal_id')
            ->leftjoin('sub_company as sc','input_po.sub_company_id','sc.id')
            ->selectRaw('input_po.*,
            sc.name as perusahaan,
            o.name as office
            ');
        if (isset($request->start_date) && !empty($request->start_date)) {
            $data = $data->whereDate('input_po.created_at','>=',$request->start_date);
        }
        if (isset($request->end_date) && !empty($request->end_date)) {
            $data = $data->whereDate('input_po.created_at','<=',$request->end_date);
        }
        if (isset($request->limit) && !empty($request->limit)) {
            $data = $data->limit($request->limit);
        }
        if (isset($request->offset) && !empty($request->offset)) {
            $data = $data->offset($request->offset);
        }
        $data = $data->get();
        return ApiResponse::make('Get data success', [
            'data' => $data
        ]);
    }
}
