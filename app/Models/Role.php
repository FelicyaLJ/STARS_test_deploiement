<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = 'role';
    public $timestamps = false;

    protected $with = [
        'permissions',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_role', 'id_role', 'id_user');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'id_role', 'id_permission');
    }

}
