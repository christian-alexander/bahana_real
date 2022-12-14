<?php

namespace App\Observers;


use App\Notifications\NewProjectMember;
use App\ProjectMember;

class ProjectMemberObserver
{

    public function saving(ProjectMember $member)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $member->company_id = company()->id;
        }
    }

    public function created(ProjectMember $member)
    {
        $flagErrorMail = false;
        if (!app()->runningInConsole() ) {
            try {
                $member->user->notify(new NewProjectMember($member));
            } catch (\Throwable $th) {
                $flagErrorMail = true;
            }
            // $member->user->notify(new NewProjectMember($member));
        }
    }
}
