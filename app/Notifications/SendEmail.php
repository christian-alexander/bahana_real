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
use App\TaskboardColumn;

class SendEmail extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $details;
    private $emailSetting;
    public function __construct($details)
    {
        $this->details = $details;
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
        if($this->emailSetting){
            array_push($via, 'mail');
        }
        // if($this->emailSetting){
        // }
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
            ->markdown( 'mail.bulk-mail.index', ['details' => $this->details]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->details;
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
        //         ->content(ucfirst($this->task->heading).' '.__('email.taskUpdate.subject').'.');
        // }
        // return (new SlackMessage())
        //     ->from(config('app.name'))
        //     ->image($slack->slack_logo_url)
        //     ->content('This is a redirected notification. Add slack username for *'.ucwords($notifiable->name).'*');
    }
  
    public function toOneSignal($notifiable)
    {	
      	// $logo = env("LOGO_ONESIGNAL");
      	// return OneSignalMessage::create()
        //       ->subject("CC Tugas Selesai")
        //       ->body("Anda di cc pada tugas selesai ")
        //       ->setData("type", "CC_MEETING")
        //       ->setData("task_id", $this->task->id)
        //       ->setCustomParameter("small_icon",$logo)
        //       ->setCustomParameter("large_icon",$logo);
    }
}
