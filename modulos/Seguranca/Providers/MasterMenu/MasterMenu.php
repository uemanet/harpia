<?php

namespace Modulos\Seguranca\Providers\MasterMenu;

use DB;
use Cache;

class MasterMenu
{
    protected $request;
    protected $auth;

    public function __construct($app)
    {
        $this->request = $app['request'];
        $this->auth = $app['auth'];
    }

    /**
     * Renderiza o menu para o usuario
     * @return string
     */
    public function render()
    {
        $userId = $this->auth->user()->usr_id;

        // Obtem o modulo a partir da requisicao
        $routeName = $this->request->route()->getName();
        $moduloSlug = explode('.', $routeName)[0];

        $menu = Cache::get('MENU_' . $userId);
        $menuTree = $menu[$moduloSlug];
        $root = $menuTree->getRoot();

        $html = view('Seguranca::mastermenu.menu', compact('root'));

        return $html;
    }

    private function isActive($rota, $permissao)
    {
        $rota = explode('.', $rota);
        $rota = array_slice($rota, 0, 2);

        $permissao = explode('.', $permissao);
        $permissao = array_slice($permissao, 0, 2);

        if ($rota == $permissao) {
            return true;
        }

        return false;
    }

    public function checkLeafIsActive($node)
    {
        $result = false;

        if ($node->hasChildren()) {
            foreach ($node->getChilds() as $child) {
                $result = $this->checkLeafIsActive($child);

                if ($result) {
                    return true;
                }
            }
        }

        $obj = $node->getData();
        $routeName = $this->request->route()->getName();

        if ($this->isActive($routeName, $obj->mit_rota)) {
            $result = true;
        }

        return $result;
    }
}
