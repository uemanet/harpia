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

    /**
     * Renderiza Itens de uma categoria ou subcategoria
     * @param array $itens
     * @param $modulo
     * @return string
     */
    private function renderizaItens(array $itens, $modulo, $class = false)
    {

        if (empty($itens))
            return '';

        $result = '';

        foreach ($itens as $key => $item) {
            $recurso = mb_strtolower(preg_replace('/\s+/', '', $item['rcs_rota']));
            if ($class) {
                $result .= '<li class="' . $recurso . '">' .
                    '<a href="' . url("/") . '/' . $modulo . '/' . $recurso . '/' . $item['prm_nome'] . '">' .
                    '<i class="' . $item['rcs_icone'] . '"></i>'
                    . ucfirst($item['rcs_nome']) . '</a>'
                    . '</li>';
                continue;
            }

            $result .= '<li id="' . $recurso . '">' .
                '<a href="' . url("/") . '/' . $modulo . '/' . $recurso . '/' . $item['prm_nome'] . '">' .
                '<i class="' . $item['rcs_icone'] . '"></i>'
                . ucfirst($item['rcs_nome']) . '</a>'
                . '</li>';
        }

        return $result;
    }

    /**
     * Renderiza Itens de uma subcategoria
     * @param array $subcategorias
     * @param $modulo
     * @return string
     */
    private function renderizaSubcategorias(array $subcategorias, $modulo)
    {
        if (empty($subcategorias))
            return '';

        $result = '<li class="treeview">';

        foreach ($subcategorias as $key => $subcategoria) {
            $result .= '<a href="#"><i class="' . $subcategoria['ctr_icone'] . '">' .
                '</i><span>' . $subcategoria['ctr_nome'] . '</span>' .
                '<i class="fa fa-angle-left pull-right"></i></a>';

            if (!empty($subcategorias['ITENS'])) {
                $result .= '<ul class="treeview-menu" style="display: block;">';
                $result .= $this->renderizaItens($subcategorias['ITENS'], $modulo, true);
                $result .= '</ul>';
            }
        }
        $result .= '</li>';

        return $result;
    }
}
