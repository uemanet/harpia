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
        $recurso->rcs_nome = 'Dashboard';
        $recurso->rcs_rota = 'index';
        $recurso->rcs_descricao = 'Recurso dashboard da categoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-tachometer';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Modulos';
        $recurso->rcs_rota = 'modulos';
        $recurso->rcs_descricao = 'Recurso módulo da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-cubes';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 2;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Categorias de recursos';
        $recurso->rcs_rota = 'categoriasrecursos';
        $recurso->rcs_descricao = 'Recurso categorias de recursos da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-indent';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 3;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Recursos';
        $recurso->rcs_rota = 'recursos';
        $recurso->rcs_descricao = 'Recurso recursos da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-puzzle-piece';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 4;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Permissões';
        $recurso->rcs_rota = 'permissoes';
        $recurso->rcs_descricao = 'Recurso permissões da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-unlock-alt';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 5;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Perfis';
        $recurso->rcs_rota = 'perfis';
        $recurso->rcs_descricao = 'Recurso perfil da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-user-secret';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 6;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Usuários';
        $recurso->rcs_rota = 'usuarios';
        $recurso->rcs_descricao = 'Recurso usuários da categoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-users';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 7;
        $recurso->save();

        /* MODULO GERAL */

        // Recurso Index
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 2; // Categoria Seguranca
        $recurso->rcs_nome = 'Dashboard';
        $recurso->rcs_rota = 'index';
        $recurso->rcs_descricao = 'Recurso dashboard da categoria Cadastros do módulo Geral';
        $recurso->rcs_icone = 'fa fa-tachometer';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        // Recurso Pessoas
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 2; // Categoria Seguranca
        $recurso->rcs_nome = 'Pessoas';
        $recurso->rcs_rota = 'pessoas';
        $recurso->rcs_descricao = 'Cadastros de Pessoas';
        $recurso->rcs_icone = 'fa fa-user';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        // MODULO ACADEMICO

        // Recurso Dashboard
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Acadêmico
        $recurso->rcs_nome = 'Dashboard';
        $recurso->rcs_rota = 'index';
        $recurso->rcs_descricao = 'Recurso dashboard do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-tachometer';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        // Recurso Polos
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Acadêmico
        $recurso->rcs_nome = 'Polos';
        $recurso->rcs_rota = 'polos';
        $recurso->rcs_descricao = 'Recurso polos da categoria cadastro do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-ellipsis-h';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 2;
        $recurso->save();

        // Recurso Departamentos
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Acadêmico
        $recurso->rcs_nome = 'Departamentos';
        $recurso->rcs_rota = 'departamentos';
        $recurso->rcs_descricao = 'Recurso departamento do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-sitemap';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 4;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Acadêmico
        $recurso->rcs_nome = 'Períodos Letivos';
        $recurso->rcs_rota = 'periodosletivos';
        $recurso->rcs_descricao = 'Recurso período letivo do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-calendar';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 6;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Acadêmico
        $recurso->rcs_nome = 'Cursos';
        $recurso->rcs_rota = 'cursos';
        $recurso->rcs_descricao = 'Recurso curso do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-graduation-cap';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 5;
        $recurso->save();

        // Recursos Centros
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Acadêmico
        $recurso->rcs_nome = 'Centros';
        $recurso->rcs_rota = 'centros';
        $recurso->rcs_descricao = 'Recurso centro do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-map-marker';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 3;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 5; // Categoria Acadêmico -> Oculto
        $recurso->rcs_nome = 'Matrizes Curriculares';
        $recurso->rcs_rota = 'matrizescurriculares';
        $recurso->rcs_descricao = 'Recurso matriz curricular do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-table';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 5;
        $recurso->save();

        // Ofertas de cursos
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Acadêmico
        $recurso->rcs_nome = 'Ofertas de Cursos';
        $recurso->rcs_rota = 'ofertascursos';
        $recurso->rcs_descricao = 'Recurso ofertas de cursos do módulo acadêmico na categoria de processos';
        $recurso->rcs_icone = 'fa fa-plus';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 7;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 5; // Categoria Acadêmico ->Oculto
        $recurso->rcs_nome = 'Grupos';
        $recurso->rcs_rota = 'grupos';
        $recurso->rcs_descricao = 'Recurso grupo do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-group';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 8;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 5; // Categoria Acadêmico -> oculto
        $recurso->rcs_nome = 'Turmas';
        $recurso->rcs_rota = 'turmas';
        $recurso->rcs_descricao = 'Recurso turmas do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-plus';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 8;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 5; // Categoria Acadêmico -> Oculto
        $recurso->rcs_nome = 'Módulos das Matrizes';
        $recurso->rcs_rota = 'modulosmatrizes';
        $recurso->rcs_descricao = 'Recurso Módulos Matriz do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-book';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 3;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Acadêmico
        $recurso->rcs_nome = 'Disciplinas';
        $recurso->rcs_rota = 'disciplinas';
        $recurso->rcs_descricao = 'Recurso disciplinas do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-book';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 3;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 4; // Categoria Academico  -> Processos
        $recurso->rcs_nome = 'Vínculos';
        $recurso->rcs_rota = 'usuarioscursos';
        $recurso->rcs_descricao = 'Recurso vincular usuário ao curso do módulo Segurança';
        $recurso->rcs_icone = 'fa fa-link';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 5; // Categoria Acadêmico -> oculto
        $recurso->rcs_nome = 'Tutor do Grupo';
        $recurso->rcs_rota = 'tutoresgrupos';
        $recurso->rcs_descricao = 'Recurso tutoresgrupos do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-plus';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Acadêmico->Cadastros
        $recurso->rcs_nome = 'Alunos';
        $recurso->rcs_rota = 'alunos';
        $recurso->rcs_descricao = 'Recurso alunos do módulo Acadêmico';
        $recurso->rcs_icone = 'fa fa-address-card-o';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 9;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Cadastros
        $recurso->rcs_nome = 'Tutores';
        $recurso->rcs_rota = 'tutores';
        $recurso->rcs_descricao = 'Recurso tutores do módulo Acadêmico';
        $recurso->rcs_icone = 'fa fa-group';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 11;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Cadastros
        $recurso->rcs_nome = 'Professores';
        $recurso->rcs_rota = 'professores';
        $recurso->rcs_descricao = 'Recurso professores do módulo Acadêmico';
        $recurso->rcs_icone = 'fa fa-id-card-o';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 10;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 4; // Categoria Processos do Módulo Acadêmico
        $recurso->rcs_nome = 'Matricular aluno no curso';
        $recurso->rcs_rota = 'matricularalunocurso';
        $recurso->rcs_descricao = 'Recurso Matricular aluno no curso do módulo Acadêmico';
        $recurso->rcs_icone = 'fa fa-university';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 2;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 4; // Categoria Processos do Módulo Acadêmico
        $recurso->rcs_nome = 'Ofertar Disciplina';
        $recurso->rcs_rota = 'ofertasdisciplinas';
        $recurso->rcs_descricao = 'Recurso Ofertar Disciplina';
        $recurso->rcs_icone = 'fa fa-clipboard';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 3;
        $recurso->save();

        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 4; // Categoria Processos do Módulo Acadêmico
        $recurso->rcs_nome = 'Matricular Aluno na Disciplina';
        $recurso->rcs_rota = 'matricularalunodisciplina';
        $recurso->rcs_descricao = 'Recurso Matricular Aluno na Disciplina';
        $recurso->rcs_icone = 'fa fa-book';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 4;
        $recurso->save();

        // MODULO INTEGRAÇÃO
        // Recurso Dashboard
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 6; // Categoria Integracao
        $recurso->rcs_nome = 'Dashboard';
        $recurso->rcs_rota = 'index';
        $recurso->rcs_descricao = 'Recurso dashboard do módulo integração';
        $recurso->rcs_icone = 'fa fa-tachometer';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        // Recurso AmbientesVirtuais
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 6; // Categoria Integracao
        $recurso->rcs_nome = 'Ambientes Virtuais';
        $recurso->rcs_rota = 'ambientesvirtuais';
        $recurso->rcs_descricao = 'Recurso ambientes virtuais do módulo integração';
        $recurso->rcs_icone = 'fa fa-laptop';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 2;
        $recurso->save();

        // MODULO MONITORAMENTO
        // Recurso Dashboard
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 7; // Categoria Monitoramento
        $recurso->rcs_nome = 'Dashboard';
        $recurso->rcs_rota = 'index';
        $recurso->rcs_descricao = 'Recurso dashboard do módulo monitoramento';
        $recurso->rcs_icone = 'fa fa-tachometer';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        // MODULO MONITORAMENTO
        // Recurso Dashboard
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 7; // Categoria Monitoramento
        $recurso->rcs_nome = 'Tempo Online';
        $recurso->rcs_rota = 'tempoonline';
        $recurso->rcs_descricao = 'Recurso tempo online do módulo monitoramento';
        $recurso->rcs_icone = 'fa fa-bar-chart';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();
    }
}
