<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategorieEvenement extends Model
{
    protected $table = 'categorie_evenement';
    public $timestamps = false;

    protected $fillable = [
        'nom_categorie',
        'couleur'
    ];

    /**
     * Get all of the evenements for the CategorieEvenement
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function evenements()
    {
        return $this->hasMany(Evenement::class, 'id_categorie');
    }
}
