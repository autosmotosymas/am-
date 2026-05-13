<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotificarSuscripcionVence implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    public function handle(): void
    {
        $avisos = [7, 3, 1]; // días antes de vencimiento para avisar

        foreach ($avisos as $dias) {
            $fecha = now()->addDays($dias)->toDateString();

            \App\Models\Suscripcion::with('agencia')
                ->where('status', 'activa')
                ->whereDate('fecha_vencimiento', $fecha)
                ->each(function ($sus) use ($dias) {
                    if (! $sus->agencia?->email) return;

                    \Illuminate\Support\Facades\Mail::to($sus->agencia->email)
                        ->queue(new \App\Mail\SuscripcionVenceMail($sus, $dias));
                });
        }
    }
}
