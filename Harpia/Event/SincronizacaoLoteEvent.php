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
    protected $items;

    protected $baseClass = "";

    public function __construct(Collection $items, string $action = "CREATE", $extra = null, $version = 'v1')
    {
        $this->extra = $extra;
        $this->items = $items;
        $this->action = $action;
        $this->version = $version;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function getBaseClass(): string
    {
        return $this->baseClass;
    }

    public function getItemsAsEvents(): Collection
    {
        $events = collect([]);

        foreach ($this->items as $item) {

            $events->push(new $this->baseClass($item, $this->extra, $this->version));
        }

        return $events;
    }
}