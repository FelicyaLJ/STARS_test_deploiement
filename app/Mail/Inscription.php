<?php

namespace App\Mail;

use App\Models\Equipe;
use App\Models\Evenement;
use App\Models\User;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Inscription extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Equipe $equipe, public User $user, public Evenement $evenement, public $id)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('inscription@associationstars.com', 'Demandes d\'inscription'),
            subject: 'Inscription de ' . $this->user->prenom,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.inscription',
            with:[
                'idUser'=> $this->user->id,
                'nomUser' => $this->user->nom,
                'emailUser' => $this->user->email,
                'prenomUser' => $this->user->prenom,
                'idEquipe' => $this->equipe->id,
                'activiteName' => $this->equipe->nom_equipe,
                'idEvenement'=> $this->evenement->id,
                'prix'=>$this->evenement->prix,
                'idDemande'=>$this->id,
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
