<?php

namespace Modulos\Seguranca\Providers\Seguranca;

use Illuminate\Contracts\Foundation\Application;
use Modulos\Seguranca\Providers\Seguranca\Contracts\Seguranca as SegurancaContract;
use Modulos\Seguranca\Providers\Seguranca\Exceptions\ForbiddenException;
use Cache;
use DB;
use Modulos\Seguranca\Repositories\MenuItemRepository;
use Modulos\Seguranca\Repositories\ModuloRepository;

class Seguranca implements SegurancaContract
{
    /**
     * The Laravel Application.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Retorna o usuário logado na aplicação
     */
    public function getUser()
    {
        return $this->app['auth']->user();
    }

    /**
     * Monta em cache o menu, com categorias e recursos por categoria, de acordo com as permissoes do usuario
     */
    public function makeCacheMenu()
    {
        $menuItemRepository = new MenuItemRepository();
        $modulosRepository = new ModuloRepository();

        $user = $this->getUser();

        // busca os modulos no qual o usuario tem permissao
        $modulos = $modulosRepository->getByUser($user->usr_id);

        $menus = [];

        foreach ($modulos as $modulo) {
            $tree = new \stdClass();
            $tree->categorias = [];

            $categorias = $menuItemRepository->getCategorias($modulo->mod_id);

            foreach ($categorias as $categoria) {
                // busca as subcategorias
                $subcategorias = $menuItemRepository->getItensFilhos($modulo->mod_id, $categoria->mit_id);

                for ($i = 0; $i < $subcategorias->count(); $i++) {
                    if ($subcategorias[$i]->mit_rota && !$this->haspermission($subcategorias[$i]->mit_rota)) {
                        unset($subcategorias[$i]);
                        continue;
                    }

                    // caso o item seja uma subcategoria, busca os filhos
                    if (!$subcategorias[$i]->mit_rota) {
                        // busca os items da subcategoria
                        $itens = $menuItemRepository->getItensFilhos($modulo->mod_id, $subcategorias[$i]->mit_id);

                        for ($j = 0; $j < $itens->count(); $j++) {
                            if (!$this->haspermission($itens[$j]->mit_rota)) {
                                unset($itens[$j]);
                            }
                        }

                        if ($itens->count()) {
                            $subcategorias[$i]->itens = $itens;
                            continue;
                        }

                        unset($subcategorias[$i]);
                    }
                }

                if ($subcategorias->count()) {
                    $categoria->subcategorias = $subcategorias;
                    $tree->categorias[] = $categoria;
                }
            }

            $menus[$modulo->mod_slug] = $tree;
        }

        Cache::forever('MENU_'.$user->usr_id, $menus);
    }


    public function makeCachePermissoes()
    {
        $user = $this->getUser();

        $permissions = DB::table('seg_permissoes')
            ->join('seg_permissoes_perfis', 'prm_id', '=', 'prp_prm_id')
            ->join('seg_perfis', 'prp_prf_id', '=', 'prf_id')
            ->join('seg_perfis_usuarios', 'pru_prf_id', '=', 'prf_id')
            ->where('pru_usr_id', '=', $user->usr_id)
            ->get();

        $permissions = $permissions->pluck('prm_rota')->toArray();

        Cache::forever('PERMISSOES_'.$user->usr_id, $permissions);
    }

    /**
     * Verifica se o usuário tem permissao de acesso à uma determinada rota
     *
     * @param string $rota
     * @return bool
     * @throws ForbiddenException
     */
    public function haspermission($rota)
    {
        // O usuario nao esta logado, porem a rota eh liberada para usuarios guest.
        if (is_null($this->getUser())) {
            if ($this->isPreLoginOpenRoutes($rota)) {
                return true;
            }

            return false;
        }

        // Verifica se a rota eh liberada pas usuarios logados.
        if ($this->isPostLoginOpenRoutes($rota)) {
            return true;
        }

        // Verifica na base de dados se o perfil do usuario tem acesso ao recurso
        $hasPermission = $this->verifyPermission($this->getUser()->getAuthIdentifier(), $rota);

        if ($hasPermission) {
            return true;
        }

        return false;
    }

    /**
     * Verifica se a rota eh liberada para usuarios que nao estao logados no sistema
     *
     * @param $rota
     * @return bool
     */
    private function isPreLoginOpenRoutes($rota)
    {
        $openRoutes = $this->app['config']->get('seguranca.prelogin_openroutes', []);

        return in_array($rota, $openRoutes);
    }

    /**
     * Verifica se a rota eh liberada para usuarios que estao logados no sistema
     *
     * @param $rota
     * @return bool
     */
    private function isPostLoginOpenRoutes($rota)
    {
        $openRoutes = $this->app['config']->get('seguranca.postlogin_openroutes', []);

        return in_array($rota, $openRoutes);
    }

    /**
     * Funcao que verifica no cache se o usuario tem permissao para a rota
     *
     * @param $usr_id
     * @param $rota
     * @return bool
     */
    private function verifyPermission($usr_id, $rota)
    {
        $permissoes = Cache::get('PERMISSOES_'.$usr_id);

        return in_array($rota, $permissoes);
    }
}
