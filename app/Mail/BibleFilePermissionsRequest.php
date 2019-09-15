<?php

namespace App\Mail;

use App\Models\User\User;
use App\Models\Bible\Bible;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BibleFilePermissionsRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $bible;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Bible $bible
     */
    public function __construct(User $user, Bible $bible)
    {
        $this->user = $user;
        $this->bible = $bible;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'))->markdown('emails.bibleFilePermissionsRequest');
    }
}
