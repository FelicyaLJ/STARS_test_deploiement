<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewRegisteredUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('registers@associationstars.com', 'Notification - Nouvel utilisateur sur la plateforme'),
            subject: 'Nouvel utilisateur : ' . $this->user->prenom . ' ' . $this->user->nom,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.registeredUser',
            with:[
                'idUser'=> $this->user->id,
                'prenomUser' => $this->user->prenom,
                'nomUser' => $this->user->nom,
                'emailUser' => $this->user->email,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
