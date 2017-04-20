<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Seguranca\Models\MenuItem;

class MenuItemRepository
{
    public function find($id)
    {
        return MenuItem::find($id);
    }

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

    public function isCategoria($menuItemId)
    {
        $menuItem = MenuItem::where('mit_id', '=', $menuItemId)->get()->shift();

        if (isset($menuItem->mit_item_pai)) {
            return false;
        }

        if (isset($menuItem->rota)) {
            return false;
        }

        return true;
    }

    public function isSubCategoria($menuItemId)
    {
        $menuItem = MenuItem::where('mit_id', '=', $menuItemId)->get()->shift();

        if (!isset($menuItem->mit_item_pai)) {
            return false;
        }

        if (isset($menuItem->rota)) {
            return false;
        }

        return true;
    }

    public function isItem($menuItemId)
    {
        $menuItem = MenuItem::where('mit_id', '=', $menuItemId)->get()->shift();

        if (isset($menuItem->mit_item_pai) && isset($menuItem->mit_rota)) {
            return true;
        }

        return false;
    }
}
