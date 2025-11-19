<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    protected $table = 'partenaires';
    public $timestamps = false;

    protected $fillable = ['nom_partenaire', 'lien', 'image', 'ordre_affichage'];
}
