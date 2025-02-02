<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class reservationDeclined extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('We regret to inform you that your car rental reservation has been rejected.')
            ->line('This may be due to the following reasons:')
            ->line('- Submission of a fake payment receipt.')
            ->line('- Unrealistic or invalid booking dates.')
            ->line('- Missing or incomplete required information.')
            ->line('- Violation of our booking policies or terms of service.')
            ->line('Please double-check the booking details you provided and try again if necessary.')
            ->action('More Details', route('login'))
            ->line('If you need further assistance, you can reach us at: mbtransportcooperative@gmail.com.')
            ->line('Thank you!.');

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
