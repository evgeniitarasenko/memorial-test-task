<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvitationRequest;
use App\Http\Resources\RecipientsResource;
use App\Mail\Invitation;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    public function invite(InvitationRequest $request): AnonymousResourceCollection
    {
        $recipients = $request->input('recipients', []);
        $message = $request->input('message');

        foreach ($recipients as $recipient) {
            // For saving to file set MAIL_MAILER=log or MAIL_MAILER=file (see app/Mails/Drivers)
            Mail::to(new Address($recipient['email'], $recipient['name']))
                ->later(now()->addMinutes(1), new Invitation($recipient, $message));
        }

        return RecipientsResource::collection($recipients);
    }
}
