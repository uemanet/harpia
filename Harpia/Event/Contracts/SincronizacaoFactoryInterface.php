<?php

namespace Harpia\Event\Contracts;

use Modulos\Integracao\Models\Sincronizacao;

interface SincronizacaoFactoryInterface
{
    public static function factorySincronizacao(Sincronizacao $sincronizacao);
}
