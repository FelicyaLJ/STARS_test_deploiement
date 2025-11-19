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

class annulationAdhesion extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user, public ?Equipe $equipe = null, public ?Forum $forum = null)
    {
        if (isset($equipe)){
            $this->sujet="son inscription à l'activité ";
            $this->objet=" d'inscription ";
            $this->nom = $equipe->nom_equipe;
        } else if (isset($forum)){
            $this->sujet = "sa demande d'adhésion au forum ";
            $this->objet = " d'adhésion ";
            $this->nom = $forum->nom_forum;
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('demandesadhesion@associationstars.com', 'Demandes d\'adhésion aux forums'),
            subject: 'Annulation de la demande' . $this->objet . 'de ' . $this->user->prenom . " " . $this->user->nom,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.annulationAdhesion',
            with:[
                'nomUser' => $this->user->nom,
                'prenomUser' => $this->user->prenom,
                'nom' => $this->nom,
                'sujet'=>$this->sujet,
                'objet'=>$this->objet,
                'lien'=>"lambda"
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
