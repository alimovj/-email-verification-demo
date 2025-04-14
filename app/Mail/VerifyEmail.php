<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $user, public $url) {}

    public function build()
    {
        return $this->subject('Emailni tasdiqlash')
            ->view('emails.verify')
            ->with([
                'name' => $this->user->name,
                'url' => $this->url,
            ]);
    }
}
