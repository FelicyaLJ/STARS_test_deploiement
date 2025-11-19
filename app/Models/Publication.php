<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Publication extends Model
{
    protected $table = 'publication';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = ['id'];

    protected $fillable = [
        'texte',
        'titre',
        'fichier',
        'from_facebook',
        'created_at',
        'updated_at',
        'id_etat'
    ];

    public function etat_publication()
    {
        return $this->belongsTo(EtatPublication::class, 'id_etat');
    }
}
