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

class TaskUpdated extends Notification implements ShouldQueue
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
        if (is_object($this->notif_to)) {
            $this->notif_to = $this->notif_to->first();
        }
        $check = EmailToSend::checkNotificationInstant($this->notif_to->id);
        if ($check) {
            if($this->emailSetting){
                array_push($via, 'mail');
            }
        }
        
        $taskBoardInProgress = TaskboardColumn::where("slug", "in_progress")->where("company_id", $this->task->company_id)->first();
        $taskBoardInReview = TaskboardColumn::where("slug", "in_review")->where("company_id", $this->task->company_id)->first();
        if($this->task->board_column_id == $taskBoardInProgress->id){
            $msg = $this->task->heading." mulai dikerjakan";
        }else if($this->task->board_column_id == $taskBoardInReview->id){
            $msg = $this->task->heading." pengerjaan selesai dan menunggu direview";
        }else{
            $msg = $this->task->heading." telah diubah";
        }
        
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
        $taskBoardInProgress = TaskboardColumn::where("slug", "in_progress")->where("company_id", $this->task->company_id)->first();
        $taskBoardInReview = TaskboardColumn::where("slug", "in_review")->where("company_id", $this->task->company_id)->first();
        if($this->task->board_column_id == $taskBoardInProgress->id){
            return (new MailMessage)
                ->subject("Task dikerjakan")
                ->line($this->task->heading." mulai dikerjakan");
        }else if($this->task->board_column_id == $taskBoardInReview->id){
            return (new MailMessage)
                ->subject("Task menunggu direview")
                ->line($this->task->heading." pengerjaan selesai dan menunggu direview");
        }else{
            return (new MailMessage)
                ->subject("Task diubah")
                ->line($this->task->heading." telah diubah");
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
        return $this->task->toArray();

        $taskBoardInProgress = TaskboardColumn::where("slug", "in_progress")->where("company_id", $this->task->company_id)->first();
        $taskBoardInReview = TaskboardColumn::where("slug", "in_review")->where("company_id", $this->task->company_id)->first();
        
        $data = $this->task->toArray();
        if($this->task->board_column_id == $taskBoardInProgress->id){
            $data['notif']['heading'] = "Task dikerjakan";
            $data['notif']['description'] = $this->task->heading." mulai dikerjakan";
        }
        else if($this->task->board_column_id == $taskBoardInReview->id){
            $data['notif']['heading'] = "Task menunggu direview";
            $data['notif']['description'] = $this->task->heading." pengerjaan selesai dan menunggu direview";
        }else{
            $data['notif']['heading'] = "Task diubah";
            $data['notif']['description'] = $this->task->heading." telah diubah";
        }

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
    }
  
    public function toOneSignal($notifiable)
    {	
      	$logo = env("LOGO_ONESIGNAL");
      	$taskBoardInProgress = TaskboardColumn::where("slug", "in_progress")->where("company_id", $this->task->company_id)->first();
      	$taskBoardInReview = TaskboardColumn::where("slug", "in_review")->where("company_id", $this->task->company_id)->first();
      
      	$icon["id"] = "cancel";
      	$icon["text"] = "Cancel";
      	$icon["icon"] = "";
      	$button[] = $icon;
      	$buttons = $button;
      
        if($this->task->board_column_id == $taskBoardInProgress->id){
          return OneSignalMessage::create()
              ->subject("Task dikerjakan")
              ->body($this->task->heading." mulai dikerjakan")
              ->setData("type", "TASK")
              ->setData("id", $this->task->id)
              ->setCustomParameter("small_icon",$logo)
              ->setCustomParameter("large_icon",$logo)
              ->setCustomParameter("buttons",$buttons)
              ->setCustomParameter("android_channel_id", env('ANDROID_CHANNEL_ID'))
              ->setCustomParameter("ttl", 3600)
              ->setCustomParameter("priority", 10);
        }
      	else if($this->task->board_column_id == $taskBoardInReview->id){
          return OneSignalMessage::create()
              ->subject("Task menunggu direview")
              ->body($this->task->heading." pengerjaan selesai dan menunggu direview")
              ->setData("type", "TASK")
              ->setData("id", $this->task->id)
              ->setCustomParameter("small_icon",$logo)
              ->setCustomParameter("large_icon",$logo)
              ->setCustomParameter("buttons",$buttons)
              ->setCustomParameter("android_channel_id", env('ANDROID_CHANNEL_ID'))
              ->setCustomParameter("ttl", 3600)
              ->setCustomParameter("priority", 10);
        }else{
          return OneSignalMessage::create()
              ->subject("Task diubah")
              ->body($this->task->heading." telah diubah")
              ->setData("type", "TASK")
              ->setData("id", $this->task->id)
              ->setCustomParameter("small_icon",$logo)
              ->setCustomParameter("large_icon",$logo)
              ->setCustomParameter("buttons",$buttons)
              ->setCustomParameter("android_channel_id", env('ANDROID_CHANNEL_ID'))
              ->setCustomParameter("ttl", 3600)
              ->setCustomParameter("priority", 10);
        }
      	/*
        return OneSignalMessage::create()
            ->subject(__('email.taskUpdate.subject'))
            ->body($this->task->heading);
            */
    }
}
