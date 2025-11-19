<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipe extends Model
{
    protected $table="equipe";
    public $timestamps = false;

    protected $fillable = [
        'nom_equipe',
        'description',
        'id_categorie',
        'id_genre',
        'id_etat',
        'ordre_affichage',
    ];

    public function etat()
    {
        return $this->belongsTo(EtatEquipe::class, 'id_etat');
    }

    public function categorie()
    {
        return $this->belongsTo(CategorieEquipe::class, 'id_categorie');
    }

    public function genre()
    {
        return $this->belongsTo(GenreEquipe::class, 'id_genre');
    }

    public function joueurs()
    {
        return $this->belongsToMany(User::class, 'equipe_user', 'id_equipe', 'id_user');
    }

    public function demandesInscription()
    {
        return $this->hasMany(DemandeInscription::class, 'id_equipe', 'id');
    }
}
