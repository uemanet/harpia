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
        $menu = $menu[$moduloSlug];

        $render = '<ul class="sidebar-menu">';

        foreach ($menu->categorias as $key => $categoria) {
            if (!empty($categoria->subcategorias)) {
                $categoriaActiveHtml = '<li class="treeview">';
                $categoriaHtml = '<a href="#">';
                $categoriaHtml .= '<i class="'.$categoria->mit_icone.'"></i>';
                $categoriaHtml .= '<span>'.$categoria->mit_nome.'</span>';
                $categoriaHtml .= '<span class="pull-right-container">';
                $categoriaHtml .= '<i class="fa fa-angle-left pull-right"></i>';
                $categoriaHtml .= '</span></a>';
                $categoriaHtml .= '<ul class="treeview-menu">';

                foreach ($categoria->subcategorias as $subcategoria) {
                    $subcategoriaHtml = '';

                    if (!$subcategoria->mit_rota && !empty($subcategoria->itens)) {
                        $subcategoriaActiveHtml = '<li><a href="#"><i class="'.$subcategoria->mit_icone.'"></i> '.$subcategoria->mit_nome;
                        $subcategoriaHtml .= '<span class="pull-right-container">';
                        $subcategoriaHtml .= '<i class="fa fa-angle-left pull-right"></i>';
                        $subcategoriaHtml .= '</span></a>';

                        $subcategoriaHtml .= '<ul class="treeview-menu">';

                        $itensHtml = '';
                        foreach ($subcategoria->itens as $key => $item) {
                            $itensHtml .= '<li';
                            if ($this->isActive($routeName, $item->mit_rota)) {
                                $itensHtml .= ' class="active"';

                                // Active na subcategoria
                                $subcategoriaActiveHtml = '<li class="active"><a href="#"><i class="'.$subcategoria->mit_icone.'"></i> '.$subcategoria->mit_nome;

                                // Active na categoria
                                $categoriaActiveHtml = '<li class="treeview active">';
                            }
                            $itensHtml .= '>';
                            $itensHtml .= '<a href="'.route($item->mit_rota).'"><i class="'.$item->mit_icone.'"></i> '.$item->mit_nome.'</a></li>';
                        }

                        $subcategoriaHtml .= $itensHtml;
                        $subcategoriaHtml .= "</ul></li>";

                        $categoriaHtml .= $subcategoriaActiveHtml;
                        $categoriaHtml .= $subcategoriaHtml;
                        continue;
                    }

                    $subcategoriaHtml .= '<li';
                    if ($this->isActive($routeName, $subcategoria->mit_rota)) {
                        $subcategoriaHtml .= ' class="active"';
                        $categoriaActiveHtml = '<li class="treeview active">';
                    }
                    $subcategoriaHtml .= '>';
                    $subcategoriaHtml .= '<a href="'.route($subcategoria->mit_rota).'"><i class="'.$subcategoria->mit_icone.'"></i> '.$subcategoria->mit_nome.'</a></li>';

                    $categoriaHtml .= $subcategoriaHtml;
                }

                $categoriaHtml .= "</ul></li>";

                $render .= $categoriaActiveHtml;
                $render .= $categoriaHtml;
            }
        }

        $render .= "</ul>";

        return $render;
    }

    private function isActive($rota, $permissao)
    {
        $rota = explode('.', $rota, 2);
        $permissao = explode('.', $permissao, 2);

        if ($rota == $permissao) {
            return true;
        }

        return false;
    }
}
