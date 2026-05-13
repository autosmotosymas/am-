<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotificarCambioPrecio implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public \App\Models\Vehiculo $vehiculo,
        public float $precioAnterior,
        public float $precioNuevo,
    ) {}

    public function handle(): void
    {
        // Notificar solo si el precio bajó
        if ($this->precioNuevo >= $this->precioAnterior) return;

        // Obtener leads con email que preguntaron por este vehículo
        $emails = $this->vehiculo
            ->leads()
            ->whereNotNull('email')
            ->pluck('email')
            ->unique();

        foreach ($emails as $email) {
            \Illuminate\Support\Facades\Mail::to($email)
                ->queue(new \App\Mail\CambioPrecioMail(
                    $this->vehiculo,
                    $this->precioAnterior,
                    $this->precioNuevo,
                ));
        }
    }
}
