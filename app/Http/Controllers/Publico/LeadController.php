<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Jobs\NotificarNuevoLead;
use App\Models\Lead;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'vehiculo_id' => ['required', 'exists:vehiculos,id'],
            'nombre'      => ['required', 'string', 'max:100'],
            'telefono'    => ['required', 'string', 'max:20'],
            'email'       => ['nullable', 'email', 'max:150'],
            'mensaje'     => ['nullable', 'string', 'max:1000'],
            'tipo'        => ['required', 'in:llamada,whatsapp,email,visita'],
        ]);

        $data['user_id'] = auth()->id();
        $data['status']  = 'nuevo';

        $lead = Lead::create($data);

        NotificarNuevoLead::dispatch($lead);

        $vehiculo = Vehiculo::find($data['vehiculo_id']);

        return redirect()
            ->route('vehiculo.show', $vehiculo)
            ->with('lead_enviado', true);
    }
}
