<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use App\Http\Requests\API\APIRequest;
use App\SPP;

class SPPController extends ApiBaseController
{
    public function getList(APIRequest $request)
    {
        $user = auth()->user();

        $data = SPP::leftjoin('users as u_pembuat','u_pembuat.id','sounding_pagi_perwira.pembuat')
            ->leftjoin('office as o','o.id','sounding_pagi_perwira.office_id')
            ->leftjoin('users as u_menyetujui','u_menyetujui.id','sounding_pagi_perwira.menyetujui')
            ->selectRaw('sounding_pagi_perwira.*,
                u_pembuat.name as name_pembuat,
                u_menyetujui.name as name_menyetujui,
                o.name as office
            ');
        if (isset($request->start_date) && !empty($request->start_date)) {
            $data = $data->whereDate('sounding_pagi_perwira.created_at','>=',$request->start_date);
        }
        if (isset($request->end_date) && !empty($request->end_date)) {
            $data = $data->whereDate('sounding_pagi_perwira.created_at','<=',$request->end_date);
        }
        if (isset($request->status) && !empty($request->status)) {
            $data = $data->where('sounding_pagi_perwira.status',$request->status);
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
