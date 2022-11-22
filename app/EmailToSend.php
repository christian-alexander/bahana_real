<?php

namespace App;

use App\Observers\EmailToSendObserver;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\DB;

class EmailToSend extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::observe(EmailToSendObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
    protected $table= "email_to_send";

    public static function checkNotificationInstant($user_id){
        $is_instant = false;
        $employee = EmployeeDetails::where('user_id', $user_id)->first();
        if (isset($employee) && !empty($employee)){ 
            $additional_field = json_decode($employee->additional_field);
            if (isset($additional_field->notifikasi_instant) && !empty($additional_field->notifikasi_instant)){ 
                if ($additional_field->notifikasi_instant=='1') {
                    $is_instant = true;
                }
            }
        }
        return $is_instant;
    }
    public static function saveData($user,$text){
        // check to_user_id already exist
        $check_data = EmailToSend::where('to_user_id',$user->id)->first();
        if ($check_data) {
            // save to detail
            $modelDetail = new EmailToSendDetail;
            $modelDetail->email_to_send_id = $check_data->id;
            $modelDetail->text = $text;
            $modelDetail->save();
        }else{
            $model = new EmailToSend;
            $model->company_id = $user->company_id;
            $model->to_user_id = $user->id;
            $model->save();
            if ($model) {
                // save to detail
                $modelDetail = new EmailToSendDetail;
                $modelDetail->email_to_send_id = $model->id;
                $modelDetail->text = $text;
                $modelDetail->save();
            }
        }
    }
}
