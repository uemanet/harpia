<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\CategoriaRecurso;

class CategoriaRecursoTableSeeder extends Seeder
{

    public function run()
    {
        $categoria = new CategoriaRecurso();
        $categoria->ctr_mod_id = 1; // Modulo Seguranca
        $categoria->ctr_nome = 'Segurança';
        $categoria->ctr_descricao = 'Categoria segurança do módulo segurança';
        $categoria->ctr_icone = 'fa fa-lock';
        $categoria->ctr_ordem = 1;
        $categoria->ctr_ativo = 1;
        $categoria->ctr_visivel = 1;
        $categoria->save();
    }
}
