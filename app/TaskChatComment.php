<?php

namespace App;

use App\Notifications\CommentOrangKepercayaan;
use App\Observers\CabangObserver;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\DB;
use App\Helper\Files;

class TaskChatComment extends BaseModel
{
    protected $table = 'task_chat_comment';

    protected $appends = [
        'already_read'
    ];

    /**
     * Get all of the files for the TaskChatComment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(TaskChatCommentFile::class, 'task_chat_comment_id', 'id');
    }

    public function getAlreadyReadAttribute()
    {
        $userLogin = api_user();
        $read = TaskChatCommentRead::where('user_id',$userLogin->id)->where('task_chat_comment_id',$this->id)->count();
        if ($read>0) {
            return true;
        }
        return false;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function storeModel($request){
        DB::beginTransaction();
        try {
            $userLogin = auth()->user();

            // validate this user is orang kepercayaan
            // get tugas
            $tugas = Task::find($request->task_id);
            if (empty($tugas)) {
                throw new \Exception("Tugas tidak ditemukan");
            }
            
            // $tugas_creator = EmployeeDetails::where('user_id',$tugas->created_by)->first();
            // if (empty($tugas_creator)) {
            //     throw new \Exception("Pembuat tugas tidak ditemukan");
            // }

            // // check orang kepercayaan aktif
            // if ($tugas_creator->is_on_orang_kepercayaan==0) {
            //     throw new \Exception("Pembuat tugas tidak mengaktifkan fitur orang kepercayaan");
            // }

            // // orang kepercayaan creator tugas
            // $tugas_creator_orang_kepercayaan = json_decode($tugas_creator->user_orang_kepercayaan);

            // if (empty($tugas_creator_orang_kepercayaan)) {
            //     throw new \Exception("Pembuat tugas tidak memiliki orang kepercayaan");
            // }

            // // get assignee tugas
            // $assignee_user = EmployeeDetails::where('user_id',$tugas->assignee_user_id)->first();

            // if (empty($assignee_user)) {
            //     throw new \Exception("Assignee user tidak ditemukan");
            // }

            // // check user login adalah orang kepercayaan
            // if (!in_array($userLogin->id,$tugas_creator_orang_kepercayaan)) {
            //     throw new \Exception("Anda bukan orang kepercayaan pembuat tugas");
            // }

            // // get sub_company_orang_kepercayaan
            // $tugas_creator_sub_company_orang_kepercayaan = json_decode($tugas_creator->sub_company_orang_kepercayaan);
            // if (empty($tugas_creator_sub_company_orang_kepercayaan)) {
            //     throw new \Exception("Pembuat tugas tidak mengatur company orang kepercayaan");
            // }

            // // check sub company assignee_user dengan sub_company_orang_kepercayaan
            // if (!in_array($assignee_user->sub_company_id,$tugas_creator_sub_company_orang_kepercayaan)) {
            //     throw new \Exception("Anda tidak diberi wewenang untuk company user pada tugas ini");
            // }

            $assignee_user = EmployeeDetails::where('user_id',$tugas->assignee_user_id)->first();

            if (empty($assignee_user)) {
                throw new \Exception("Some data not found");
            }

            $model = new TaskChatComment;
            $model->company_id=$userLogin->company_id;
            $model->task_id=$request->task_id;
            $model->msg=$request->msg;
            $model->created_by=$userLogin->id;
            if (Task::isOrangKepercayaan($tugas->created_by,$assignee_user->sub_company_id,$userLogin->id)) {
                $model->is_orang_kepercayaan=1;
            }
            $model->save();

            $file = $request->file('files');
            
            if (isset($file) && !empty($file)){
                foreach ($file as $file) {
                    $filename = Files::uploadLocalOrS3($file, "user-comment/$userLogin->id");
                    $modelFile = new TaskChatCommentFile;
                    $modelFile->task_chat_comment_id=$model->id;
                    $modelFile->filename=$filename;
                    $modelFile->hashname=$filename;
                    $modelFile->url_file="user-comment/$userLogin->id/$filename";
                    $modelFile->save();
                }
            }

            $flagErrorMail = false;
            if ($model->is_orang_kepercayaan==1) {
                //send notif
                // ketika orang kepercayaan mengirim comment maka kirim notif ke atasan/yg mendelegasikan
                $notif_atasan = User::find($tugas->created_by);
                if (isset($notif_atasan) && !empty($notif_atasan)) {
                    try {
                        $notif_atasan->notify(new CommentOrangKepercayaan($tugas,$notif_atasan));
                    } catch (\Throwable $th) {
                        $flagErrorMail = true;
                    }
                }
            }

            DB::commit();
            if ($flagErrorMail){
                return model_response(true,'Data berhasil disimpan, Email error silahkan hubungi developer',$model);
            }else{
                return model_response(true,'Data berhasil disimpan',$model);
            }
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
    public static function updateModel($request,$id){
        DB::beginTransaction();
        try {
            $userLogin = auth()->user();

            // validate this user is orang kepercayaan
            // get tugas
            $model = TaskChatComment::where('created_by',$userLogin->id)->find($id);
            if (empty($model)) {
                throw new \Exception("Komentar tidak ditemukan");
            }
            $model->msg=$request->msg;
            $model->save();

            $file = $request->file('files');

            if (isset($request->files_to_delete) && !empty($request->files_to_delete)){ 
                foreach ($request->files_to_delete as $item) {
                    // find file
                    $file_to_delete = TaskChatCommentFile::where('task_chat_comment_id',$model->id)->find($item);
                    if ($file_to_delete) {
                        if (\File::exists(public_path($file_to_delete->url_file))) {
                            // remove prev image
                            \File::delete(public_path($file_to_delete->url_file));
                        }
                        $file_to_delete->delete();
                    }
                }
            }

            if (isset($file) && !empty($file)){
                foreach ($file as $file) {
                    $filename = Files::uploadLocalOrS3($file, "user-comment/$userLogin->id");
                    $modelFile = new TaskChatCommentFile;
                    $modelFile->task_chat_comment_id=$model->id;
                    $modelFile->filename=$filename;
                    $modelFile->hashname=$filename;
                    $modelFile->url_file="user-comment/$userLogin->id/$filename";
                    $modelFile->save();
                }
            }

            DB::commit();
            return model_response(true,'Data berhasil diupdate',$model);
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
    public static function deleteModel($request){
        DB::beginTransaction();
        try {
            $userLogin = auth()->user();

            // validate this user is orang kepercayaan
            // get tugas
            $model = TaskChatComment::where('created_by',$userLogin->id)->find($request->id);
            if (empty($model)) {
                throw new \Exception("Komentar tidak ditemukan");
            }
            // get file
            $file_to_deletes = TaskChatCommentFile::where('task_chat_comment_id',$model->id)->get();
            foreach ($file_to_deletes as $item) {
                if (\File::exists(public_path($item->url_file))) {
                    // remove prev image
                    \File::delete(public_path($item->url_file));
                }
                $item->delete();
            }
            $model->delete();
            DB::commit();
            return model_response(true,'Data berhasil dihapus',$model);
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
}
