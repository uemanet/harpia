<?php

namespace Modulos\Core\Providers\Seguranca\Exceptions;

class ForbiddenException extends SegurancaException
{
    public function __construct($message = 'Você não tem permissão para acessar esse recurso.')
    {
        parent::__construct($message);
    }
}
