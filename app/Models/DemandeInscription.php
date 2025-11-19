<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemandeInscription extends Model
{
    protected $table="demande_inscriptions";
    protected $fillable = ['id_user', 'id_equipe', 'id_evenement'];
    public $timestamps=false;

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function equipe()
    {
        return $this->belongsTo(Equipe::class, 'id_equipe', 'id');
    }

    public function evenement()
    {
        return $this->belongsTo(evenement::class, 'id_evenement', 'id');
    }
}
