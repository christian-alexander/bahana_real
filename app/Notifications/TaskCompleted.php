<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
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

class TaskCompleted extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $task;
    private $emailSetting;
    private $sendEmail;
    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->emailSetting = EmailNotificationSetting::where('setting_name', 'Task Completed')->first();
        $this->sendEmail = env("NOTIF_USING_EMAIL", false);
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

        // if($this->emailSetting->send_email == 'yes'){
        // }
        
        if($this->emailSetting->send_slack == 'yes'){
            array_push($via, 'slack');
        }
        
        if($this->emailSetting->send_push == 'yes'){
            if ($this->sendEmail) {
                array_push($via, 'mail');
            }
            array_push($via, OneSignalChannel::class);
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
        return (new MailMessage)
            ->subject(__('email.taskComplete.subject'))
            ->line(ucfirst($this->task->heading).' '.__('email.taskComplete.subject').'.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->task->toArray();
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
        if(count($notifiable->employee) > 0 && (!is_null($notifiable->employee[0]->slack_username) && ($notifiable->employee[0]->slack_username != ''))){
            return (new SlackMessage())
                ->from(config('app.name'))
                ->image($slack->slack_logo_url)
                ->to('@' . $notifiable->employee[0]->slack_username)
                ->content(ucfirst($this->task->heading).' '.__('email.taskComplete.subject').'.');
        }
        return (new SlackMessage())
            ->from(config('app.name'))
            ->image($slack->slack_logo_url)
            ->content('This is a redirected notification. Add slack username for *'.ucwords($notifiable->name).'*');
    }

    public function toOneSignal($notifiable)
    {
      
      	$icon["id"] = "cancel";
      	$icon["text"] = "Cancel";
      	$icon["icon"] = "";
      	$button[] = $icon;
      	$buttons = $button;
      
        return OneSignalMessage::create()
            ->subject(__('email.taskComplete.subject'))
            ->body(ucfirst($this->task->heading).' '.__('email.taskComplete.subject'))
            ->setCustomParameter("buttons",$buttons);
    }
}
