<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FAQ extends Model
{
    protected $table = "faq";

    protected $fillable = [
        'titre',
        'texte',
        'lien',
        'fichier',
        'ordre_affichage',
        'created_at',
        'updated_at',
        'id_categorie',
    ];

    /**
     * Get the categorie that owns the FAQ
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(CategorieFAQ::class, 'id_categorie');
    }
}
