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

        return view('Geral::pessoas.verificapessoa', ['rota' => $request->route()->getName()]);
    }
}
