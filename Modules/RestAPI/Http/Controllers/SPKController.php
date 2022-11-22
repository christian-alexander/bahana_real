<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use App\Http\Requests\API\APIRequest;
use App\SPK;
use App\SPKActivity;
use App\SPKApproval;
use App\SPKDetail;
use App\SPKPerformance;
use App\TipeCuti;
use Illuminate\Support\Facades\DB;

class SPKController extends ApiBaseController
{
    public function getList(APIRequest $request)
    {
        $user = auth()->user();

        $getSPK = SPK::join('users as u','u.id','spk.user_id')
        ->selectRaw('spk.*,u.name as user_name');
        if (isset($request->user_id) && !empty($request->user_id)) {
            if ($request->user_id == "bawahan") {
              // do nothing
            } else {
              $getSPK = $getSPK->where('spk.user_id', $request->user_id);
            }
          }
        if (isset($request->cari) && !empty($request->cari)) {
            $getSPK = $getSPK->where('spk.no','like','%'.$request->cari.'%');
        }
        if (isset($request->start_date) && !empty($request->start_date)) {
            $getSPK = $getSPK->whereDate('spk.created_at','>=',$request->start_date);
        }
        if (isset($request->end_date) && !empty($request->end_date)) {
            $getSPK = $getSPK->whereDate('spk.created_at','<=',$request->end_date);
        }
        if (isset($request->status_pengajuan) && !empty($request->status_pengajuan)) {
            $getSPK = $getSPK->where('spk.status_approval',$request->status_pengajuan);
        }
        if (isset($request->limit) && !empty($request->limit)) {
            $getSPK = $getSPK->limit($request->limit);
        }
        if (isset($request->offset) && !empty($request->offset)) {
            $getSPK = $getSPK->offset($request->offset);
        }
        $getSPK = $getSPK->get();
        return ApiResponse::make('Get spk success', [
            'spk' => $getSPK
        ]);
    }
    public function getHistorySPK(APIRequest $request)
    {
        $activity = SPKActivity::where('spk_id', $request->spk_id)->get();
        return ApiResponse::make('Get activity success', [
            'activity' => $activity
        ]);
    }
}
