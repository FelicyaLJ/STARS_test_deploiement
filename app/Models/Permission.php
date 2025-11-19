<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permission';
    public $timestamps = false;

    protected $appends = ['formatted_name'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'id_permission', 'id_role');
    }

    public function getFormattedNameAttribute()
    {
        $formatted = \Illuminate\Support\Str::of($this->nom_permission)
            ->replace('_', ' ')
            ->title();

        return __((string) $formatted);
    }
}
