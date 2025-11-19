<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EtatEvenement extends Model
{
    protected $table = 'etat_evenement';
    public $timestamps = false;

    public function evenements()
    {
        return $this->hasMany(Evenement::class, 'id_etat');
    }
}
