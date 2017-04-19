<?php

namespace Modulos\Seguranca\Providers\Seguranca;

use Illuminate\Contracts\Foundation\Application;
use Modulos\Seguranca\Providers\Seguranca\Contracts\Seguranca as SegurancaContract;
use Modulos\Seguranca\Providers\Seguranca\Exceptions\ForbiddenException;
use Cache;
use DB;

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
        $usrId = $this->getUser()->getAuthIdentifier();

        $arrayMenuCategorias = $this->makeMenuCategoriasModulos($usrId);

        $arrayMenu = [];
        if (!empty($arrayMenuCategorias)) {
            $arrayMenu = $this->makeMenuRecursos($usrId, $arrayMenuCategorias);
        }

        //Estrutura do menu em cache
        Cache::forever('MENU_'.$usrId, $arrayMenu);
    }

    public function makeCachePermissoes()
    {
        $user = $this->getUser();

        $permissions = DB::table('permissoes AS per')
            ->join('permissoes_has_perfis AS php', 'per.id', '=', 'php.permissoes_id')
            ->join('perfis AS perf', 'php.perfis_id', '=', 'perf.id')
            ->join('perfis_has_users AS phu', 'phu.perfis_id', '=', 'perf.id')
            ->where('phu.users_id', '=', $user->id)
            ->get();

        $permissions = $permissions->pluck('rota')->toArray();

        Cache::forever('PERMISSOES_'.$user->id, $permissions);
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
