<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\EmailToSend;
use App\Leave;
use App\LeaveType;
use App\Traits\SmtpSettings;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\DB;

class LeaveApplicationCustom extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $leave;
    private $emailSetting;
    private $cc;
    private $user_id;
    public function __construct(Leave $leave, $cc, $user_id)
    {
        $this->leave = $leave;
        $this->cc = $cc;
        $this->user_id = $user_id;
        $this->emailSetting = env("NOTIF_USING_EMAIL", false);
        $this->setMailConfigs();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = ['database'];
        // get leave type
        $leave_type = LeaveType::where('id',$this->leave->leave_type_id)->first();
        $start_date = Carbon::parse($this->leave->leave_date)->format('d-m-Y');
        $end_date = Carbon::parse($this->leave->leave_date_end)->format('d-m-Y');
        // check user notif instant
        $check = EmailToSend::checkNotificationInstant($this->user_id);

        // get user requester
        $user = DB::table('users')->where('id', $this->user_id)->first();
        // save data to email to send table
        $msg = "Anda mengajukan ".$leave_type->display_name." pada tanggal ".$start_date.' sd '.$end_date;
        EmailToSend::saveData($user,$msg);
        
        foreach ($this->cc as $email) {
            // get user
            $userAtasan = DB::table('users')->where('email', $email)->first();
            $msgAtasan = $user->name.' mengajukan '.$leave_type->display_name. ' pada tanggal '.$start_date.' sd '.$end_date;
            EmailToSend::saveData($userAtasan,$msgAtasan);
        }
        if ($check) {
            if($this->emailSetting){
                array_push($via, 'mail');
            }
        }
        
        return $via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = $notifiable;
        $url = url('/');

        return (new MailMessage)
            ->cc($this->cc)
            ->subject( __('email.leave.applied').' - '.config('app.name'))
            ->greeting(__('email.hello').' '.ucwords($user->name).'!')
            ->line(__('email.leave.applied').':- ')
            ->line(__('app.date').': '.$this->leave->leave_date->format('d M, Y'))
            ->line(__('app.status').': '.ucwords($this->leave->status))
            ->action(__('email.loginDashboard'), route('login'))
            ->line(__('email.thankyouNote'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->leave->toArray();
    }
    
}
