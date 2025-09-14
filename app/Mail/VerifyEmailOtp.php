<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailOtp extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $code)
    {
    }

    public function build(): self
    {
        return $this->subject('Your Verification Code')
            ->view('mail.verify-otp')
            ->with(['code' => $this->code]);
    }
}



