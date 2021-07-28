<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;

class PermissoesAlunosSeeder extends Seeder
{
    public function run()
    {
        $modulo = Modulo::where('mod_slug','alunos')->first();
       $perfil = Perfil::create([
            'prf_mod_id' => $modulo->mod_id,
            'prf_nome' => 'Perfil de Aluno'
        ]);
        $arrPermissoes = [];

        // Criar permissao index do modulo Alunos (DASHBOARD)
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'alunos.index.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $perfil->permissoes()->attach($arrPermissoes);
        $perfil->usuarios()->attach(1);

    }
}