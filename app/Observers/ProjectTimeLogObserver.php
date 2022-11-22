<?php

namespace App\Observers;

use App\ProjectTimeLog;

  
use App\Events\TaskEvent;
use App\Events\TaskUpdated as EventsTaskUpdated;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Notifications\TaskCompleted;
use App\Notifications\TaskUpdated;
use App\Notifications\TaskUpdatedClient;
use App\Task;
use App\TaskboardColumn;
use App\Traits\ProjectProgress;
use App\UniversalSearch;
use App\User;
use Illuminate\Support\Facades\Notification;

use App\Notifications\ProjectTimeLogUpdated;

class ProjectTimeLogObserver
{

    public function saving(ProjectTimeLog $log)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $log->company_id = company()->id;
        }
    }
    public function updated(ProjectTimeLog $log)
    {
        $flagErrorMail = false;
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        $assignee = User::find($log->user_id);
        try {
            $assignee->notify(new ProjectTimeLogUpdated($log));
        } catch (\Throwable $th) {
            $flagErrorMail = true;
        }
        // $assignee->notify(new ProjectTimeLogUpdated($log));
    }

}
