<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RideAcceptedNotification extends Notification
{
    use Queueable;

    public $driver;
    public $ride;

    public function __construct($driver, $ride)
    {
        $this->driver = $driver;
        $this->ride = $ride;
    }

    public function via($notifiable)
    {
        return ['mail']; // You can also add 'database' if you want in-app notifications
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Ride Request Has Been Accepted 🚖')
            ->greeting('Hello ' . $notifiable->fullname . '!')
            ->line('Your ride request has been accepted by a driver.')
            ->line('**Driver Name:** ' . $this->driver->fullname)
            ->line('**Driver Email:** ' . $this->driver->email)
            ->line('**Driver Phone:** ' . $this->driver->phone_number)
            ->line('**Pickup Location:** ' . $this->ride->pickup_location)
            ->line('**Drop-off Location:** ' . $this->ride->dropoff_location)
            ->line('Please be ready at your pickup location.')
            ->salutation('Thank you for using TAREPPA!');
    }
}
