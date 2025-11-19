<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Evenement extends Model
{
    protected $table = 'evenement';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = ['id'];

    protected $fillable = [
        'nom_evenement',
        'description',
        'date',
        'heure_debut',
        'heure_fin',
        'prix',
        'id_categorie',
        'id_etat',
        'id_terrain'
    ];

    protected $with = [
        'etat',
        'terrain',
        'categorie',
        'equipes',
    ];

    protected $casts = [
        'date' => 'date',
        'heure_debut' => 'datetime:H:i:s',
        'heure_fin' => 'datetime:H:i:s',
    ];

    public function terrain()
    {
        return $this->belongsTo(Terrain::class, 'id_terrain', 'id');
    }

    public function categorie()
    {
        return $this->belongsTo(CategorieEvenement::class, 'id_categorie', 'id');
    }

    public function etat()
    {
        return $this->belongsTo(EtatEvenement::class, 'id_etat', 'id');
    }

    public function equipes()
    {
        return $this->belongsToMany(Equipe::class, 'equipe_evenement', 'id_evenement', 'id_equipe');
    }

    /**
     * Scopes
     */

    /**
     * Scope to filter by date
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date)->where('id_etat', '!=', 4);
    }

    /**
     * Scope to filter by terrain
     */
    public function scopeForTerrain($query, $terrainId)
    {
        return $query->where('id_terrain', $terrainId);
    }

    /**
     * Scope to filter by upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', Carbon::today())->where('id_etat', '!=', 4);
    }

    /**
     * Scope to filter by past events
     */
    public function scopePast($query)
    {
        return $query->where('date', '<', Carbon::today())->where('id_etat', '!=', 4);
    }

    /**
     * Get events for a specific date
     */
    public static function getEvenementsForDate(string $date)
    {
        return self::with(['terrain', 'categorie', 'etat'])
            ->where('date', $date)->where('id_etat', '!=', 4)
            ->get();
    }

    public static function getEvenementsInRange(string $startDate, string $endDate, bool $onlyUpcoming = false)
    {
        $query = self::with('categorie:id,couleur')
            ->whereBetween('date', [$startDate, $endDate]);

        if ($onlyUpcoming) {
            $query->where('date', '>=', Carbon::today());
        }

        return $query->get()->where('id_etat', '!=', 4)
            ->groupBy(fn($e) => Carbon::parse($e->date)->format('Y-m-d'))
            ->map(fn($events) => $events->map(fn($e) => [
                'id' => $e->id,
                'etat' => 'Réservé',
                'color' => $e->categorie->couleur ?? '#f87171',
            ])->values());
    }

    public function demandesInscription()
    {
        return $this->hasMany(DemandeInscription::class, 'id_evenement', 'id');
    }
}
