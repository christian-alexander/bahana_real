<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use App\Http\Requests\API\APIRequest;
use App\LaporanKerusakan;
use App\SPK;
use App\SPKActivity;
use App\SPKApproval;
use App\SPKDetail;
use App\SPKPerformance;
use App\SPTT;
use App\TipeCuti;
use Illuminate\Support\Facades\DB;

class LaporanKerusakanController extends ApiBaseController
{
    public function getList(APIRequest $request)
    {
        $user = auth()->user();

        $getLaporanKerusakan = LaporanKerusakan::select('*');
        if (isset($request->cari) && !empty($request->cari)) {
            $getLaporanKerusakan = $getLaporanKerusakan->where('nomor','like','%'.$request->cari.'%');
        }
        if (isset($request->start_date) && !empty($request->start_date)) {
            $getLaporanKerusakan = $getLaporanKerusakan->whereDate('created_at','>=',$request->start_date);
        }
        if (isset($request->end_date) && !empty($request->end_date)) {
            $getLaporanKerusakan = $getLaporanKerusakan->whereDate('created_at','<=',$request->end_date);
        }
        if (isset($request->status) && !empty($request->status)) {
            $getLaporanKerusakan = $getLaporanKerusakan->where('status',$request->status);
        }
        if (isset($request->limit) && !empty($request->limit)) {
            $getLaporanKerusakan = $getLaporanKerusakan->limit($request->limit);
        }
        if (isset($request->offset) && !empty($request->offset)) {
            $getLaporanKerusakan = $getLaporanKerusakan->offset($request->offset);
        }
        $getLaporanKerusakan = $getLaporanKerusakan->get();
        return ApiResponse::make('Get data success', [
            'data' => $getLaporanKerusakan
        ]);
    }
}
