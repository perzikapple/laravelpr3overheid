<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
});

// Verwijder oude open meldingen (ouder dan 7 dagen) - draait elke dag om 3:00 's nachts
Schedule::command('reports:cleanup')->daily()->at('03:00');
