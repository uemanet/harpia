<?php

namespace Modulos\Seguranca\Providers\Security;

use DB;
use Config;
use Closure;
use Flash;
use Illuminate\Http\Request;

class SecurityMiddleware extends AbstractSecurityMiddleware
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param callable                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!env('IS_SECURITY_ENNABLED')) {
            return $next($request);
        }

        $path = $this->getPathInfoArray($request->path());

        if($this->security->haspermission($path)) {
            return $next($request);
        }

        Flash::error('Você não term permissão para acessar esse recurso.');
        // return redirect()->back();
        return redirect('/index');
    }

    /**
     * Retorna os elementos do path info.
     *
     * @param string $pathInfo
     *
     * @return array
     */
    private function getPathInfoArray($pathInfo)
    {
        $path = preg_split('/\//', $pathInfo);

        $pathArray = array_values(array_filter($path, function ($item) {
            return !empty($item);
        }));

        $retorno[0] = isset($pathArray[0]) ? $pathArray[0] : 'index';
        $retorno[1] = isset($pathArray[1]) ? $pathArray[1] : 'index';
        $retorno[2] = isset($pathArray[2]) ? $pathArray[2] : 'index';

        return $retorno;
    }
}
