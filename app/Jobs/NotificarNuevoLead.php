<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotificarNuevoLead implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public \App\Models\Lead $lead) {}

    public function handle(): void
    {
        $lead     = $this->lead->load(['vehiculo.agencia']);
        $agencia  = $lead->vehiculo?->agencia;

        if (! $agencia?->email) return;

        \Illuminate\Support\Facades\Mail::to($agencia->email)
            ->send(new \App\Mail\NuevoLeadMail($lead));
    }
}
