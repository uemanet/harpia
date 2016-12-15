<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Permissao;

class PermissaoTableSeeder extends Seeder
{
    public function run()
    {
        /** Permisssoes do Módulo Segurança */

        $this->createPermissoesIndex();

        $this->createPermissoesModulo();

        $this->createPermissoesCategoriasRecursos();

        $this->createPermissoesRecursos();

        $this->createPermissoesPermissoes();

        $this->createPermissoesPerfis();

        $this->createPermissoesUsuarios();

        /** Permissoes do Módulo Geral */

        $this->createPermissoesGeralDashboard();

        $this->createPermissoesGeralPessoas();

        $this->createPermissoesGeralTitulacoes();

        $this->createPermissoesGeralTitulacoesInformacoes();

        /** Permissoes do Módulo Acadêmico */

        $this->createPermissoesAcademicoIndex();

        $this->createPermissoesAcademicoPolo();

        $this->createPermissoesAcademicoDepartamentos();

        $this->createPermissoesAcademicoPeriodosLetivos();

        $this->createPermissoesAcademicoCursos();

        $this->createPermissoesAcademicoCentros();

        $this->createPermissoesAcademicoMatrizesCurriculares();

        $this->createPermissoesAcademicoOfertasCursos();

        $this->createPermissoesAcademicoGrupos();

        $this->createPermissoesAcademicoTurmas();

        $this->createPermissoesAcademicoModulosMatrizes();

        $this->createPermissoesAcademicoDisciplinas();

        $this->createPermissoesAcademicoVinculos();

        $this->createPermissoesAcademicoTutoresGrupos();

        $this->createPermissoesAcademicoTutores();

        $this->createPermissoesAcademicoAlunos();

        $this->createPermissoesAcademicoProfessores();

        $this->createPermissoesMatricularAlunoCurso();

        $this->createPermissoesAcademicoOfertarDisciplina();

        $this->createPermissoesAcademicoMatricularAlunoDisciplina();

        /** Permissões do Módulo Integração */

        $this->createPermissoesIntegracaoDashboard();

        $this->createPermissoesIntegracaoAmbientes();

        /** Permissões do Módulo de Monitoramento */

        $this->createPermissoesMonitoramentoDashboard();

        $this->createPermissoesMonitoramentoTempoOnline();

        $this->createPermissoesGeralDocumentos();
    }

    /** Permissões do Módulo Segurança */

