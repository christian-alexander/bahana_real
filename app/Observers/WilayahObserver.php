<?php

namespace App\Observers;

use App\Wilayah;

class WilayahObserver
{

    public function saving(Wilayah $wilayah)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $wilayah->company_id = company()->id;
        }
    }
}
