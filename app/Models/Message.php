<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $table="message";
    protected $fillable = ['texte', 'created_at', 'id_response', 'id_forum', 'id_user'];
    const UPDATED_AT=null;

    /*

    * Get the user that owns the Message
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function forum(): BelongsTo{
        return $this->belongsTo(Forum::class, 'id_forum');
    }

    /**
     * Get the reponse associated with the Message
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function reponse(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'id_reponse');
    }
}
