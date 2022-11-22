<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use App\Http\Requests\API\APIRequest;
use App\InternalMemo;
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

class InternalMemoController extends ApiBaseController
{
    public function getList(APIRequest $request)
    {
        $user = auth()->user();

        $data =  InternalMemo::leftjoin('users as u_pembuat','u_pembuat.id','internal_memo.pembuat')
        ->leftjoin('sub_company as s','s.id','internal_memo.subcompany_id')
        ->leftjoin('teams as t','t.id','internal_memo.team_id')
        ->leftjoin('wilayah as w','w.id','internal_memo.wilayah_id')
        ->leftjoin('users as u_from','u_from.id','internal_memo.from_user_id')
        ->leftjoin('users as u_to','u_to.id','internal_memo.to_user_id')
        ->leftjoin('users as u_penerima','u_penerima.id','internal_memo.penerima')
        ->leftjoin('users as u_mengetahui_1','u_mengetahui_1.id','internal_memo.mengetahui_1')
        ->leftjoin('users as u_mengetahui_2','u_mengetahui_2.id','internal_memo.mengetahui_2')
        ->selectRaw('internal_memo.*,
            u_pembuat.name as name_pembuat,
            u_penerima.name as name_penerima,
            u_mengetahui_1.name as name_mengetahui_1,
            u_mengetahui_2.name as name_mengetahui_2,
            u_from.name as name_from,
            u_to.name as name_to,
            s.name as subcompany,
            t.team_name as department,
            w.name as wilayah
        ');
        if (isset($request->cari) && !empty($request->cari)) {
            $data = $data->where('internal_memo.no','like','%'.$request->cari.'%');
        }
        if (isset($request->start_date) && !empty($request->start_date)) {
            $data = $data->whereDate('internal_memo.created_at','>=',$request->start_date);
        }
        if (isset($request->end_date) && !empty($request->end_date)) {
            $data = $data->whereDate('internal_memo.created_at','<=',$request->end_date);
        }
        if (isset($request->status) && !empty($request->status)) {
            $data = $data->where('internal_memo.status',$request->status);
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
