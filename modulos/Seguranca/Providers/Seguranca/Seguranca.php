<?php

namespace Modulos\Seguranca\Providers\Seguranca;

use Harpia\Menu\MenuTree;
use Harpia\Tree\Node;
use Harpia\Menu\MenuItem as MenuNode;
use Illuminate\Contracts\Foundation\Application;
use Modulos\Seguranca\Models\MenuItem;
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
        $menuItemRepository = new MenuItemRepository(new MenuItem());
        $modulosRepository = new ModuloRepository();

        $user = $this->getUser();

        // busca os modulos no qual o usuario tem permissao
        $modulos = $modulosRepository->getByUser($user->usr_id, true);

        $menus = [];

        foreach ($modulos as $modulo) {
            $menu = new MenuTree();
            $menu->addValue(new Node($modulo->mod_nome, $modulo, false));

            $categorias = $menuItemRepository->getCategorias($modulo->mod_id);

            foreach ($categorias as $categoria) {
                $menu->addTree($this->makeCategoriaTree($modulo->mod_id, $categoria->mit_id));
            }

            $menus[$modulo->mod_slug] = $menu;
        }

        Cache::forever('MENU_'.$user->usr_id, $menus);
    }

    public function makeCategoriaTree($moduloId, $categoriaId)
    {
        $menuItemRepository = new MenuItemRepository(new MenuItem());
        $categoriaTree = new MenuTree();

        // Categoria eh a raiz da subarvore atual
        $categoria = $menuItemRepository->find($categoriaId);

        $categoriaTree->addValue(new MenuNode($categoria->mit_nome, $categoria, false));

        $itensFilhos = $menuItemRepository->getItensFilhos($moduloId, $categoriaId);

        foreach ($itensFilhos as $itensFilho) {

            // Se é uma subcategoria, monta recursivamente a arvore
            if ($menuItemRepository->isSubCategoria($itensFilho->mit_id)) {
                $tree = $this->makeCategoriaTree($moduloId, $itensFilho->mit_id);

                if ($tree->getRoot()->hasChildren()) {
                    $categoriaTree->addTree($tree);
                }
            }

            // Se for um item final, adiciona a arvore
            if ($menuItemRepository->isItem($itensFilho->mit_id) && $this->haspermission($itensFilho->mit_rota)) {
                $categoriaTree->addValue(new MenuNode($itensFilho->mit_nome, $itensFilho));
            }
        }

        return $categoriaTree;
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

        if (!env('IS_SECURITY_ENNABLED')) {
            $permissions = DB::table('seg_permissoes')->get();
        }

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
        if (!env('IS_SECURITY_ENNABLED')) {
            return true;
        }

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

        // verifica se a rota é async
        if (preg_match("/\basync\b/i", $rota)) {
            return true;
        }

        // Verifica na base de dados se o perfil do usuario tem acesso ao recurso
        $hasPermission = $this->verifyPermission($this->getUser()->usr_id, $rota);

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
