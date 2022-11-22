<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\EmailToSend;
use App\SlackSetting;
use App\Task;
use App\Traits\SmtpSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use App\User;

class CustomMessageNotif extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $user;
    private $message;
    private $emailSetting;
    private $type;
    private $notif_to;
    public function __construct(User $user, $message, $type, $notif_to, $needSendEmail = null)
    {
        $this->user = $user;
        $this->type = $type;
        $this->notif_to = $notif_to;
        $this->emailSetting = env("NOTIF_USING_EMAIL", false);
        $this->setMailConfigs();
        $this->message = $message;
        $this->needSendEmail = $needSendEmail;
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
        if (empty($this->needSendEmail)) {
            // jika type logout
            if ($this->type=="LOGOUT") {
                $check = EmailToSend::checkNotificationInstant($this->notif_to->id);
                if ($check) {
                    if($this->emailSetting){
                        array_push($via, 'mail');
                    }
                }
                $msg = $this->message;
                EmailToSend::saveData($this->notif_to,$msg);
            }else{
                if($this->emailSetting){
                    array_push($via, 'mail');
                }
            }
        }else{
            if ($this->needSendEmail){ 
                // if true
                // need to send email 
                array_push($via, 'mail');
            }
        }
        array_push($via, OneSignalChannel::class);

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
        return (new MailMessage)
            ->subject($this->message)
            ->line($this->message);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $data =$this->user->toArray();
        $data['notif']['heading'] = $this->message;
        $data['notif']['description'] = $this->message;
        $data['notif']['type'] = $this->type;
        $data['notif']['id'] = $this->user->id;
        $data['notif']['created_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        // $slack = SlackSetting::first();
        // if(count($notifiable->employee) > 0 && (!is_null($notifiable->employee[0]->slack_username) && ($notifiable->employee[0]->slack_username != ''))){
        //     return (new SlackMessage())
        //         ->from(config('app.name'))
        //         ->image($slack->slack_logo_url)
        //         ->to('@' . $notifiable->employee[0]->slack_username)
        //         ->content(ucfirst($this->task->heading).' '.__('email.taskComplete.subject').'.');
        // }
        // return (new SlackMessage())
        //     ->from(config('app.name'))
        //     ->image($slack->slack_logo_url)
        //     ->content('This is a redirected notification. Add slack username for *'.ucwords($notifiable->name).'*');
    }

    public function toOneSignal($notifiable)
    {
        $logo = env("LOGO_ONESIGNAL");
        return OneSignalMessage::create()
            ->subject($this->message)
            ->body($this->message)
            // ->setData("type", "CUSTOM_MESSAGE")
            ->setData("type", $this->type)
            ->setData("id", $this->user->id)
            ->setCustomParameter("small_icon", $logo)
            ->setCustomParameter("large_icon", $logo)
            ->setCustomParameter("android_channel_id", env('ANDROID_CHANNEL_ID'))
            ->setCustomParameter("ttl", 3600)
            ->setCustomParameter("priority", 10);
    }
}
