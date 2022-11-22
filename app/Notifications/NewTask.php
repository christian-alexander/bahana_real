<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\EmailToSend;
use App\SlackSetting;
use App\Task;
use App\Traits\SmtpSettings;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class NewTask extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $task;
    private $notif_to;
    private $user;
    private $emailSetting;

    public function __construct(Task $task,$notif_to)
    {
        $this->task = $task;
        $this->notif_to = $notif_to;
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

        // if ($this->emailSetting == 'yes') {
        // }
        
        // if ($this->emailSetting->send_slack == 'yes') {
            //     array_push($via, 'slack');
            // }
            
        $check = EmailToSend::checkNotificationInstant($this->notif_to->id);
        if ($check) {
            if($this->emailSetting){
                array_push($via, 'mail');
            }
        }
        $msg = $this->task->heading . " berhasil dibuat";
        EmailToSend::saveData($this->notif_to,$msg);

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
        if ($this->task->is_meeting==1) {
            return (new MailMessage)
                ->subject(__('email.newMeeting.subject'))
                ->line($this->task->heading);
        }else{
            return (new MailMessage)
                ->subject(__('email.newTask.subject'))
                ->line($this->task->heading);
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $data = $this->task->toArray();
        if ($this->task->is_meeting==1) {
            $data['notif']['heading'] = __('email.newMeeting.subject');
        }else{
            $data['notif']['heading'] = __('email.newTask.subject');
        }
        $data['notif']['description'] = $this->task->heading;
        $data['notif']['type'] = "TASK";
        $data['notif']['id'] = $this->task->id;
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
        $slack = SlackSetting::first();
        if (count($notifiable->employee) > 0 && (!is_null($notifiable->employee[0]->slack_username) && ($notifiable->employee[0]->slack_username != ''))) {
            if ($this->task->is_meeting==1) {
                return (new SlackMessage())
                    ->from(config('app.name'))
                    ->image($slack->slack_logo_url)
                    ->to('@' . $notifiable->employee[0]->slack_username)
                    ->content(__('email.newMeeting.subject'));
            }else{
                return (new SlackMessage())
                    ->from(config('app.name'))
                    ->image($slack->slack_logo_url)
                    ->to('@' . $notifiable->employee[0]->slack_username)
                    ->content(__('email.newTask.subject'));
            }
        }
        return (new SlackMessage())
            ->from(config('app.name'))
            ->image($slack->slack_logo_url)
            ->content('This is a redirected notification. Add slack username for *' . ucwords($notifiable->name) . '*');
    }

    public function toOneSignal($notifiable)
    {
        $logo = env("LOGO_ONESIGNAL");
        if ($this->task->is_meeting==1) {
            return OneSignalMessage::create()
                ->subject(__('email.newMeeting.subject'))
                ->body($this->task->heading)
                ->setData("type", "TASK")
                ->setData("id", $this->task->id)
                ->setCustomParameter("small_icon", $logo)
                ->setCustomParameter("large_icon", $logo)
                ->setCustomParameter("android_channel_id", env('ANDROID_CHANNEL_ID'))
                ->setCustomParameter("ttl", 3600)
                ->setCustomParameter("priority", 10);
        }else{
            return OneSignalMessage::create()
                ->subject(__('email.newTask.subject'))
                ->body($this->task->heading)
                ->setData("type", "TASK")
                ->setData("id", $this->task->id)
                ->setCustomParameter("small_icon", $logo)
                ->setCustomParameter("large_icon", $logo)
                ->setCustomParameter("android_channel_id", env('ANDROID_CHANNEL_ID'))
                ->setCustomParameter("ttl", 3600)
                ->setCustomParameter("priority", 10);
        }
    }
}
