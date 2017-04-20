<?php

namespace Modulos\Geral\Http\Middlewares;

use Closure;

class VerificaPessoaMiddleware
{
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('validado')) {
            if ($request->session()->get('validado')) {
                $request->session()->forget('validado');
                return $next($request);
            }
        }

        $rota = $request->route()->getName();
        $rota = str_replace('.', '-', $rota);

        return redirect()->action('\Modulos\Geral\Http\Controllers\PessoasController@getVerificapessoa',
            ['rota' => $rota]);
    }
}
