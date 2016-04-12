<?php

namespace Modulos\Seguranca\Providers\Security;

use Illuminate\Contracts\Foundation\Application;
use Modulos\Seguranca\Providers\Security\Exceptions\ForbiddenException;
use Illuminate\Contracts\Auth\Guard;

abstract class AbstractSecurityMiddleware
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
    protected $security;

    /**
     * @param Guard $user
     */
    public function __construct(Guard $auth, Application $app)
    {
        $this->user = $auth->user();
        $this->security = $app->make('security');
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
