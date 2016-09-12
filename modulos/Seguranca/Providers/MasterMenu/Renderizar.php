<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 12/09/16
 * Time: 16:48
 */

namespace Modulos\Seguranca\Providers\MasterMenu;


class Renderizar
{
    /**
     * Renderiza Itens de uma categoria ou subcategoria
     * @param array $itens
     * @param $modulo
     * @return string
     */
    public function renderizaItens(array $itens, $modulo, $class = false)
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
    public function renderizaSubcategorias(array $subcategorias, $modulo)
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