<?php

namespace App\Observers;

use App\TipeCuti;

class TipeCutiObserver
{

    public function saving(TipeCuti $tipeCuti)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $tipeCuti->company_id = company()->id;
        }
    }
}
