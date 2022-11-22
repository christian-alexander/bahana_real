<?php

namespace App\Observers;

use App\Pertanyaan;

class PertanyaanObserver
{

    public function saving(Pertanyaan $pertanyaan)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $pertanyaan->company_id = company()->id;
        }
    }
}
