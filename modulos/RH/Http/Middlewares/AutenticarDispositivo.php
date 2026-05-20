<?php

namespace Modulos\RH\Http\Middlewares;

use Closure;
use Modulos\RH\Models\DispositivoAcesso;

class AutenticarDispositivo
{
    public function handle($request, Closure $next)
    {
        $token = $request->header('X-Dispositivo-Token')
            ?? $request->input('token');

        if (!$token) {
            return response()->json(['error' => 'Token nao informado.'], 401);
        }

        $dispositivo = DispositivoAcesso::where('dis_token_api', $token)
            ->where('dis_status', 'ativo')
            ->first();

        if (!$dispositivo) {
            return response()->json(['error' => 'Token invalido ou dispositivo inativo.'], 403);
        }

        $request->merge(['dispositivo' => $dispositivo]);

        return $next($request);
    }
}
