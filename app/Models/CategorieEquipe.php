<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategorieEquipe extends Model
{
    protected $table = 'categorie_equipe';
    public $timestamps = false;

    public function equipes() : HasMany
    {
        return $this->hasMany(Equipe::class, 'id_categorie');
    }
}
