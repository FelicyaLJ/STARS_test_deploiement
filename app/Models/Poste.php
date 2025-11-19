<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poste extends Model
{
    protected $table = 'poste';
    public $timestamps = false;

    protected $fillable = [
        'nom_poste',
        'description',
        'salaire',
        'ordre_affichage',
        'id_etat',
    ];
}
