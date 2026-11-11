<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    public $confirmationUrl;

    public function __construct($confirmationUrl)
    {
        $this->confirmationUrl = $confirmationUrl;
    }

    public function build()
    {
        return $this->subject('Confirm Your MIFFA Account')
                    ->view('emails.confirm-account');
    }
}