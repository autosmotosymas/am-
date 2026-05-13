<?php

use App\Jobs\NotificarSuscripcionVence;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Revisar suscripciones por vencer — corre todos los días a las 9 AM hora CDMX
Schedule::job(new NotificarSuscripcionVence)
    ->dailyAt('09:00')
    ->timezone('America/Mexico_City')
    ->name('notificar-suscripcion-vence')
    ->withoutOverlapping();
