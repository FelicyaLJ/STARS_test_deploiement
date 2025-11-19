<?php

use App\Console\Commands\DeactivateInactiveUsers;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(DeactivateInactiveUsers::class)->daily();
