<?php

namespace Modulos\Seguranca\Providers\Security\Exceptions;

class ForbiddenException extends SecurityException
{
    public function __construct($message = 'Você não tem permissão para acessar esse recurso.')
    {
        parent::__construct($message);
    }
}
