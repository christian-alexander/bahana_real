<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use Modules\RestAPI\Entities\Leave;
use Modules\RestAPI\Http\Requests\Leave\IndexRequest;
use Modules\RestAPI\Http\Requests\Leave\CreateRequest;
use Modules\RestAPI\Http\Requests\Leave\UpdateRequest;
use Modules\RestAPI\Http\Requests\Leave\ShowRequest;
use Modules\RestAPI\Http\Requests\Leave\DeleteRequest;
use App\Http\Requests\API\APIRequest;
use App\Helper\Files;
use App\LeaveCuti;
use App\LeaveDinasLuarKota;
use App\LeaveDinasSementara;
use Carbon\Carbon;
use Modules\RestAPI\Entities\LeaveIjin;
use Modules\RestAPI\Entities\LeaveType;

class LeaveApprovalController extends ApiBaseController
{
  protected $model = Leave::class;

  protected $indexRequest = IndexRequest::class;
  protected $storeRequest = CreateRequest::class;
  protected $updateRequest = UpdateRequest::class;
  protected $showRequest = ShowRequest::class;
  protected $deleteRequest = DeleteRequest::class;

  public function indexApproval()
  {
    // get user
    $user = auth()->user();
    // get leaves
    $leaves = Leave::join('leave_types as lt', 'lt.id', 'leaves.leave_type_id')
      ->where('lt.company_id', $user->company_id)
      ->where('leaves.company_id', $user->company_id)
      ->where('user_id', $user->id)
      ->selectRaw('leaves.*,lt.type_name')
      ->get();

    // foreach ($leaves as $item) {
    //   // get child by leave type
    //   if ($item->type_name == 'Ijin') {
    //     $child = LeaveIjin::where('leave_id', $item->id)->first();
    //     $item->child = $child;
    //   } elseif ($item->type_name == 'Cuti') {
    //     $child = LeaveCuti::where('leave_id', $item->id)->first();
    //     $item->child = $child;
    //   } elseif ($item->type_name == 'Dinas sementara') {
    //     $child = LeaveDinasSementara::where('leave_id', $item->id)->first();
    //     $item->child = $child;
    //   } elseif ($item->type_name == 'Dinas Luar Kota') {
    //     $child = LeaveDinasLuarKota::where('leave_id', $item->id)->first();
    //     $item->child = $child;
    //   }
    // }
    return $leaves;
    return ApiResponse::make('Leave saved', [
      'leave' => null,
    ]);
  }
}
