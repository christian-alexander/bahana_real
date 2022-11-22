<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use App\Http\Requests\API\APIRequest;
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

class SBPBBMController extends ApiBaseController
{
    public function getList(APIRequest $request)
    {
        $user = auth()->user();

        $data = SBPBBM::leftjoin('users as u_pembuat','u_pembuat.id','sbpbbm.pembuat')
        ->leftjoin('office as o','o.id','sbpbbm.office_id')
        ->leftjoin('users as u_menyaksikan','u_menyaksikan.id','sbpbbm.menyaksikan')
        ->leftjoin('users as u_mengetahui_1','u_mengetahui_1.id','sbpbbm.mengetahui_1')
        ->leftjoin('users as u_diperiksa','u_diperiksa.id','sbpbbm.diperiksa')
        ->leftjoin('users as u_penerima','u_penerima.id','sbpbbm.penerima')
        ->selectRaw('sbpbbm.*,
            u_pembuat.name as name_pembuat,
            u_menyaksikan.name as name_menyaksikan,
            u_mengetahui_1.name as name_mengetahui_1,
            u_diperiksa.name as name_diperiksa,
            u_penerima.name as name_penerima,
            o.name as office
        ');
        if (isset($request->start_date) && !empty($request->start_date)) {
            $data = $data->whereDate('sbpbbm.created_at','>=',$request->start_date);
        }
        if (isset($request->end_date) && !empty($request->end_date)) {
            $data = $data->whereDate('sbpbbm.created_at','<=',$request->end_date);
        }
        if (isset($request->status) && !empty($request->status)) {
            $data = $data->where('sbpbbm.status',$request->status);
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
