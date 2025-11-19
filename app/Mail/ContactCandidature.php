<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactCandidature extends Mailable
{
    use Queueable, SerializesModels;

    public $prenom;
    public $nom;
    public $email;
    public $tel;
    public $adresse;
    public $poste;
    public $messageCandidature;
    public $cvPath;

    /**
     * Create a new message instance.
     */
    public function __construct($prenom, $nom, $email, $tel, $adresse, $poste, $messageCandidature, $cvPath)
    {
        $this->prenom             = $prenom;
        $this->nom                = $nom;
        $this->email              = $email;
        $this->tel                = $tel;
        $this->adresse            = $adresse;
        $this->poste              = $poste; // ← NOM du poste (pas l'id)
        $this->messageCandidature = $messageCandidature;
        $this->cvPath             = $cvPath;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $email = $this->subject("Nouvelle candidature – {$this->prenom} {$this->nom}")
                      ->markdown('emails.candidature');

        // Ajouter CV en pièce jointe
        if ($this->cvPath) {
            $email->attach(storage_path("app/public/" . $this->cvPath));
        }

        return $email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Contact Candidature',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.candidature',
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
