<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategorieExercice extends Model
{
    protected $table ="categorie_exercice";
    protected $fillable=["nom_categorie", "ordre_affichage"];
    public $timestamps=false;

    /**
     * Get all of the exercices for the CategorieExercice
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exercices(): HasMany
    {
        return $this->hasMany(Exercice::class, 'id_categorie');
    }
}
