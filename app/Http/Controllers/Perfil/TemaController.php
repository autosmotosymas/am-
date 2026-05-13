<?php

namespace App\Http\Controllers\Perfil;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TemaController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'tema' => ['required', 'in:dark,light,system'],
        ]);

        $request->user()->update(['tema' => $request->tema]);

        return response()->json(['ok' => true]);
    }
}
