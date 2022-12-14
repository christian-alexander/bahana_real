<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Leave;
use App\SlackSetting;
use App\Traits\SmtpSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class NewLeaveRequest extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $leave;
    private $emailSetting;
    private $sendEmail;

    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
        $this->emailSetting = EmailNotificationSetting::where('setting_name', 'New Leave Application')->first();
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

        // if ($this->emailSetting->send_email == 'yes') {
        //     array_push($via, 'mail');
        // }

        // if ($this->emailSetting->send_slack == 'yes') {
        //     array_push($via, 'slack');
        // }

            // array_push($via, OneSignalChannel::class);
        if($this->emailSetting){
            array_push($via, 'mail');
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
        $user = $notifiable;
        $url = url('/');

        return (new MailMessage)
            ->subject(__('email.leaves.subject').' - '.config('app.name'))
            ->greeting(__('email.hello').' '.ucwords($user->name).'!')
            ->line(__('email.leaves.subject').' by: '.ucwords($this->leave->user->name).'.')
            ->line(__('app.date').': '.$this->leave->leave_date->format('d M, Y'))
            ->line(__('modules.leaves.leaveType').': '.$this->leave->type->type_name)
            ->line(__('modules.leaves.reason').':-')
            ->line($this->leave->reason)
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
        $data = $this->leave->toArray();
        $data['notif']['heading'] = __('email.leaves.subject');
        $data['notif']['description'] = 'by '.ucwords($this->leave->user->name);
        $data['notif']['type'] = "LEAVE";
        $data['notif']['id'] = $this->leave->id;
        $data['notif']['created_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    public function toSlack($notifiable)
    {
        $slack = SlackSetting::first();
        if(count($notifiable->employee) > 0 && (!is_null($notifiable->employee[0]->slack_username) && ($notifiable->employee[0]->slack_username != ''))){
            return (new SlackMessage())
                ->from(config('app.name'))
                ->image($slack->slack_logo_url)
                ->to('@' . $notifiable->employee[0]->slack_username)
                ->content(__('email.leaves.subject').':- " by "'.ucwords($this->leave->user->name).'".');
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
            ->subject(__('email.leaves.subject'))
            ->body('by '.ucwords($this->leave->user->name))
            ->setData("type", "LEAVE")
            ->setData("id", $this->leave->id)
            ->setCustomParameter("small_icon", $logo)
            ->setCustomParameter("large_icon", $logo)
            ->setCustomParameter("android_channel_id", env('ANDROID_CHANNEL_ID'))
            ->setCustomParameter("ttl", 3600)
            ->setCustomParameter("priority", 10);
    }
}
