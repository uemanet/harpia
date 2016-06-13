<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Recurso;

class RecursoTableSeeder extends Seeder
{

    public function run()
    {
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Modulos';
        $recurso->rcs_descricao = 'Recurso módulo da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-lock';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Perfis';
        $recurso->rcs_descricao = 'Recurso perfil da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-users';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 2;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Categorias Recursos';
        $recurso->rcs_descricao = 'Recurso categorias de recursos da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-cogs';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 3;
        $recurso->save();
    }
}
