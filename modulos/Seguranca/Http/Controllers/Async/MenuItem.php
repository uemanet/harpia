<?php

namespace Modulos\Seguranca\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Repositories\MenuItemRepository;

class MenuItem extends BaseController
{
    protected $menuItemRepository;

    public function __construct(MenuItemRepository $menuItemRepository)
    {
        $this->menuItemRepository = $menuItemRepository;
    }

    public function getItensByModulo($moduloId)
    {
        $itens = \Modulos\Seguranca\Models\MenuItem::where('mit_mod_id', $moduloId)->pluck('mit_nome', 'mit_id');

        return new JsonResponse($itens, 200, [], JSON_UNESCAPED_UNICODE);
    }
}