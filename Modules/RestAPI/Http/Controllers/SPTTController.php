<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use App\Http\Requests\API\APIRequest;
use App\SPK;
use App\SPKActivity;
use App\SPKApproval;
use App\SPKDetail;
use App\SPKPerformance;
use App\SPTT;
use App\TipeCuti;
use Illuminate\Support\Facades\DB;

class SPTTController extends ApiBaseController
{
    public function getList(APIRequest $request)
    {
        $user = auth()->user();

        $getSPTT = SPTT::select('*');
        if (isset($request->cari) && !empty($request->cari)) {
            $getSPTT = $getSPTT->where('nomor','like','%'.$request->cari.'%');
        }
        if (isset($request->start_date) && !empty($request->start_date)) {
            $getSPTT = $getSPTT->whereDate('created_at','>=',$request->start_date);
        }
        if (isset($request->end_date) && !empty($request->end_date)) {
            $getSPTT = $getSPTT->whereDate('created_at','<=',$request->end_date);
        }
        if (isset($request->status) && !empty($request->status)) {
            $getSPTT = $getSPTT->where('status',$request->status);
        }
        if (isset($request->limit) && !empty($request->limit)) {
            $getSPTT = $getSPTT->limit($request->limit);
        }
        if (isset($request->offset) && !empty($request->offset)) {
            $getSPTT = $getSPTT->offset($request->offset);
        }
        $getSPTT = $getSPTT->get();
        return ApiResponse::make('Get data success', [
            'data' => $getSPTT
        ]);
    }
}
