<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Leave;
use App\LeaveType;
use App\SlackSetting;
use App\Traits\SmtpSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use App\TaskboardColumn;

class LeaveCreate extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $leave;
    private $user;
    private $emailSetting;
    public function __construct(Leave $leave, $user,$mail=true)
    {
        $this->leave = $leave;
        $this->user = $user;
        $this->mail = $mail;
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

        if($this->mail){
            if ($this->emailSetting) {
                array_push($via, 'mail');
            }
        }

        // if ($this->emailSetting->send_slack == 'yes') {
        //     array_push($via, 'slack');
        // }

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
        $getType = LeaveType::find($this->leave->leave_type_id);
        return (new MailMessage)
            ->subject("Leave applied")
            ->line($this->user->name . " mengajukan $getType->type_name");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $getType = LeaveType::find($this->leave->leave_type_id);
        $data = $this->leave->toArray();
        $data['notif']['heading'] = "Leave applied";
        $data['notif']['description'] = $this->user->name . " mengajukan $getType->type_name";
        $data['notif']['type'] = "Leave";
        $data['notif']['id'] = $this->leave->id;
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
        // if (count($notifiable->employee) > 0 && (!is_null($notifiable->employee[0]->slack_username) && ($notifiable->employee[0]->slack_username != ''))) {
        //     return (new SlackMessage())
        //         ->from(config('app.name'))
        //         ->image($slack->slack_logo_url)
        //         ->to('@' . $notifiable->employee[0]->slack_username)
        //         ->content(ucfirst($this->task->heading) . ' ' . __('email.taskUpdate.subject') . '.');
        // }
        // return (new SlackMessage())
        //     ->from(config('app.name'))
        //     ->image($slack->slack_logo_url)
        //     ->content('This is a redirected notification. Add slack username for *' . ucwords($notifiable->name) . '*');
    }

    public function toOneSignal($notifiable)
    {
        // get type
        $getType = LeaveType::find($this->leave->leave_type_id);
        $logo = env("LOGO_ONESIGNAL");
        return OneSignalMessage::create()
            ->subject("Leave applied")
            ->body($this->user->name . " mengajukan $getType->type_name")
            ->setData("type", "Leave")
            ->setData("id", $this->leave->id)
            ->setCustomParameter("small_icon", $logo)
            ->setCustomParameter("large_icon", $logo)
            ->setCustomParameter("android_channel_id", env('ANDROID_CHANNEL_ID'))
            ->setCustomParameter("ttl", 3600)
            ->setCustomParameter("priority", 10);
    }
}
