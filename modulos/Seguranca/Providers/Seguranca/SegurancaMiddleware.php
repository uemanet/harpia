<?php

namespace Modulos\Seguranca\Providers\Seguranca;

use DB;
use Flash;
use Config;
use Closure;
use Illuminate\Http\Request;

class SegurancaMiddleware extends AbstractSegurancaMiddleware
{

    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!env('IS_SECURITY_ENNABLED')) {
            return $next($request);
        }

        $path = $this->getRouteName($request);

        if ($this->seguranca->haspermission($path)) {
            return $next($request);
        }

        Flash::error('Você não term permissão para acessar esse recurso.');
        return redirect()->back();
    }
}
