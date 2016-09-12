<?php

namespace Modulos\Seguranca\Providers\MasterMenu;

use DB;
use Cache;

class MasterMenu extends Renderizar
{
    protected $request;
    protected $auth;

    public function __construct($app)
    {
        $this->request = $app['request'];
        $this->auth = $app['auth'];
    }

    public function make()
    {
    }

    public function render()
    {
        $usrId = $this->auth->user()->usr_id;

        // Obtem o modulo a partir da requisicao
        $modulo = current(preg_split('/\//', $this->request->path()));

        $menu = Cache::get('MENU_' . $usrId);
        $menu = $menu[$modulo];

        $render = '<ul class="sidebar-menu">';

        foreach ($menu['CATEGORIAS'] as $key => $categorias) {
            $render .= '<li class="treeview">';

            $render .= '<a href="#">' . '<i class="' . $categorias['ctr_icone'] . '">'.
                '</i><span>' . ucfirst($categorias['ctr_nome']) . '</span>'.
                '<i class="fa fa-angle-left pull-right"></i></a>';


            $render .= '<ul class="treeview-menu" style="display: block;">';

            $render .= $this->renderizaItens($categorias['ITENS'], $modulo);

            if (!empty($categorias['SUBCATEGORIA']))
                $render .= $this->renderizaSubcategorias($categorias['SUBCATEGORIA'], $modulo);

            $render .= '</ul>';
            $render .= '</li>';
        }

        $render .= '</ul>';
        return $render;
    }
}
