<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Registration Successful')
                    ->view('emails.registration_success')
                    ->with([
                        'name' => $this->user->name,
                        'email' => $this->user->email,
                        'registration_date' => $this->user->created_at->format('d M Y H:i'),
                    ]);
    }
}