    // 1 permissao
    private function createPermissoesIndex()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 1;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Dashboard';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesModulo()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 2;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso módulo da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 2;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso módulo da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 2;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso módulo da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 2;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso módulo da categoria segurança do módulo segurança';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesCategoriasRecursos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 3;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso categorias de recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 3;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso categorias de recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 3;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso categorias de recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 3;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso categorias de recursos da categoria segurança do módulo segurança';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesRecursos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 4;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 4;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 4;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 4;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso recursos da categoria segurança do módulo segurança';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesPermissoes()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 5;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso permissoes da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 5;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso permissoes da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 5;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso permissoes da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 5;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso permissoes da categoria segurança do módulo segurança';
        $permissao->save();
    }

    // 5 permissoes
    private function createPermissoesPerfis()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 6;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 6;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 6;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 6;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 6;
        $permissao->prm_nome = 'atribuirpermissoes';
        $permissao->prm_descricao = 'Permissão de atribuir permissoes ao perfis do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();
    }

    // 6 permissoes
    private function createPermissoesUsuarios()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 7;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso usuario da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 7;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso usuario da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 7;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso usuario da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 7;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso usuario da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 7;
        $permissao->prm_nome = 'atribuirperfil';
        $permissao->prm_descricao = 'Permissão atribuirperfil do recurso usuario da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 7;
        $permissao->prm_nome = 'deletarperfil';
        $permissao->prm_descricao = 'Permissão deletarperfil do recurso usuario da categoria segurança do módulo segurança';
        $permissao->save();
    }

    /** Permissões do Módulo Geral */

    // 1 permissao
    private function createPermissoesGeralDashboard()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 8; // Recurso Dashboard
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Dashboard da Categoria Cadastros do módulo geral';
        $permissao->save();
    }

    // 5 permissoes
    private function createPermissoesGeralPessoas()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 9; // Recurso Pessoas
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Pessoas da categoria Cadastros do módulo Geral';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 9; // Recurso Pessoas
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Pessoas da categoria Cadastros do módulo Geral';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 9; // Recurso Pessoas
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Pessoas da categoria Cadastros do módulo Geral';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 9; // Recurso Pessoas
        $permissao->prm_nome = 'show';
        $permissao->prm_descricao = 'Permissão show do recurso Pessoas da categoria Cadastros do módulo Geral';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 9; // Recurso Pessoas
        $permissao->prm_nome = 'verificapessoa';
        $permissao->prm_descricao = 'Permissão verificapessoa do recurso Pessoas da categoria Cadastros do módulo Geral';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesGeralTitulacoes()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 10;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Titulações';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 10;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Titulações';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 10;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Titulações';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 10;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Titulações';
        $permissao->save();
    }

    // 3 permissoes
    private function createPermissoesGeralTitulacoesInformacoes()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 11;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Titulações Informações';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 11;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Titulações Informações';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 11;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Titulações Informações';
        $permissao->save();
    }

    /** Permissões do Módulo Acadêmico */

    // 1 permissao
    private function createPermissoesAcademicoIndex()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 12;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Dashboard';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesAcademicoPolo()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 13;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso polos da categoria cadastro do módulo acadêmico';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 13;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso polos da categoria cadastro do módulo acadêmico';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 13;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso polos da categoria cadastro do módulo acadêmico';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 13;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso polos da categoria cadastro do módulo acadêmico';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesAcademicoDepartamentos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 14;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Departamento';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 14;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Departamento';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 14;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Departamento';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 14;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Departamento';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesAcademicoPeriodosLetivos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 15;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Período Letivo';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 15;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Período Letivo';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 15;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Período Letivo';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 15;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Período Letivo';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesAcademicoCursos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 16;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Curso';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 16;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Curso';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 16;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Curso';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 16;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Curso';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesAcademicoCentros()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 17;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Centro';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 17;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Centro';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 17;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Centro';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 17;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Centro';
        $permissao->save();
    }

    // 5 permissoes
    private function createPermissoesAcademicoMatrizesCurriculares()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 18;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Matriz Curricular';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 18;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão index do recurso Matriz Curricular';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 18;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão index do recurso Matriz Curricular';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 18;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão index do recurso Matriz Curricular';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 18;
        $permissao->prm_nome = 'anexo';
        $permissao->prm_descricao = 'Permissão anexo do recurso Matriz Curricular';
        $permissao->save();
    }

    // 2 permissoes
    private function createPermissoesAcademicoOfertasCursos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 19;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Ofertas de Cursos';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 19;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Ofertas de Cursos';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesAcademicoGrupos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 20;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Grupo';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 20;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Grupo';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 20;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Grupo';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 20;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Grupo';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesAcademicoTurmas()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 21;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Turmas';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 21;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Turmas';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 21;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Turmas';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 21;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Turmas';
        $permissao->save();
    }

    // 5 permissoes
    private function createPermissoesAcademicoModulosMatrizes()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 22;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Módulos Matrizes';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 22;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Módulos Matrizes';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 22;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Módulos Matrizes';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 22;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Módulos Matrizes';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 22;
        $permissao->prm_nome = 'gerenciardisciplinas';
        $permissao->prm_descricao = 'Permissão gerenciar disciplinas do recurso Módulos Matrizes';
        $permissao->save();
    }

    // 4 permisssoes
    private function createPermissoesAcademicoDisciplinas()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 23;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Disciplinas';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 23;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Disciplinas';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 23;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Disciplinas';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 23;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Disciplinas';
        $permissao->save();
    }

    // 4 permisssoes
    private function createPermissoesAcademicoVinculos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 24;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Vinculos';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 24;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Vinculos';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 24;
        $permissao->prm_nome = 'vinculos';
        $permissao->prm_descricao = 'Permissão vinculos do recurso Vinculos';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 24;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Vinculos';
        $permissao->save();
    }

    // 3 permissoes
    private function createPermissoesAcademicoTutoresGrupos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 25;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Tutores do Grupo';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 25;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Tutores do Grupo';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 25;
        $permissao->prm_nome = 'alterartutor';
        $permissao->prm_descricao = 'Permissão alterartutor do recurso Tutores do Grupo';
        $permissao->save();
    }

    // 4 permisssoes
    private function createPermissoesAcademicoAlunos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 26;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Alunos';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 26;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Alunos';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 26;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Alunos';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 26;
        $permissao->prm_nome = 'show';
        $permissao->prm_descricao = 'Permissão delete do recurso Alunos';
        $permissao->save();
    }

    // 4 permisssoes
    private function createPermissoesAcademicoTutores()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 27;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Tutores';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 27;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Tutores';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 27;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Tutores';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 27;
        $permissao->prm_nome = 'show';
        $permissao->prm_descricao = 'Permissão show do recurso Tutores';
        $permissao->save();
    }
    // 4 permissoes
    private function createPermissoesAcademicoProfessores()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 28;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Professores';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 28;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Professores';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 28;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Professores';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 28;
        $permissao->prm_nome = 'show';
        $permissao->prm_descricao = 'Permissão show do recurso Professores';
        $permissao->save();
    }

    // 3 permissoes
    private function createPermissoesMatricularAlunoCurso()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 29;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Matricular aluno no curso';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 29;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Matricular aluno no curso';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 29;
        $permissao->prm_nome = 'show';
        $permissao->prm_descricao = 'Permissão show do recurso Matricular aluno no curso';
        $permissao->save();
    }

    // 2 permissoes
    private function createPermissoesAcademicoOfertarDisciplina()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 30;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Ofertar Disciplina';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 30;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Ofertar Disciplina';
        $permissao->save();
    }

    private function createPermissoesAcademicoMatricularAlunoDisciplina()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 31;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Matricular Aluno na Disciplina';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 31;
        $permissao->prm_nome = 'show';
        $permissao->prm_descricao = 'Permissão show do recurso Matricular Aluno na Disciplina';
        $permissao->save();
    }

    /** Permissões do Módulo Integração */

    // 1 permissao
    private function createPermissoesIntegracaoDashboard()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 32; // Recurso Dashboard
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Dashboard da Categoria Cadastros do módulo geral';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesIntegracaoAmbientes()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 33;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Ambientes';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 33;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Ambientes';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 33;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Ambientes';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 33;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Ambientes';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 33;
        $permissao->prm_nome = 'adicionarservico';
        $permissao->prm_descricao = 'Permissão serviços do recurso Ambientes';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 33;
        $permissao->prm_nome = 'adicionarturma';
        $permissao->prm_descricao = 'Permissão turmas do recurso Ambientes';
        $permissao->save();
    }

    /** Permissões do Módulo Integração */

    // 1 permissao
    private function createPermissoesMonitoramentoDashboard()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 34; // Recurso Dashboard
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Dashboard da Categoria de Monitoramento do módulo de monitoramento';
        $permissao->save();
    }

    private function createPermissoesMonitoramentoTempoOnline()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 35; // Recurso Tempo Online
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Tempo Online da Categoria de Monitoramento do módulo de monitoramento';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 35; // Recurso Tempo Online
        $permissao->prm_nome = 'monitorar';
        $permissao->prm_descricao = 'Permissão monitorar do recurso Tempo Online da Categoria de Monitoramento do módulo de monitoramento';
        $permissao->save();
    }

    private function createPermissoesGeralDocumentos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 36;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Documentos';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 36;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Documentos';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 36;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Documentos';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 36;
        $permissao->prm_nome = 'anexo';
        $permissao->prm_descricao = 'Permissão anexo do recurso Documentos';
        $permissao->save();
    }
}
