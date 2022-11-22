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
use App\TaskboardColumn;
use App\ProjectTimeLog;
use Carbon\Carbon;
use Modules\RestAPI\Entities\Attendance;
use NotificationChannels\OneSignal\OneSignalWebButton;

class ProjectTimeLogUpdated extends Notification implements ShouldQueue
{
    use Queueable, SmtpSettings;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $log;
    private $emailSetting;
    public function __construct(ProjectTimeLog $log)
    {
        $this->log = $log;
        //$this->emailSetting = EmailNotificationSetting::where('setting_name', 'Task Completed')->first();
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

        if ($this->emailSetting) {
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
        
        if($this->log->status == "in_review"){
            $task = Task::find($this->log->task_id);
            return (new MailMessage)
            ->subject("Pekerjaan menunggu untuk direview")
            ->line("Pekerjaan $task->heading sedang menunggu untuk direview oleh atasan");
        }
        
        if($this->log->status == "accepted"){
            $task = Task::find($this->log->task_id);
            return (new MailMessage)
            ->subject("Pekerjaan diterima oleh atasan")
            ->line("Pekerjaan $task->heading telah diterima oleh atasan");
        }
        if($this->log->status == "rejected"){
            $task = Task::find($this->log->task_id);
            return (new MailMessage)
            ->subject("Pekerjaan ditolak oleh atasan")
            ->line("Pekerjaan $task->heading telah ditolak oleh atasan, dengan alasan :".empty($this->log->reason)?$this->log->reason:"-");
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
        $data = $this->log->toArray();
        if($this->log->status == "in_review"){
            $data['notif']['heading'] = "Pekerjaan menunggu untuk direview";
            $data['notif']['description'] = "Pekerjaan ini sedang menunggu untuk direview oleh atasan";
        }

        if($this->log->status == "accepted"){
            $data['notif']['heading'] = "Pekerjaan diterima oleh atasan";
            $data['notif']['description'] = "Pekerjaan ini telah diterima oleh atasan";
        }

        if($this->log->status == "rejected"){
            $data['notif']['heading'] = "Pekerjaan ditolak oleh atasan";
            $data['notif']['description'] = "Alasan penolakan : ".empty($this->log->reason)?$this->log->reason:"-";
        }
        // get gtm/timezone by last attendance
        $timezone = 7;
        $attendance =Attendance::where('user_id',$this->log->user_id)->orderBy('id','desc')->first();
        if ($attendance) {
            $timezone=$attendance->clock_in_timezone;
        }
        $date = Carbon::now()->addHours($timezone)->format('Y-m-d H:i:s');
        $data['notif']['type'] = "TIMELOG";
        $data['notif']['id'] = $this->log->id;
        // $data['notif']['created_at'] = date('Y-m-d H:i:s');
        $data['notif']['created_at'] = $date;
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
      	/*
        $slack = SlackSetting::first();
        if(count($notifiable->employee) > 0 && (!is_null($notifiable->employee[0]->slack_username) && ($notifiable->employee[0]->slack_username != ''))){
            return (new SlackMessage())
                ->from(config('app.name'))
                ->image($slack->slack_logo_url)
                ->to('@' . $notifiable->employee[0]->slack_username)
                ->content(ucfirst($this->task->heading).' '.__('email.taskUpdate.subject').'.');
        }
        return (new SlackMessage())
            ->from(config('app.name'))
            ->image($slack->slack_logo_url)
            ->content('This is a redirected notification. Add slack username for *'.ucwords($notifiable->name).'*');
            */
    }
  
    public function toOneSignal($notifiable)
    {	/*
      	$taskBoardInProgress = TaskboardColumn::where("slug", "in_progress")->where("company_id", $this->task->company_id)->first();
      	$taskBoardInReview = TaskboardColumn::where("slug", "in_review")->where("company_id", $this->task->company_id)->first();
        if($this->task->board_column_id == $taskBoardInProgress->id){
          return OneSignalMessage::create()
              ->subject("Task dikerjakan")
              ->body($this->task->heading." mulai dikerjakan");
        }
      	else if($this->task->board_column_id == $taskBoardInReview->id){
          return OneSignalMessage::create()
              ->subject("Task menunggu direview")
              ->body($this->task->heading." pengerjaan selesai dan menunggu direview");
        }
        */
      //print_r($this->log->status);exit();
      
      	$icon["id"] = "cancel";
      	$icon["text"] = "Cancel";
      	$icon["icon"] = "";
      	$button[] = $icon;
      	$buttons = $button;
      
      	$logo = env("LOGO_ONESIGNAL");
      	if($this->log->status == "in_review"){
          return OneSignalMessage::create()
              ->subject("Pekerjaan menunggu untuk direview")
              ->body("Pekerjaan ini sedang menunggu untuk direview oleh atasan")
              ->setData("type", "TIMELOG")
              ->setData("id", $this->log->id)
              ->setCustomParameter("small_icon",$logo)
              ->setCustomParameter("large_icon",$logo)
              ->setCustomParameter("buttons",$buttons)
              ->setCustomParameter("android_channel_id", env('ANDROID_CHANNEL_ID'))
              ->setCustomParameter("ttl", 3600)
              ->setCustomParameter("priority", 10);
          
        }
      
      	if($this->log->status == "accepted"){
          return OneSignalMessage::create()
              ->subject("Pekerjaan diterima oleh atasan")
              ->body("Pekerjaan ini telah diterima oleh atasan")
              ->setData("type", "TIMELOG")
              ->setData("id", $this->log->id)
              ->setCustomParameter("small_icon",$logo)
              ->setCustomParameter("large_icon",$logo)
              ->setCustomParameter("buttons",$buttons)
              ->setCustomParameter("android_channel_id", env('ANDROID_CHANNEL_ID'))
              ->setCustomParameter("ttl", 3600)
              ->setCustomParameter("priority", 10);
          
        }
      	if($this->log->status == "rejected"){
          return OneSignalMessage::create()
              ->subject("Pekerjaan ditolak oleh atasan")
              ->body("Alasan penolakan : ".empty($this->log->reason)?$this->log->reason:"-")
              ->setData("type", "TIMELOG")
              ->setData("id", $this->log->id)
              ->setCustomParameter("small_icon",$logo)
              ->setCustomParameter("large_icon",$logo)
              ->setCustomParameter("buttons",$buttons)
              ->setCustomParameter("android_channel_id", env('ANDROID_CHANNEL_ID'))
              ->setCustomParameter("ttl", 3600)
              ->setCustomParameter("priority", 10);
          
        }
      
    }
}
