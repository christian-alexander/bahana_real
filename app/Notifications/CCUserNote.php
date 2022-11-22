<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\EmailToSend;
use App\Notes;
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

class CCUserNote extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $note;
    private $notif_to;
    private $emailSetting;
    public function __construct(Notes $note,$notif_to)
    {
        $this->note = $note;
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

        $check = EmailToSend::checkNotificationInstant($this->notif_to->id);
        if ($check) {
            if($this->emailSetting){
                array_push($via, 'mail');
            }
        }
        $msg = "Anda di cc pada sebuah catatan (".$this->note->title.")";
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
        return (new MailMessage)
            ->subject('CC Catatan')
            ->line("Anda di cc pada sebuah catatan");
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $data = $this->note->toArray();
        $data['notif']['heading'] = "CC Catatan";
        $data['notif']['description'] = "Anda di cc pada sebuah catatan ";
        $data['notif']['type'] = "NOTES";
        $data['notif']['note_id'] = $this->note->id;
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
        // code
    }
  
    public function toOneSignal($notifiable)
    {	
      	$logo = env("LOGO_ONESIGNAL");
      	return OneSignalMessage::create()
              ->subject("CC Catatan")
              ->body("Anda di cc pada sebuah catatan ")
              ->setData("type", "NOTES")
              ->setData("note_id", $this->note->id)
              ->setCustomParameter("small_icon",$logo)
              ->setCustomParameter("large_icon",$logo)
              ->setCustomParameter("android_channel_id", env('ANDROID_CHANNEL_ID'))
              ->setCustomParameter("ttl", 3600)
              ->setCustomParameter("priority", 10);
    }
}
