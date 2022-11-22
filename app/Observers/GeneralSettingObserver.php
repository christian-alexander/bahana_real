<?php

namespace App\Observers;

use App\GeneralSetting;

class GeneralSettingObserver
{

    public function saving(GeneralSetting $detail)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $detail->company_id = company()->id;
        }
    }
}
