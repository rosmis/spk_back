<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mime\Email;

class OtpConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly int $otp) {}

    public function build(): self
    {
        // this is a dummy mail template to prevent the invalid view error
        $this->subject('OTP Confirmation')
            ->view('mail.otp');

        $this->withSymfonyMessage(function (Email $message) {
            $message->getHeaders()
                ->addHeader('templateId', 2)
                ->addParameterizedHeader('params', 'params', [
                    'otp' => $this->otp,
                ]);
        });

        return $this;
    }
}
