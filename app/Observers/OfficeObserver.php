<?php

namespace App\Observers;

use App\Office;

class OfficeObserver
{

    public function saving(Office $office)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $office->company_id = company()->id;
        }
    }
}
