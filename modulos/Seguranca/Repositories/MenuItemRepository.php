<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Seguranca\Models\MenuItem;

class MenuItemRepository
{
    public function getCategorias($moduloId)
    {
        return MenuItem::where([
            ['mit_mod_id', '=', $moduloId],
            ['mit_visivel', '=', 1],
            ['mit_item_pai', '=', null]
        ])
            ->orderBy('mit_ordem', 'asc')
            ->get();
    }

    public function getItensFilhos($moduloId, $categoriaId)
    {
        return MenuItem::where([
            ['mit_mod_id', '=', $moduloId],
            ['mit_item_pai', '=', $categoriaId],
            ['mit_visivel', '=', 1]
        ])
            ->orderBy('mit_ordem', 'asc')
            ->get();
    }
}
