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

class CCUser extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $task;
    private $notif_to;
    private $emailSetting;
    public function __construct(Task $task,$notif_to)
    {
        $this->task = $task;
        $this->notif_to = $notif_to;
        // $this->emailSetting = EmailNotificationSetting::where('setting_name', 'CC Meeting')->first();
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

        $check = EmailToSend::checkNotificationInstant($this->notif_to->id);
        if ($check) {
            if($this->emailSetting){
                array_push($via, 'mail');
            }
        }
        $msg = "Anda di cc pada tugas selesai (".$this->task->heading.")";
        EmailToSend::saveData($this->notif_to,$msg);

        // if($this->emailSetting->send_slack == 'yes'){
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
        return (new MailMessage)
            ->subject('CC Tugas Selesai')
            ->line("Anda di cc pada tugas selesai ");
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
        $data['notif']['heading'] = "CC Tugas Selesai";
        $data['notif']['description'] = "Anda di cc pada tugas selesai ";
        $data['notif']['type'] = "CC_MEETING";
        $data['notif']['task_id'] = $this->task->id;
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
        //         ->content(ucfirst($this->task->heading).' '.__('email.taskUpdate.subject').'.');
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
              ->subject("CC Tugas Selesai")
              ->body("Anda di cc pada tugas selesai ")
              ->setData("type", "CC_MEETING")
              ->setData("task_id", $this->task->id)
              ->setCustomParameter("small_icon",$logo)
              ->setCustomParameter("large_icon",$logo)
              ->setCustomParameter("android_channel_id", env('ANDROID_CHANNEL_ID'))
              ->setCustomParameter("ttl", 3600)
              ->setCustomParameter("priority", 10);
    }
}
