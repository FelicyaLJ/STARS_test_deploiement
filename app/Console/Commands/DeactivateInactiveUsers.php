<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class DeactivateInactiveUsers extends Command
{
    protected $signature = 'users:deactivate-inactive';
    protected $description = 'Change l\'état d\'un utilisateur qui ne s\'est pas authentifié dans les 30 derniers jours à inactif.';

    public function handle()
    {
        $limit = Carbon::now()->subDays(30);

        $count = User::where('id_etat', 1)
            ->where(function($q) use ($limit) {
                $q->where('last_login_at', '<', $limit)
                ->orWhereNull('last_login_at');
            })
            ->update(['id_etat' => 2]);

        $this->info("$count utilisateurs sont désormais inactifs.");
    }
}


