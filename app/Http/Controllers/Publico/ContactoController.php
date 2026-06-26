<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Mail\ContactoAmmMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactoController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre'      => ['required', 'string', 'max:100'],
            'telefono'    => ['required', 'string', 'max:20'],
            'correo'      => ['required', 'email', 'max:120'],
            'comentarios' => ['required', 'string', 'max:2000'],
        ]);

        Mail::to('developer@autosmotosymas.mx')
            ->send(new ContactoAmmMail(
                nombre:      $data['nombre'],
                telefono:    $data['telefono'],
                correo:      $data['correo'],
                comentarios: $data['comentarios'],
            ));

        return back()->with('contacto_ok', true);
    }
}
