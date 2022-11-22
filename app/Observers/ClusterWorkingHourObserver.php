<?php

namespace App\Observers;

use App\ClusterWorkingHour;

class ClusterWorkingHourObserver
{

    public function saving(ClusterWorkingHour $cluster_working_hour)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $cluster_working_hour->company_id = company()->id;
        }
    }
}
