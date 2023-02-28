<?php

namespace App\Mail\Drivers;

use Carbon\Carbon;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\RawMessage;

class FileMailTransport implements TransportInterface
{
    public function __toString(): string
    {
        return 'file';
    }

    public function send(RawMessage $message, Envelope $envelope = null): ?SentMessage
    {
        // TODO: customize file name and folder for mail logs.

        $date = Carbon::now()->format('Y-m-d_H:i:s');
        $uuid = uniqid();
        $file = storage_path("logs/mail_{$date}_{$uuid}.html");

        file_put_contents($file, $message->toString());

        return new SentMessage($message, $envelope ?? Envelope::create($message));
    }
}
