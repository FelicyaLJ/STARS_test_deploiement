<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Terrain extends Model
{
    protected $table = 'terrain';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = ['id'];

    protected $fillable = [
        'nom_terrain',
        'description',
        'latitude',
        'longitude',
        'adresse',
        'visible',
        'couleur',
        'id_parent',
    ];

    public function terrain_enfant()
    {
        return $this->belongsTo(Terrain::class, 'id_parent');
    }

    public function evenements()
    {
        return $this->hasMany(Evenement::class, 'id_terrain', 'id');
    }

    /*
    public function terrain_parent()
    {
        return $this->HasMany(Terrain::class, 'id_parent');
    }
    */

    /**
     * Get all terrains with their status for a specific date
     */
    public static function getTerrainsStatusForDate(string $date)
    {
        return Terrain::with([
            'evenements' => function ($query) use ($date) {
                $query->where('date', $date)
                      ->with('etat:id,nom_etat');
            }
        ])
        ->get()
        ->map(function ($terrain) {
            $event = $terrain->evenements->first();

            return [
                'id' => $terrain->id,
                'nom_terrain' => $terrain->nom_terrain,
                'description' => $terrain->description,
                'latitude' => $terrain->latitude,
                'longitude' => $terrain->longitude,
                'adresse' => $terrain->adresse,
                'visible' => $terrain->getRawOriginal('visible'),
                'couleur' => $terrain->couleur,
                'id_parent' => $terrain->id_parent,
                'etat' => $event ? 'Réservé' : 'Disponible',
                'id_evenement' => $event->id ?? null,
                'nom_evenement' => $event->nom_evenement ?? null,
                'date' => $event->date ?? null,
                'heure_debut' => $event->heure_debut ?? null,
                'heure_fin' => $event->heure_fin ?? null,
                'etat_evenement' => $event->etat->nom_etat ?? null,
            ];
        });
    }

    /**
     * Check if terrain is available for a specific date and time slot
     */
    public static function isTerrainAvailable(int $terrainId, string $date, string $heureDebut, string $heureFin): bool
    {
        $terrain = self::find($terrainId);

        if (!$terrain) {
            return false;
        }

        $linkedIds = collect([$terrainId]);

        if ($terrain->id_parent) {
            $linkedIds->push($terrain->id_parent);
        }

        $childIds = self::where('id_parent', $terrainId)->pluck('id');
        $linkedIds = $linkedIds->merge($childIds);

        return !DB::table('evenement')
            ->whereIn('id_terrain', $linkedIds)
            ->where('date', $date)
            ->where(function($query) use ($heureDebut, $heureFin) {
                $query->where('heure_debut', '<', $heureFin)
                    ->where('heure_fin', '>', $heureDebut);
            })
            ->exists();
    }

    /**
     * Get available terrains for a specific date and time slot
     */
    public static function getAvailableTerrains(string $date, string $heureDebut, string $heureFin)
    {
        $allTerrains = self::all();

        return $allTerrains->filter(function($terrain) use ($date, $heureDebut, $heureFin) {
            return self::isTerrainAvailable($terrain->id, $date, $heureDebut, $heureFin);
        })->values();
    }

    public static function getTerrainsInRange(string $startDate, string $endDate) : Collection
    {
        return Evenement::with('terrain:id,couleur')
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($e) {
                return Carbon::parse($e->date)->format('Y-m-d');
            })
            ->map(function ($events) {
                return $events->map(function ($e, $index) {
                    return [ 'id' => $e->terrain->id . '-' . $index,
                             'etat' => 'Réservé',
                             'color' => $e->terrain->couleur ?? '#f87171',
                    ];
                })->values();
            });
    }

    public static function getAvailableTerrainsInRange(
        string $startDate,
        string $endDate,
        string $heureDebut,
        string $heureFin,
        ?array $jours = null
    ): Collection
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $allTerrains = self::all();

        $dates = collect();
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($jours && !in_array(strtolower($date->format('l')), $jours)) {
                continue;
            }
            $dates->push($date->format('Y-m-d'));
        }

        return $allTerrains->filter(function($terrain) use ($dates, $heureDebut, $heureFin) {
            foreach ($dates as $date) {
                if (!self::isTerrainAvailable($terrain->id, $date, $heureDebut, $heureFin)) {
                    return false;
                }
            }
            return true;
        })->values();
    }

}
