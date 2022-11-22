<?php

namespace App\Observers;

use App\Cabang;

class CabangObserver
{

    public function saving(Cabang $cabang)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $cabang->company_id = company()->id;
        }
    }
}
