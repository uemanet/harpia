<?php

namespace Modulos\Seguranca\Providers\Menu;

use Illuminate\Auth\Guard;
use DB;

class Menu
{

    protected $request;
    protected $auth;

    public function __construct($request, $auth)
    {
        $this->request = $request;
        $this->auth = $auth;
    }

    public function render()
    {
        $path = preg_split('/\//', $this->request->path());
        $modulo = current($path);

        $controller = next($path);

        $userId = $this->auth->user()->getAuthIdentifier();


        if (!isset($_COOKIE['Permissoes'])) {
            echo "Cookie named Permissoes is not set!";
            $permissoes = $this->getPermissoes($modulo, $userId);
            $cookie_value = $permissoes;
            setcookie('Permissoes', serialize($cookie_value), time() + (86400 * 30), "/"); // 86400 = 1 day
        }

        $permissoes_cookie = unserialize($_COOKIE['Permissoes']);

        $navigation = $this->getNavigationArray($permissoes_cookie, $controller);

        $html = "<ul class='nav' id='side-menu'>";

        foreach ($navigation as $nav) {
            $active = '';
            if (array_key_exists('activeMenu', $nav)) {
                $active = "class='{$nav['activeMenu']}'";
            }

          //   $html .='<li class="has-user-block">
          //    <div id="user-block" class="collapse">
          //       <div class="item user-block">
          //          <!-- User picture-->
          //          <div class="user-block-picture">
          //             <div class="user-block-status">
          //                <div class="circle circle-success circle-lg"></div>
          //             </div>
          //          </div>
          //          <!-- Name and Job-->
          //          <div class="user-block-info">
          //             <span class="user-block-name">Hello, Mike</span>
          //             <span class="user-block-role">Designer</span>
          //          </div>
          //       </div>
          //    </div>
          // </li>';

            $html .= "<li class='nav-heading'><span >Menu Principal</span></li>";

            $html .= "<li {$active}>
               <a href='#{$nav['id']}' data-toggle='collapse' aria-expanded='true'>
                  <em class='{$nav['icon']}'></em>
                  <span>{$nav['label']}</span>
               </a>";

            $html .= "<ul id='{$nav['id']}' class='nav sidebar-subnav collapse in'>";
            foreach ($nav['pages'] as $page) {
                $isActive = $page['active'] == true ? "class='active'" : "";
                $html .= "<li {$isActive}><a title='{$page['label']}' href='".url()."{$page['uri']}'>{$page['label']}</a></li>";
            }
            $html .= "</ul>";
        }

        $html .= "</ul>";
        echo $html;
    }

    private function getPermissoes($modulo, $usuarioId)
    {
        $sql = "SELECT
                    DISTINCT mod_nome, ctr_id,ctr_nome, ctr_icone, ctr_ordem,rcs_descricao,rcs_nome,rcs_ordem
                FROM
                    seg_usuarios
                    INNER JOIN seg_perfis_usuarios ON pru_usr_id = usr_id
                    INNER JOIN seg_perfis ON prf_id = pru_prf_id
                    INNER JOIN seg_modulos ON mod_id = prf_mod_id
                    INNER JOIN seg_perfis_permissoes ON prp_prf_id = prf_id
                    INNER JOIN seg_permissoes ON prm_id = prp_prm_id
                    INNER JOIN seg_recursos ON rcs_id = prm_rcs_id
                    INNER JOIN seg_categorias_recursos ON ctr_id = rcs_ctr_id
                WHERE
                    mod_nome = :mod_nome AND usr_id = :usuario_id AND ctr_ativo = 1
                ORDER BY
                   ctr_ordem,rcs_ordem";

        return DB::select($sql, ['mod_nome' => $modulo, 'usuario_id' => $usuarioId]);
    }

    private function getNavigationArray($permissoes_cookie, $activeController = null)
    {
        $navigation = [];
        if (!empty($permissoes_cookie)) {
            foreach ($permissoes_cookie as $key => $permissao) {
                $navigation[$permissao->ctr_nome]['id'] = $permissao->ctr_id;
                $navigation[$permissao->ctr_nome]['label'] = $permissao->ctr_nome;
                $navigation[$permissao->ctr_nome]['uri']   = '#';
                $navigation[$permissao->ctr_nome]['icon']  = $permissao->ctr_icone;

                if ($activeController == strtolower($permissao->rcs_nome)) {
                    $navigation[$permissao->ctr_nome]['activeMenu'] = 'active';
                }

                $navigation[$permissao->ctr_nome]['pages'][$key] = [
                    'label'      => $permissao->rcs_descricao,
                    'uri'        => '/'.strtolower($permissao->mod_nome).'/'.strtolower($permissao->rcs_nome).'/index',
                    'controller' => strtolower($permissao->rcs_nome),
                    'action'     => 'index',
                    'active'     => (strtolower($permissao->rcs_nome) == $activeController),
                ];
            }
        }

        return $navigation;
    }
}
