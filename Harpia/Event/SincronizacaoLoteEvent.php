<?php

namespace Harpia\Event;

use Illuminate\Support\Collection;
use Harpia\Event\Contracts\SincronizacaoLoteInterface;

/**
 * Classe base para eventos de sincronizacao em lote
 *
 * @package Harpia\Event
 */
abstract class SincronizacaoLoteEvent extends SincronizacaoEvent implements SincronizacaoLoteInterface
{
    protected $itens;

    protected $baseClass = "";

    public function __construct(Collection $itens, string $action = "CREATE", $extra = null)
    {
        $this->extra = $extra;
        $this->itens = $itens;
        $this->action = $action;
    }

    public function getItens(): Collection
    {
        return $this->itens;
    }

    public function getBaseClass(): string
    {
        return $this->baseClass;
    }
}