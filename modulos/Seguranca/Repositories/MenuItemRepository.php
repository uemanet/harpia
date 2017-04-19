<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Seguranca\Models\MenuItem;

class MenuItemRepository
{
    public function getCategorias($moduloId)
    {
        return MenuItem::where([
            ['modulos_id', '=', $moduloId],
            ['visivel', '=', 1],
            ['menu_itens_pai', '=', null]
        ])
            ->orderBy('ordem', 'asc')
            ->get();
    }

    public function getItensFilhos($moduloId, $categoriaId)
    {
        return MenuItem::where([
            ['modulos_id', '=', $moduloId],
            ['menu_itens_pai', '=', $categoriaId],
            ['visivel', '=', 1]
        ])
            ->orderBy('ordem', 'asc')
            ->get();
    }
}
