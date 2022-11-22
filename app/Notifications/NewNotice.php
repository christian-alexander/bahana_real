<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\EmailToSend;
use App\Notice;
use App\SlackSetting;
use App\Traits\SmtpSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class NewNotice extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $notice;
    private $created;
    private $notif_to;
    private $emailSetting;

    public function __construct(Notice $notice,$created,$notif_to)
    {
        $this->notice = $notice;
        $this->created = $created;
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

        // if($this->emailSetting->send_email == 'yes'){
        // }
        
        // if($this->emailSetting->send_slack == 'yes'){
        //     array_push($via, 'slack');
        // }
        
        // if($this->emailSetting->send_push == 'yes'){
        // }
        // $check = EmailToSend::checkNotificationInstant($this->notif_to->id);
        // if ($check) {
        //     if($this->emailSetting){
        //         array_push($via, 'mail');
        //     }
        // }

        // $msg = $this->created->name . " membuat pengumuan (".$this->notice->heading.")";
        // EmailToSend::saveData($this->notif_to,$msg);
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
                ->subject(__('email.newNotice.subject').' ('.$this->notice->heading.') - '.config('app.name'))
                ->greeting(__('email.hello').' '.ucwords($notifiable->name).'!')
                ->line($this->notice->description);
                // ->action(__('email.loginDashboard'), url('/login'))
                // ->line(__('email.thankyouNote'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->notice->toArray();
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
                ->image(asset('storage/slack-logo/'.$slack->slack_logo))
//                ->to('@abhinav')
                ->to('@'.$notifiable->employee[0]->slack_username)
                ->content('*'.__('email.newNotice.subject').' : ' . ucfirst($this->notice->heading) . '*'."\n" . $this->notice->description);
        }
        return (new SlackMessage())
            ->from(config('app.name'))
            ->image($slack->slack_logo_url)
            ->content('This is a redirected notification. Add slack username for *'.ucwords($notifiable->name).'*');
    }

    public function toOneSignal($notifiable)
    {
        $logo = env("LOGO_ONESIGNAL");
      	return OneSignalMessage::create()
              ->subject("Pengumuman Baru")
              ->body($this->notice->heading)
              ->setData("type", "NOTICE")
              ->setData("notice_id", $this->notice->id)
              ->setCustomParameter("small_icon",$logo)
              ->setCustomParameter("large_icon",$logo)
              ->setCustomParameter("android_channel_id", env('ANDROID_CHANNEL_ID'))
              ->setCustomParameter("ttl", 3600)
              ->setCustomParameter("priority", 10);
        // return OneSignalMessage::create()
        //     ->subject(__('email.newNotice.subject'))
        //     ->body($this->notice->heading);
    }
}
