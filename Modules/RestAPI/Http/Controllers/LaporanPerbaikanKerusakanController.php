<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use App\Http\Requests\API\APIRequest;
use App\LaporanKerusakan;
use App\LaporanPenangguhanPekerjaan;
use App\LaporanPerbaikanKerusakan;
use App\SPK;
use App\SPKActivity;
use App\SPKApproval;
use App\SPKDetail;
use App\SPKPerformance;
use App\SPTT;
use App\TipeCuti;
use Illuminate\Support\Facades\DB;

class LaporanPerbaikanKerusakanController extends ApiBaseController
{
    public function getList(APIRequest $request, $id)
    {
        $user = auth()->user();

        $getLPK = LaporanPerbaikanKerusakan::select('*')
        ->where('laporan_kerusakan_id', $id);
        
        if (isset($request->cari) && !empty($request->cari)) {
            $getLPK = $getLPK->where('nomor','like','%'.$request->cari.'%');
        }
        if (isset($request->start_date) && !empty($request->start_date)) {
            $getLPK = $getLPK->whereDate('created_at','>=',$request->start_date);
        }
        if (isset($request->end_date) && !empty($request->end_date)) {
            $getLPK = $getLPK->whereDate('created_at','<=',$request->end_date);
        }
        if (isset($request->status) && !empty($request->status)) {
            $getLPK = $getLPK->where('status',$request->status);
        }
        if (isset($request->limit) && !empty($request->limit)) {
            $getLPK = $getLPK->limit($request->limit);
        }
        if (isset($request->offset) && !empty($request->offset)) {
            $getLPK = $getLPK->offset($request->offset);
        }
        $getLPK = $getLPK->get();
        return ApiResponse::make('Get data success', [
            'data' => $getLPK
        ]);
    }
}
