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

class courrielDemandeAdhesion extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Forum $forum, public User $user, public $raison, public $id)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('demandesadhesion@associationstars.com', 'Demandes d\'adhésion aux forums'),
            subject: 'Demande d\'adhésion de ' . $this->user->prenom . " " . $this->user->nom,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.demandeAdhesion',
            with:[
                'idUser'=> $this->user->id,
                'nomUser' => $this->user->nom,
                'prenomUser' => $this->user->prenom,
                'idForum' => $this->forum->id,
                'forumName' => $this->forum->nom_forum,
                'raison'=> $this->raison,
                'idDemande'=> $this->id,
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
