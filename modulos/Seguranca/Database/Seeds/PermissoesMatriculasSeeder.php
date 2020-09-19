<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class PermissoesMatriculasSeeder extends Seeder
{
    public function run()
    {

        $modulo = Modulo::where('mod_slug','matriculas')->first();

        // Cria perfil de Administrador
        $perfil = Perfil::create([
            'prf_mod_id' => $modulo->mod_id,
            'prf_nome' => 'Administrador Matriculas'
        ]);
        $arrPermissoes = [];


        // Criar permissao index do modulo Matriculas (DASHBOARD)
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'matriculas.index.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        // Atirbuir permissao index ao perfil de Administrador
        $perfil->permissoes()->attach($arrPermissoes);

        // Atribuir perfil de Administrador ao usuario criado
        $perfil->usuarios()->attach(1);

    }
}
