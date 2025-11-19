<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exercice extends Model
{
    protected $table="exercice";
    protected $fillable = ['nom_exercice', 'texte', 'fichier', 'image', 'lien', 'ordre_affichage', 'id_forum', 'id_categorie'];
    public $timestamps=false;

    /**
     * Get the categorie that owns the Exercice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(CategorieExercice::class, 'id_categorie');
    }

    /**
     * Get the forum that owns the Exercice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function forum(): BelongsTo
    {
        return $this->belongsTo(Forum::class, 'id_forum');
    }
}
