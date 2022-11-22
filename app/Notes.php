<?php

namespace App;

use App\Notifications\CCUserNote;
use App\Scopes\CompanyScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use App\Helper\Files;

class Notes extends BaseModel
{
    use Notifiable;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }

    public function files()
    {
        return $this->hasMany(NoteFile::class, 'note_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function storeModel($request){
        $user = auth()->user();
        DB::beginTransaction();
        try {
            $cc = (isset($request->cc)) ? array_filter($request->cc) : [];

            // insert 
            $model = new Notes;
            $model->company_id=$user->company_id;
            $model->title=$request->title;
            $model->content=$request->content;
            $model->date=$request->date;
            if (isset($request->note) && !empty($request->note)){ 
                $model->note=$request->note;
            }
            $model->created_by=$user->id;
            if (isset($request->cc)) {
                if (count($cc)>0) {
                    $model->cc=json_encode($request->cc);
                }
            }
            $model->save();

            // send notif to cc_user_id
            foreach ($cc as $cc_user) {
                $cc_user_notif = User::find($cc_user);
                if (isset($cc_user_notif) && !empty($cc_user_notif)) {
                    try {
                        $cc_user_notif->notify(new CCUserNote($model,$cc_user_notif));
                    } catch (\Throwable $th) {
                        $flagErrorMail = true;
                    }
                }
            }

            //save file
            $file = $request->file('files');

            if (isset($file) && !empty($file)){
                foreach ($file as $file) {
                    $filename = Files::uploadLocalOrS3($file, "user-notes/$user->id");
                    $modelFile = new NoteFile;
                    $modelFile->note_id=$model->id;
                    $modelFile->filename=$filename;
                    $modelFile->hashname=$filename;
                    $modelFile->url_file="user-notes/$user->id/$filename";
                    $modelFile->save();
                }
            }

            DB::commit();
            return model_response(true,'Data berhasil disimpan',$model);
        } catch (\Throwable $e) {
            DB::rollback();
            return model_response(false,$e->getMessage());
        }
    }
    public static function updateModel($request,$id){
        $user = auth()->user();
        DB::beginTransaction();
        try {
            if (isset($request->cc) && !empty($request->cc)){ 
                $cc = array_filter($request->cc);
            }

            // update 
            $model = Notes::where('created_by',$user->id)->find($id);

            if (!$model) {
                throw new \Exception("Data tidak ditemukan");
            }

            $model->title=$request->title;
            $model->content=$request->content;
            if (isset($request->note) && !empty($request->note)){ 
                $model->note=$request->note;
            }
            $model->date=$request->date;
            $model->updated_by=$user->id;
            if (isset($request->cc)) {
                if (count($cc)>0) {
                    $model->cc=json_encode($request->cc);
                }
            }
            $model->save();
            if (isset($request->cc)) {
                // send notif to cc_user_id
                foreach ($cc as $cc_user) {
                    $cc_user_notif = User::find($cc_user);
                    if (isset($cc_user_notif) && !empty($cc_user_notif)) {
                        try {
                            $cc_user_notif->notify(new CCUserNote($model,$cc_user_notif));
                        } catch (\Throwable $th) {
                            $flagErrorMail = true;
                        }
                    }
                }
            }

            $file = $request->file('files');

            if (isset($request->files_to_delete) && !empty($request->files_to_delete)){ 
                foreach ($request->files_to_delete as $item) {
                    // find file
                    $file_to_delete = NoteFile::where('note_id',$model->id)->find($item);
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
                    $filename = Files::uploadLocalOrS3($file, "user-notes/$user->id");
                    $modelFile = new NoteFile;
                    $modelFile->note_id=$model->id;
                    $modelFile->filename=$filename;
                    $modelFile->hashname=$filename;
                    $modelFile->url_file="user-notes/$user->id/$filename";
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
        $user = auth()->user();
        DB::beginTransaction();
        try {
            // delete 
            $model = Notes::where('created_by',$user->id)->find($request->id);

            if (!$model) {
                throw new \Exception("Data tidak ditemukan");
            }
            // get file
            $file_to_deletes = NoteFile::where('note_id',$model->id)->get();
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
