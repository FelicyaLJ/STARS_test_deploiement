<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'no_telephone',
        'nam',
        'id_etat',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $with = [
        'etat',
        'roles',
        'equipes'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Surcharge personnalisée de la méthode par défaut
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\CustomVerifyEmail());
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_role', 'id_user', 'id_role');
    }

    public function etat()
    {
        return $this->belongsTo(EtatUser::class, 'id_etat');
    }

    public function equipes()
    {
        return $this->belongsToMany(Equipe::class, 'equipe_user', 'id_user', 'id_equipe');
    }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('nom_permission', $permission);
            })
            ->exists();
    }

    public function demandesInscription()
    {
        return $this->hasMany(DemandeInscription::class, 'id_user', 'id');
    }

    public function demandesAdhesion()
    {
        return $this->hasMany(DemandeAdhesion::class, 'id_user', 'id');
    }


}
