<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserApprovedNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['database']; // store in DB
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Approval Successful',
            'message' => 'Your account has been approved by the admin. You can now start using the system.',
        ];
    }
}
