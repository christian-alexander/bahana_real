<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use App\Http\Requests\API\APIRequest;
use App\InternalMemo;
use App\LaporanKerusakan;
use App\LaporanPenangguhanPekerjaan;
use App\LaporanPerbaikanKerusakan;
use App\PermintaanDana;
use App\SPK;
use App\SPKActivity;
use App\SPKApproval;
use App\SPKDetail;
use App\SPKPerformance;
use App\SPTT;
use App\TipeCuti;
use Illuminate\Support\Facades\DB;

class PermintaanDanaController extends ApiBaseController
{
    public function getList(APIRequest $request)
    {
        $user = auth()->user();

        $data =  PermintaanDana::leftjoin('users as u_pembuat','u_pembuat.id','permintaan_dana.pembuat')
        ->leftjoin('sub_company as s','s.id','permintaan_dana.subcompany_id')
        ->leftjoin('users as u_nama','u_nama.id','permintaan_dana.user_id')
        ->leftjoin('users as u_disetujui','u_disetujui.id','permintaan_dana.disetujui')
        ->leftjoin('users as u_mengetahui_1','u_mengetahui_1.id','permintaan_dana.mengetahui_1')
        ->leftjoin('users as u_diperiksa','u_diperiksa.id','permintaan_dana.diperiksa')
        ->selectRaw('permintaan_dana.*,
            u_pembuat.name as name_pembuat,
            u_disetujui.name as name_disetujui,
            u_mengetahui_1.name as name_mengetahui_1,
            u_diperiksa.name as name_diperiksa,
            u_nama.name as nama,
            s.name as subcompany
            ');
        if (isset($request->start_date) && !empty($request->start_date)) {
            $data = $data->whereDate('permintaan_dana.created_at','>=',$request->start_date);
        }
        if (isset($request->end_date) && !empty($request->end_date)) {
            $data = $data->whereDate('permintaan_dana.created_at','<=',$request->end_date);
        }
        if (isset($request->status) && !empty($request->status)) {
            $data = $data->where('permintaan_dana.status',$request->status);
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
