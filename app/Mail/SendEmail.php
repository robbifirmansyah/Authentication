<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $registration_date;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->registration_date = $data['registration_date'];
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Registration Successful!')
                    ->view('emails.sendemail');
    }
}
