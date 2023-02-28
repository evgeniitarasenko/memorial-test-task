<?php

namespace App\Mail\Drivers;

use Illuminate\Mail\Mailer;
use Illuminate\Mail\MailManager;

class FileMailManager extends MailManager
{
    protected function createFileTransport(): FileMailTransport
    {
        return new FileMailTransport();
    }
}
