<?php

namespace Modulos\Seguranca\Providers\Seguranca;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Foundation\Application;
use Modulos\Seguranca\Providers\Seguranca\Exceptions\ForbiddenException;

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
        $this->seguranca = $app->make(Seguranca::class);
    }

    /**
     * @param $request
     * @return mixed
     */
    protected function getRouteName($request)
    {
        if ($request->route()) {
            return $request->route()->getName();
        }

        return "";
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
