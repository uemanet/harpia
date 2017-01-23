<?php

namespace Harpia\Event;

interface EventInterface
{
    /**
     * Retorna os dados necessarios para
     * registrar os dados do evento na tabela de sincronizacao
     * @return mixed
     */
    public function getData();
}
