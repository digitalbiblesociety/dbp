<?php

namespace App\Models\Notifications;

use Illuminate\Notifications\Notifiable;

class NotifyToSlack
{
    use Notifiable;
    public function routeNotificationForSlack()
    {
        return config('logging.channels.slack.url');
    }
}
