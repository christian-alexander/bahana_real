<?php

namespace App\Observers;


use App\Notifications\FileUpload;
use App\Project;
use App\ProjectFile;

class ProjectFileObserver
{

    public function created(ProjectFile $file)
    {
        $flagErrorMail = false;
        if (!isRunningInConsoleOrSeeding()) {
            $project = Project::with('members', 'members.user')->findOrFail($file->project_id);

            foreach ($project->members as $member) {
                try {
                    $member->user->notify(new FileUpload($file));
                } catch (\Throwable $th) {
                    $flagErrorMail = true;
                }
                // $member->user->notify(new FileUpload($file));
            }
        }
    }

    public function saving(ProjectFile $file)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $file->company_id = company()->id;
        }
    }

}
