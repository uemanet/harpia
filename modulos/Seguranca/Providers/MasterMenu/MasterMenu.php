<?php

namespace Modulos\Seguranca\Providers\MasterMenu;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Modulos\Seguranca\Providers\Seguranca\Seguranca;

class MasterMenu
{
    protected $auth;
    protected $request;

    public function __construct($app, $request = null)
    {
        $this->auth = $app['auth'];
        $this->request = is_null($request) ? $app['request'] : $request;
    }

    /**
     * Renderiza o menu para o usuario
     * @return string
     */
    public function render()
    {
        if (is_null($this->auth->user())) {
            return '';
        }

        $userId = $this->auth->user()->usr_id;

        // Obtem o modulo a partir da requisicao
        $route = $this->request->route();

        if (is_null($route)) {
            return '';
        }

        $routeName = $route->getName();

        if (empty($routeName)) {
            return '';
        }

        $moduloSlug = explode('.', $routeName)[0];

        $menu = $this->normalizeMenu(Cache::get('MENU_' . $userId, []));

        if (!array_key_exists($moduloSlug, $menu)) {
            // Self-heal menu cache after login/permission changes.
            $seguranca = app(Seguranca::class);
            $seguranca->makeCachePermissoes();
            $seguranca->makeCacheMenu();

            $menu = $this->normalizeMenu(Cache::get('MENU_' . $userId, []));
        }

        if (!array_key_exists($moduloSlug, $menu)) {
            return '';
        }

        $menuTree = $menu[$moduloSlug];

        if (is_null($menuTree) || !method_exists($menuTree, 'getRoot')) {
            return '';
        }

        $root = $menuTree->getRoot();

        $html = view('Seguranca::mastermenu.menu', compact('root'));

        return $html;
    }

    private function normalizeMenu($menu)
    {
        if (is_array($menu)) {
            return $menu;
        }

        if ($menu instanceof Collection) {
            return $menu->toArray();
        }

        return [];
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
        $route = $this->request->route();
        $routeName = is_null($route) ? null : $route->getName();

        if (is_null($routeName) || is_null($obj) || !isset($obj->mit_rota)) {
            return $result;
        }

        if ($this->isActive($routeName, $obj->mit_rota)) {
            $result = true;
        }

        return $result;
    }
}
