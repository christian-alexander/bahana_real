<?php

namespace Modules\RestAPI\Http\Controllers;

use Froiden\RestAPI\ApiController;
use Modules\RestAPI\Entities\Notice;
use Modules\RestAPI\Http\Requests\Notice\IndexRequest;
use Modules\RestAPI\Http\Requests\Notice\CreateRequest;
use Modules\RestAPI\Http\Requests\Notice\ShowRequest;
use Modules\RestAPI\Http\Requests\Notice\UpdateRequest;
use Modules\RestAPI\Http\Requests\Notice\DeleteRequest;
use App\Team;
use Illuminate\Support\Facades\DB;
use Froiden\RestAPI\ApiResponse;
use Froiden\RestAPI\Exceptions\ApiException;
use App\Http\Requests\API\APIRequest;
use App\Notes;
use App\Notifications\NewNotice;
use Modules\RestAPI\Entities\User;

class NotesController extends ApiBaseController
{
    public function listData(APIRequest $request)
    {
        try {
            $user = auth()->user();
            $notes = Notes::with('files')
                ->where('company_id',$user->company_id)
                ->where('created_by',$user->id);
            if (isset($request->limit) && !empty($request->limit)) {
                $notes = $notes->limit($request->limit);
            }
            if (isset($request->offset) && !empty($request->offset)) {
                $notes = $notes->offset($request->offset);
            }
            if (isset($request->title) && !empty($request->title)) {
                $notes = $notes->where('title','like','%'.$request->title.'%');
            }
            if (isset($request->date) && !empty($request->date)) {
                $notes = $notes->where('date',$request->date);
            }
            // if (isset($request->catatan_saya) && !empty($request->catatan_saya)) {
            //     if ($request->catatan_saya=="true") {
            //         $notes = $notes->where('cc','like',"%\"" . $user->id . "\"%");
            //     }else{
            //         $notes = $notes->where('created_by',$user->id);
            //     }
            // }else{
            //     $notes = $notes->where('created_by',$user->id);
            // }
            $notes = $notes->get();

            // catatan saya
            $catatan_yg_di_cc_ke_saya = Notes::with('files')
                ->where('company_id',$user->company_id)
                ->where('cc','like',"%\"" . $user->id . "\"%")
                ->get();

            foreach ($notes as $item) {
                $can_edit_delete = false;
                if ($item->created_by==$user->id) {
                    $can_edit_delete = true;
                }
                $item->can_edit_delete = $can_edit_delete;
                $item->user = $item->user;
            }

            foreach ($catatan_yg_di_cc_ke_saya as $item) {
                $item->user = $item->user;
            }
            return ApiResponse::make('Get notes success', [
                'catatan_saya' => $notes,
                'catatan_yg_di_cc_ke_saya' => $catatan_yg_di_cc_ke_saya,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => [
                'status' => 500,
                'message' => 'Internal server error',
                ]
            ]);
        }
        
    }
    public function storeData(APIRequest $request)
    {
        $userLogin = auth()->user();
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'date' => 'required|date_format:Y-m-d',
        ]);
        try{
            // store notes
            $model = Notes::storeModel($request);
            if (!$model['success']) {
                $msg = $model['msg'];
                return response()->json([
                  'error' => [
                    'status' => 500,
                    'message' => $msg,
                  ]
                ]);
              }
            return ApiResponse::make($model['msg'], [
                'notes' => $model['data']
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => [
                'status' => 500,
                'message' => 'Internal server error',
                ]
            ]);
        }
    }
    public function updateData(APIRequest $request,$id)
    {
        $userLogin = auth()->user();
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'date' => 'required|date_format:Y-m-d',
        ]);
        try{
            // store notes
            $model = Notes::updateModel($request,$id);
            if (!$model['success']) {
                $msg = $model['msg'];
                return response()->json([
                  'error' => [
                    'status' => 500,
                    'message' => $msg,
                  ]
                ]);
              }
            return ApiResponse::make($model['msg'], [
                'notes' => $model['data']
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => [
                'status' => 500,
                'message' => 'Internal server error',
                ]
            ]);
        }
    }
    public function deleteData(APIRequest $request)
    {
        $userLogin = auth()->user();
        $request->validate([
            'id' => 'required'
        ]);
        try{
            // store notes
            $model = Notes::deleteModel($request);
            if (!$model['success']) {
                $msg = $model['msg'];
                return response()->json([
                  'error' => [
                    'status' => 500,
                    'message' => $msg,
                  ]
                ]);
              }
            return ApiResponse::make($model['msg'], [
                'notes' => $model['data']
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => [
                'status' => 500,
                'message' => 'Internal server error',
                ]
            ]);
        }
    }
}
