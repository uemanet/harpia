<?php

namespace Modulos\Seguranca\Providers\Seguranca;

use Illuminate\Contracts\Foundation\Application;
use Modulos\Seguranca\Providers\Seguranca\Exceptions\ForbiddenException;
use Illuminate\Contracts\Auth\Guard;

abstract class AbstractSegurancaMiddleware
{
    /**
     * Usuário logado do sistema
     *
     * @var
     */
    protected $user;

    /**
     * Instancia do servico de seguranca
     *
     * @var
     */
    protected $seguranca;

    /**
     * @param Guard $user
     */
    public function __construct(Guard $auth, Application $app)
    {
        $this->user = $auth->user();
        $this->seguranca = $app->make('seguranca');
    }

    /**
     * @param $request
     *
     * @return mixed
     */
    protected function getActions($request)
    {
        $routeActions = $request->route()->getAction();

        return $routeActions;
    }

    /**
     * Handles the forbidden response.
     *
     * @return mixed
     */
    protected function forbiddenResponse()
    {
        throw new ForbiddenException();
    }
}