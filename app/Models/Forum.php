<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Forum extends Model
{
    protected $table="forum";
    protected $fillable = ['nom_forum', 'description', 'created_at'];
    protected $appends = ['last_message'];
    const UPDATED_AT=null;

    public function getLastMessageAttribute(){
        return $this->messages()->with('user')->orderBy('created_at', 'desc')->first();
    }

    /**
     * Get all of the messages for the Forum
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'id_forum');
    }

    /**
     * The users that belong to the Forum
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function membres(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'forum_user', 'id_forum', 'id_user');
    }

    public function demandesAdhesion()
    {
        return $this->hasMany(DemandeAdhesion::class, 'id_forum', 'id');
    }
}
