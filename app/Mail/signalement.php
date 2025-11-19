<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Message;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class signalement extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Message $message, public User $user, public $raison)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('signalement@associationstars.com', 'Signalement'),
            subject: 'Signalement dans le forum ' . $this->message->forum->nom_forum,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.signalement',
            with:[
                'user_source'=> $this->user->prenom . " " . $this->user->nom,
                'user_prob'=> $this->message->user->prenom . " " . $this->message->user->nom,
                'message' => $this->message->id,
                'texte' => $this->message->texte,
                'forum' => $this->message->forum->nom_forum,
                'raison' => $this->raison,
                'email_source' => $this->user->email,
                'email_prob'=>$this->message->user->email
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
