<?php

namespace App\Mail;

use App\Models\Forum;
use App\Models\User;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $prenom,
        public string $nom,
        public string $email,
        public ?string $tel,
        public ?string $addresse,
        public string $sujet,
        public string $content
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->email, "{$this->prenom} {$this->nom}"),
            subject: "{$this->sujet}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.contact',
            with: [
                'prenom'   => $this->prenom,
                'nom'      => $this->nom,
                'email'    => $this->email,
                'tel'      => $this->tel,
                'addresse' => $this->addresse,
                'sujet'    => $this->sujet,
                'content '  => $this->content,
            ],
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
