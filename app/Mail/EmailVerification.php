<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $key_request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $key_request)
    {
        $this->user = $user;
        $this->key_request = $key_request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.verify')->from("info@dbp4.org")->with(['email_token' => $this->user->email_token]);
    }
}
