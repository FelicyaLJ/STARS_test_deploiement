<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Equipe;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DemandeEquipe extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Equipe $equipe, public User $user, public $raison)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('applicationsequipe@associationstars.com', 'Applications équipes STARS'),
            subject: $this->user->prenom . " " . $this->user->nom . " souhaite rejoindre l'équipe " . $this->equipe->nom_equipe,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.demandeEquipe',
            with:[
                'idUser'=> $this->user->id,
                'nomUser' => $this->user->nom,
                'prenomUser' => $this->user->prenom,
                'emailUser' => $this->user->email,
                'idEquipe' => $this->equipe->id,
                'equipeName' => $this->equipe->nom_equipe,
                'raison'=> $this->raison,
                //'idDemande'=> $this->id,
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
