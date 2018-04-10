<?php
declare(strict_types=1);

namespace Harpia\Event\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface SincronizacaoLoteInterface
 *
 * @package Harpia\Event\Contracts
 */
interface SincronizacaoLoteInterface
{
    public function getItens(): Collection;

    public function getBaseClass(): string;
}