<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EtatUser extends Model
{
    protected $table = 'etat_user';
    public $timestamps = false;

    public function users() : HasMany
    {
        return $this->hasMany(User::class, 'id_etat');
    }
}
