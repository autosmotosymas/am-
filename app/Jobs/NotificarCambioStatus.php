<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotificarCambioStatus implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public \App\Models\Vehiculo $vehiculo,
        public string $statusAnterior,
        public string $statusNuevo,
    ) {}

    public function handle(): void
    {
        // Solo notificar si cambia a apartado o vendido
        if (! in_array($this->statusNuevo, ['apartado', 'vendido'])) return;

        $emails = $this->vehiculo
            ->leads()
            ->whereNotNull('email')
            ->pluck('email')
            ->unique();

        foreach ($emails as $email) {
            \Illuminate\Support\Facades\Mail::to($email)
                ->queue(new \App\Mail\CambioStatusMail(
                    $this->vehiculo,
                    $this->statusNuevo,
                ));
        }
    }
}
