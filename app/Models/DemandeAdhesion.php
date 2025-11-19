<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemandeAdhesion extends Model
{
    protected $table="demande_adhesions";
    protected $fillable = ['id_user', 'id_forum', 'raison'];
    public $timestamps=false;

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function forum()
    {
        return $this->belongsTo(Forum::class, 'id_forum', 'id');
    }
}
