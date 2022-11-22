<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use App\Http\Requests\API\APIRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Notifications\ManualSendNotif;
use Froiden\RestAPI\ApiController;
use Froiden\RestAPI\Exceptions\ApiException;

class GeneralControllerController extends ApiController
{
  public function sendOneSignal(APIRequest $request){
    try {
      $user_id = $request->user_id;
      $user = User::find($user_id);
      if (!isset($user) && empty($user)){ 
        return ApiResponse::make('Data user not found');
      }
      $user->notify(new ManualSendNotif($user,$request->title,$request->content,$request->type));

      return ApiResponse::make('Send notif success');
    } catch (\Throwable $e) {
        $exception = new ApiException('Track failed '.$e->getMessage(), null, 403, 403, 2001);
        return ApiResponse::exception($exception);
    }
  } 
}
