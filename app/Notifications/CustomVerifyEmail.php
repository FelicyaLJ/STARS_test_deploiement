<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends VerifyEmailBase
{
    /**
     * Get the verification mail message for the given URL.
     */
    public function toMail($notifiable)
    {
        $url = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->from('registers@associationstars.com', 'Association STARS')
            ->subject('Vérifiez votre adresse e-mail')
            ->view('mail.verifyEmail', [
                'url' => $url,
                'notifiable' => $notifiable, // ✅ pass the user here
            ]);
    }

    /**
     * Generate the verification URL (you can customize this too if needed).
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}

