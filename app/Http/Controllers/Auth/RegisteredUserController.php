<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // Honeypot: si el campo oculto viene lleno, es un bot
        if ($request->filled('website')) {
            return redirect()->route('register');
        }

        $blockedDomains = [
            'mail.ru', 'inbox.ru', 'list.ru', 'bk.ru',
            'guerrillamail.com', 'tempmail.com', 'yopmail.com',
            'mailinator.com', 'trashmail.com', 'sharklasers.com',
            'guerrillamailblock.com', 'grr.la', 'guerrillamail.info',
            'spam4.me', 'getairmail.com', 'dispostable.com',
        ];

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email'    => [
                'required', 'string', 'lowercase', 'email', 'max:255', 'unique:users',
                function ($attr, $val, $fail) use ($blockedDomains) {
                    $domain = strtolower(substr(strrchr($val, '@'), 1));
                    if (in_array($domain, $blockedDomains)) {
                        $fail('Este dominio de correo no está permitido para el registro.');
                    }
                },
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'telefono' => $request->telefono,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'tema'     => 'dark',
        ]);

        // Todo usuario nuevo es comprador por defecto
        $user->assignRole('comprador');

        event(new Registered($user));

        Auth::login($user);

        return redirect($user->redirigirSegunRol());
    }
}
