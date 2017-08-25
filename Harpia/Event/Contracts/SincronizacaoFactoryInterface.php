<?php

namespace Harpia\Event\Contracts;

use Modulos\Integracao\Models\Sincronizacao;

interface SincronizacaoFactoryInterface
{
    /**
     * Cria um evento de sincronizacao com base em seu registro na tabela de sincronizacao
     * @param Sincronizacao $sincronizacao
     * @throws \Exception
     * @return \Harpia\Event\SincronizacaoEvent
     */
    public static function factory(Sincronizacao $sincronizacao);
}
