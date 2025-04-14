<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReminderToVerifyEmail extends Notification
{
    use Queueable;
    public function toMail($notifiable)
    {
        $url = route('verification.verify', ['id' => $notifiable->id, 'hash' => sha1($notifiable->email)]);
        $users = User::whereNull('email_verified_at')
        ->whereDate('created_at', now()->subDays(2))
        ->get();
    
    foreach ($users as $user) {
        $user->notify(new ReminderToVerifyEmail());
    }
    
        return (new MailMessage)
            ->subject('Emailingizni hali tasdiqlamagansiz')
            ->line('Tasdiqlash uchun quyidagi linkni bosing:')
            ->action('Tasdiqlash', $url);
    }
}    