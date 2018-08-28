<?php

namespace App\Http\Listeners;

use Illuminate\Auth\Events\Failed;
use App\Traits\ActivityLogger;

class LogFailedLogin
{
    use ActivityLogger;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Failed $event
     *
     * @return void
     */
    public function handle(Failed $event)
    {
        if (config('LaravelLogger.logFailedAuthAttempts')) {
            ActivityLogger::activity('Failed Login Attempt');
        }
    }
}
