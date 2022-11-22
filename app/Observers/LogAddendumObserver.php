<?php

namespace App\Observers;

use App\LogAddendum;

class LogAddendumObserver
{

    public function saving(LogAddendum $logAddendum)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $logAddendum->company_id = company()->id;
        }
    }
}
