<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\CategoriaRecurso;

class CategoriaRecursoTableSeeder extends Seeder
{
    public function run()
    {
        $this->categoriasModuloSeguranca();

        $this->categoriasModuloGeral();

        $this->categoriasModuloAcademico();

        $this->categoriasModuloIntegracao();

        $this->categoriasModuloMonitoramento();
    }

    private function categoriasModuloSeguranca()
    {
        // Categoria Segurança - id: 1
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

    private function categoriasModuloGeral()
    {
        // Categoria Cadastro - id: 2
        $categoria = new CategoriaRecurso();
        $categoria->ctr_mod_id = 2; //Modulo Geral
        $categoria->ctr_nome = 'Cadastro';
        $categoria->ctr_descricao = 'Categoria de cadastro do módulo geral';
        $categoria->ctr_icone = 'fa fa-plus';
        $categoria->ctr_ordem = 1;
        $categoria->ctr_ativo = 1;
        $categoria->ctr_visivel = 1;
        $categoria->save();
    }

    private function categoriasModuloAcademico()
    {
        // Categoria Cadastros - id: 3
        $categoria = new CategoriaRecurso();
        $categoria->ctr_mod_id = 3; //Modulo Acadêmico
        $categoria->ctr_nome = 'Cadastros';
        $categoria->ctr_descricao = 'Categoria de cadastro do módulo acadêmica';
        $categoria->ctr_icone = 'fa fa-plus';
        $categoria->ctr_ordem = 1;
        $categoria->ctr_ativo = 1;
        $categoria->ctr_visivel = 1;
        $categoria->save();

        // Categoria Processos - id: 4
        $categoria = new CategoriaRecurso();
        $categoria->ctr_mod_id = 3; //Modulo Acadêmico
        $categoria->ctr_nome = 'Processos';
        $categoria->ctr_descricao = 'Categoria de processos do módulo acadêmico';
        $categoria->ctr_icone = 'fa fa-refresh';
        $categoria->ctr_ordem = 2;
        $categoria->ctr_ativo = 1;
        $categoria->ctr_visivel = 1;
        $categoria->save();

        // Categoria Oculto - id: 5
        $categoria = new CategoriaRecurso();
        $categoria->ctr_mod_id = 3; //Modulo Acadêmico
        $categoria->ctr_nome = 'Oculto';
        $categoria->ctr_descricao = 'Categoria oculta do módulo acadêmico';
        $categoria->ctr_icone = 'fa fa-cog';
        $categoria->ctr_ordem = 0;
        $categoria->ctr_ativo = 0;
        $categoria->ctr_visivel = 0;
        $categoria->save();
    }

    private function categoriasModuloIntegracao()
    {
        // Categoria Cadastros - id: 6
        $categoria = new CategoriaRecurso();
        $categoria->ctr_mod_id = 4; // Modulo Integração
        $categoria->ctr_nome = 'Cadastros';
        $categoria->ctr_descricao = 'Categoria de cadastro do módulo integração';
        $categoria->ctr_icone = 'fa fa-plus';
        $categoria->ctr_ordem = 1;
        $categoria->ctr_ativo = 1;
        $categoria->ctr_visivel = 1;
        $categoria->save();
    }

    private function categoriasModuloMonitoramento()
    {
        // Categoria Monitoramento - id: 7
        $categoria = new CategoriaRecurso();
        $categoria->ctr_mod_id = 5; // Modulo Integração
        $categoria->ctr_nome = 'Monitoramento';
        $categoria->ctr_descricao = 'Categoria de monitoramento do módulo de monitoramento';
        $categoria->ctr_icone = 'fa fa-plus';
        $categoria->ctr_ordem = 1;
        $categoria->ctr_ativo = 1;
        $categoria->ctr_visivel = 1;
        $categoria->save();
    }
}
