<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $plainPassword;

    public function __construct(User $user, $plainPassword)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword; 
    }

    public function build()
    {
        return $this->subject('Verify Your Email Address')
            ->view('emails.verification')
            ->with([
                'verificationUrl' => config('app.url') . '/api/verify-email/' . $this->user->verification_token,
                'plainPassword' => $this->plainPassword, 
            ]);
    }
}
