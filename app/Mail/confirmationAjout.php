<?php

namespace App\Mail;

use App\Models\Equipe;
use App\Models\Evenement;
use App\Models\User;
use App\Models\Forum;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class confirmationAjout extends Mailable
{
    use Queueable, SerializesModels;

    public $sujet = "";
    public $objet = "";
    public $nom = "";
    public $lien = "";
    public $redirection = "";

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user, public ?Equipe $equipe = null, public ?Forum $forum = null)
    {
        if (isset($equipe)){
            $this->sujet=" à l'activité ";
            $this->objet=" l'activité ";
            $this->redirection = " activités ";
            $this->nom = $equipe->nom_equipe;
            $this->lien = config('app.url') . '/inscription/show';
        } else if (isset($forum)){
            $this->sujet = " au forum ";
            $this->objet = " le forum ";
            $this->redirection = " forums ";
            $this->nom = $forum->nom_forum;
            $this->lien = config('app.url') . '/forums';
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('inscription@associationstars.com', 'Demande d\'inscription'),
            subject: 'Vous avez été ajouté.e ' . $this->sujet . $this->nom,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.confirmationAjout',
            with:[
                'nom' => $this->nom,
                'sujet'=>$this->sujet,
                'objet'=>$this->objet,
                'lien'=>$this->lien,
                'redirection'=>$this->lien
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
