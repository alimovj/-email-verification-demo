<?php

namespace App\Notifications;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyReminderNotification extends Notification
{
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Eslatma: Emailingizni tasdiqlang')
            ->line('Siz hali emailingizni tasdiqlamadingiz.')
            ->action('Emailni Tasdiqlash', url('/email/verify'));
    }
}
