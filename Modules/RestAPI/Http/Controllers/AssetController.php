<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiResponse;
use App\Http\Requests\API\APIRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Modules\Asset\Entities\Asset;
use Modules\Asset\Entities\AssetHistory;
use Modules\Asset\Entities\AssetType;

use App\Company;
class AssetController extends ApiBaseController
{

  public function getAsset(APIRequest $request)
  {
    $request->validate([
      'type' => 'required',
    ]);
    $user = auth()->user();
	$history = DB::table("asset_lending_history")
      ->leftJoin("assets", "assets.id", "=", "asset_lending_history.asset_id")
      ->where("user_id", $user->id)
      ->select("asset_lending_history.id", "assets.name", "asset_lending_history.return_date", "asset_lending_history.date_of_return", "assets.status");
      //->select("asset_lending_history.*", "assets.name", "assets.serial_number", "assets.image", "assets.description", "assets.status");




    if($request->type == "ongoing"){
      $history = $history->whereNull("date_of_return");
    }
    elseif($request->type == "returned"){
      $history = $history->whereNotNull("date_of_return");
    }
    if(isset($request->start_return_date) && !empty($request->start_return_date)){
      $history = $history->where("return_date",">=", date("Y-m-d 00:00:00", strtotime($request->start_return_date)));
    }
    if(isset($request->end_return_date) && !empty($request->end_return_date)){
      $history = $history->where("return_date","<=", date("Y-m-d 23:59:59", strtotime($request->end_return_date)));
    }
    if(isset($request->keyword) && !empty($request->keyword)){
      $history = $history->where("name","LIKE", "%$request->keyword%");
    }
    if(isset($request->status) && !empty($request->status)){
      $history = $history->where("assets.status", "$request->status");
    }
    
	$history = $history->get();
    return ApiResponse::make('List assets', [
      'data' => $history,
      //'user' => $user,
    ]);
  }

  public function getAssetDetail(APIRequest $request)
  {
    $request->validate([
      'id' => 'required',
    ]);

    $user = auth()->user();
    $global = $this->company = Company::withoutGlobalScope('active')->where('id', $user->company_id)->first();
	$history = AssetHistory::leftJoin("assets", "assets.id", "=", "asset_lending_history.asset_id")
      ->leftJoin("users", "users.id", "=", "asset_lending_history.user_id")
      ->where("user_id", $user->id)
      ->where("asset_lending_history.id", $request->id)
      ->select("asset_lending_history.id", "assets.name", "asset_lending_history.return_date", "asset_lending_history.date_of_return","users.name as employee_name","asset_lending_history.qty","asset_lending_history.notes", "asset_lending_history.date_given", "assets.status");
      //->select("asset_lending_history.*", "assets.name", "assets.serial_number", "assets.image", "assets.description", "assets.status");
	$history = $history->first();
    $history->diff = $history->date_given->setTimezone($global->timezone)->diffForHumans(\Carbon\Carbon::now()->setTimezone($global->timezone));
    return ApiResponse::make('Detail asset', [
      'data' => $history,
      //'user' => $user,
    ]);
  }
}
