<?php

namespace App\Observers;

use App\EmailToSend;

class EmailToSendObserver
{

    public function saving(EmailToSend $email_to_send)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $email_to_send->company_id = company()->id;
        }
    }
}
