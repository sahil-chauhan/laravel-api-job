<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserPinActivation extends Mailable
{
    use Queueable, SerializesModels;

    protected $pin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pin)
    {
        $this->pin = $pin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->view('mail.user-invite-pin')
                    ->with([
                        'pin' => $this->pin,
                    ]);
    }
}
