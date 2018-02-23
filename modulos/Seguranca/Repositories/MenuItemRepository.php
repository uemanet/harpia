<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Core\Repository\BaseRepository;

class MenuItemRepository extends BaseRepository
{
    public function __construct(MenuItem $model)
    {
        parent::__construct($model);
    }

    public function getCategorias($moduloId)
    {
        return MenuItem::where([
            ['mit_mod_id', '=', $moduloId],
            ['mit_visivel', '=', 1],
            ['mit_item_pai', '=', null]
        ])->orderBy('mit_ordem', 'asc')->get();
    }

    public function getItensFilhos($moduloId, $categoriaId)
    {
        return MenuItem::where([
            ['mit_mod_id', '=', $moduloId],
            ['mit_item_pai', '=', $categoriaId],
            ['mit_visivel', '=', 1]
        ])->orderBy('mit_ordem', 'asc')->get();
    }

    public function isCategoria($menuItemId)
    {
        $menuItem = MenuItem::where('mit_id', '=', $menuItemId)->get()->shift();

        if (isset($menuItem->mit_item_pai)) {
            return false;
        }

        if (isset($menuItem->mit_rota)) {
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

        if (isset($menuItem->mit_rota)) {
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

    public function create(array $data)
    {
        $ordem = 1;

        // pega o ultimo item criado, de acordo com os parametros
        $itemPaiId = isset($data['mit_item_pai']) ? $data['mit_item_pai'] : null;

        $item = $this->model->where('mit_mod_id', $data['mit_mod_id'])
            ->where('mit_item_pai', $itemPaiId)
            ->orderBy('mit_ordem', 'desc')
            ->first();

        if ($item) {
            $ordem = $item->mit_ordem + 1;
        }

        $data['mit_ordem'] = $ordem;
        $data['mit_visivel'] = (isset($data['mit_visivel'])) ? 1 : 0;

        return $this->model->create($data);
    }

    public function update(array $data, $id, $attribute = null)
    {
        if (!$attribute) {
            $attribute = $this->model->getKeyName();
        }

        $collection = $this->model->where($attribute, '=', $id)->get();

        if ($collection) {
            $data['mit_visivel'] = (isset($data['mit_visivel'])) ? 1 : 0;
            foreach ($collection as $obj) {
                $obj->fill($data)->save();
            }

            return $collection->count();
        }

        return 0;
    }
}
