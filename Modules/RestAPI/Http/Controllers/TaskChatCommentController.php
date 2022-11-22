<?php

namespace Modules\RestAPI\Http\Controllers;

use App\EmployeeDetails;
use App\TaskChatComment;
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
use App\Task;
use App\TaskChatCommentRead;
use Modules\RestAPI\Entities\User;

class TaskChatCommentController extends ApiBaseController
{
    public function listData(APIRequest $request)
    {
        $request->validate([
            'task_id' => 'required'
        ]);
        try {
            $user = auth()->user();
            // check task exist
            $task = Task::find($request->task_id);
            if (empty($task)) {
                return response()->json([
                    'error' => [
                    'status' => 500,
                    'message' => 'Tugas tidak ditemukan',
                    ]
                ]);               
            }

            // check user have access to view this chat
            // $check_user = Task::orangKepercayaanCanViewTugas($task->id);
            // if (empty($check_user)) {
            //     return response()->json([
            //         'error' => [
            //         'status' => 500,
            //         'message' => 'Anda tidak memiliki akses untuk menggunakan fitur ini',
            //         ]
            //     ]);
            // }
            // if (!in_array($user->id,$check_user)) {
            //     return response()->json([
            //         'error' => [
            //         'status' => 500,
            //         'message' => 'Anda tidak memiliki akses untuk menggunakan fitur ini',
            //         ]
            //     ]);
            // }

            $chat = TaskChatComment::with('files')->where('company_id',$user->company_id)
                ->where('task_id',$request->task_id);
            if (isset($request->limit) && !empty($request->limit)) {
                $chat = $chat->limit($request->limit);
            }
            if (isset($request->offset) && !empty($request->offset)) {
                $chat = $chat->offset($request->offset);
            }
            $chat = $chat->get();

            foreach ($chat as $key => $item) {
                if ($item->is_orang_kepercayaan==1) {
                    // get task data
                    $task = Task::find($item->task_id);
                    // get creator task
                    $creator_task = EmployeeDetails::where('user_id',$task->created_by)->first();
                    // check orang kepercayaan
                    $check_orang_kepercayaan = Task::checkUserIsMyAtasanOrangKepercayaan($creator_task->user_id,$user->id,$task->id);
                    
                    if (!$check_orang_kepercayaan) {
                        unset($chat[$key]);
                    }
                    // check user login is creator of comment/chat
                    if ($item->created_by!=$user->id) {
                        if ($creator_task->user_id!=$user->id) {
                            unset($chat[$key]);
                        }
                    }
                }
                // add permission to update and delete comment
                $can_edit_delete = false;
                if ($user->id==$item->created_by) {
                    $can_edit_delete = true;
                }
                $item->can_edit_delete = $can_edit_delete;
                $item->user = $item->user;
                $item->comment_at = $item->created_at;
                $item->comment_updated_at = $item->updated_at;
            }

            // update is opened
            // if ($task->created_by==$user->id) {
            //     $updateChat = TaskChatComment::where('task_id',$request->task_id)
            //         ->where('is_opened',0)
            //         ->get();
            //     foreach ($updateChat as $val) {
            //         $val->is_opened=1;
            //         $val->save();
            //     }
            // }
            return ApiResponse::make('Get chat success', [
                'chat' => $chat
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
        $request->validate([
            'task_id' => 'required',
            'msg' => 'required'
        ]);
        try{
            // store chat
            $model = TaskChatComment::storeModel($request);
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
                'chat' => $model['data']
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
            'msg' => 'required'
        ]);
        try{
            // store notes
            $model = TaskChatComment::updateModel($request,$id);
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
            $model = TaskChatComment::deleteModel($request);
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
    public function markRead(APIRequest $request){
        $request->validate([
            'comment_id' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $userLogin = api_user();
            // check data exist
            $check = TaskChatCommentRead::where('user_id',$userLogin->id)->where('task_chat_comment_id',$request->comment_id)->count();
            if ($check==0) {
                $model = new TaskChatCommentRead;
                $model->user_id = $userLogin->id;
                $model->task_chat_comment_id = $request->comment_id;
                $model->save();
            }
            DB::commit();
            return ApiResponse::make('Data berhasil disimpan');
        } catch (\Throwable $e) {
            DB::rollback();
            $exception = new ApiException('Data not found '.$e->getMessages(), null, 403, 403, 2001);
            return ApiResponse::exception($exception);
        }
    }
}
