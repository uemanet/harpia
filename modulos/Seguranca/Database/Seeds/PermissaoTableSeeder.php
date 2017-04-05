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

        $this->createPermissoesGeralDocumentos();

        /** Permissoes do Módulo Acadêmico */

        $this->createPermissoesAcademicoIndex();

        $this->createPermissoesAcademicoPolo();

        $this->createPermissoesAcademicoCentros();

        $this->createPermissoesAcademicoDepartamentos();

        $this->createPermissoesAcademicoDisciplinas();

        $this->createPermissoesAcademicoCursos();

        $this->createPermissoesAcademicoPeriodosLetivos();

        $this->createPermissoesAcademicoOfertasCursos();

        $this->createPermissoesAcademicoAlunos();

        $this->createPermissoesAcademicoProfessores();

        $this->createPermissoesAcademicoTutores();

        $this->createPermissoesAcademicoVinculos();

        $this->createPermissoesMatricularAlunoCurso();

        $this->createPermissoesAcademicoOfertarDisciplina();

        $this->createPermissoesAcademicoMatricularAlunoDisciplina();

        $this->createPermissoesAcademicoMatriculasLote();

        $this->createPermissoesAcademicoLancamentoTcc();

        $this->createPermissoesAcademicoConclusaoCurso();

        $this->createPermissoesAcademicoTutoresGrupos();

        $this->createPermissoesAcademicoMatrizesCurriculares();

        $this->createPermissoesAcademicoGrupos();

        $this->createPermissoesAcademicoTurmas();

        $this->createPermissoesAcademicoModulosMatrizes();

        $this->createPermissoesAcademicoHistoricoParcial();


        $this->createPermissoesAcademicoHistoricoDefinitivo();

        $this->createPermissoesAcademicoRelatoriosMatriculasCurso();

        $this->createPermissoesAcademicoRelatoriosMatriculasDisciplina();

        $this->createPermissoesAcademicoCertificacao();

        $this->createPermissoesAcademicoControleRegistro();


        /** Permissões do Módulo Integração */

        $this->createPermissoesIntegracaoDashboard();

        $this->createPermissoesIntegracaoAmbientes();

        /** Permissões do Módulo de Monitoramento */

        $this->createPermissoesMonitoramentoDashboard();

        $this->createPermissoesMonitoramentoTempoOnline();
    }

    /** Permissões do Módulo Segurança */

    // 1 permissao
    private function createPermissoesIndex()
    {
        $permissao = new Permissao(); // id = 1
        $permissao->prm_rcs_id = 1;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Dashboard';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesModulo()
    {
        $permissao = new Permissao(); // id = 2
        $permissao->prm_rcs_id = 2;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso módulo da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 3
        $permissao->prm_rcs_id = 2;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso módulo da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 4
        $permissao->prm_rcs_id = 2;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso módulo da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 5
        $permissao->prm_rcs_id = 2;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso módulo da categoria segurança do módulo segurança';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesCategoriasRecursos()
    {
        $permissao = new Permissao(); // id = 6
        $permissao->prm_rcs_id = 3;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso categorias de recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 7
        $permissao->prm_rcs_id = 3;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso categorias de recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 8
        $permissao->prm_rcs_id = 3;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso categorias de recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 9
        $permissao->prm_rcs_id = 3;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso categorias de recursos da categoria segurança do módulo segurança';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesRecursos()
    {
        $permissao = new Permissao(); // id = 10
        $permissao->prm_rcs_id = 4;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 11
        $permissao->prm_rcs_id = 4;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 12
        $permissao->prm_rcs_id = 4;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso recursos da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 13
        $permissao->prm_rcs_id = 4;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso recursos da categoria segurança do módulo segurança';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesPermissoes()
    {
        $permissao = new Permissao(); // id = 14
        $permissao->prm_rcs_id = 5;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso permissoes da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 15
        $permissao->prm_rcs_id = 5;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso permissoes da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 16
        $permissao->prm_rcs_id = 5;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso permissoes da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 17
        $permissao->prm_rcs_id = 5;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso permissoes da categoria segurança do módulo segurança';
        $permissao->save();
    }

    // 5 permissoes
    private function createPermissoesPerfis()
    {
        $permissao = new Permissao(); // id = 18
        $permissao->prm_rcs_id = 6;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 19
        $permissao->prm_rcs_id = 6;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 20
        $permissao->prm_rcs_id = 6;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 21
        $permissao->prm_rcs_id = 6;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 22
        $permissao->prm_rcs_id = 6;
        $permissao->prm_nome = 'atribuirpermissoes';
        $permissao->prm_descricao = 'Permissão de atribuir permissoes ao perfis do recurso perfil da categoria segurança do módulo segurança';
        $permissao->save();
    }

    // 6 permissoes
    private function createPermissoesUsuarios()
    {
        $permissao = new Permissao(); // id = 23
        $permissao->prm_rcs_id = 7;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso usuario da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 24
        $permissao->prm_rcs_id = 7;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso usuario da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 25
        $permissao->prm_rcs_id = 7;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso usuario da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 26
        $permissao->prm_rcs_id = 7;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso usuario da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 27
        $permissao->prm_rcs_id = 7;
        $permissao->prm_nome = 'atribuirperfil';
        $permissao->prm_descricao = 'Permissão atribuirperfil do recurso usuario da categoria segurança do módulo segurança';
        $permissao->save();

        $permissao = new Permissao(); // id = 28
        $permissao->prm_rcs_id = 7;
        $permissao->prm_nome = 'deletarperfil';
        $permissao->prm_descricao = 'Permissão deletarperfil do recurso usuario da categoria segurança do módulo segurança';
        $permissao->save();
    }

    /** Permissões do Módulo Geral */

    // 1 permissao
    private function createPermissoesGeralDashboard()
    {
        $permissao = new Permissao(); // id = 29
        $permissao->prm_rcs_id = 8; // Recurso Dashboard
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Dashboard da Categoria Cadastros do módulo geral';
        $permissao->save();
    }

    // 5 permissoes
    private function createPermissoesGeralPessoas()
    {
        $permissao = new Permissao(); // id = 30
        $permissao->prm_rcs_id = 9; // Recurso Pessoas
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Pessoas da categoria Cadastros do módulo Geral';
        $permissao->save();

        $permissao = new Permissao(); // id = 31
        $permissao->prm_rcs_id = 9; // Recurso Pessoas
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Pessoas da categoria Cadastros do módulo Geral';
        $permissao->save();

        $permissao = new Permissao(); // id = 32
        $permissao->prm_rcs_id = 9; // Recurso Pessoas
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Pessoas da categoria Cadastros do módulo Geral';
        $permissao->save();

        $permissao = new Permissao(); // id = 33
        $permissao->prm_rcs_id = 9; // Recurso Pessoas
        $permissao->prm_nome = 'show';
        $permissao->prm_descricao = 'Permissão show do recurso Pessoas da categoria Cadastros do módulo Geral';
        $permissao->save();

        $permissao = new Permissao(); // id = 34
        $permissao->prm_rcs_id = 9; // Recurso Pessoas
        $permissao->prm_nome = 'verificapessoa';
        $permissao->prm_descricao = 'Permissão verificapessoa do recurso Pessoas da categoria Cadastros do módulo Geral';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesGeralTitulacoes()
    {
        $permissao = new Permissao(); // id = 35
        $permissao->prm_rcs_id = 10;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Titulações';
        $permissao->save();

        $permissao = new Permissao(); // id = 36
        $permissao->prm_rcs_id = 10;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Titulações';
        $permissao->save();

        $permissao = new Permissao(); // id = 37
        $permissao->prm_rcs_id = 10;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Titulações';
        $permissao->save();

        $permissao = new Permissao(); // id = 38
        $permissao->prm_rcs_id = 10;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Titulações';
        $permissao->save();
    }

    // 3 permissoes
    private function createPermissoesGeralTitulacoesInformacoes()
    {
        $permissao = new Permissao(); // id = 39
        $permissao->prm_rcs_id = 11;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Titulações Informações';
        $permissao->save();

        $permissao = new Permissao(); // id = 40
        $permissao->prm_rcs_id = 11;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Titulações Informações';
        $permissao->save();

        $permissao = new Permissao(); // id = 41
        $permissao->prm_rcs_id = 11;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Titulações Informações';
        $permissao->save();
    }

    // 4 permissooes
    private function createPermissoesGeralDocumentos()
    {
        $permissao = new Permissao(); // id = 42
        $permissao->prm_rcs_id = 12;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Documentos';
        $permissao->save();

        $permissao = new Permissao(); // id = 43
        $permissao->prm_rcs_id = 12;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Documentos';
        $permissao->save();

        $permissao = new Permissao(); // id = 44
        $permissao->prm_rcs_id = 12;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Documentos';
        $permissao->save();

        $permissao = new Permissao(); // id = 45
        $permissao->prm_rcs_id = 12;
        $permissao->prm_nome = 'anexo';
        $permissao->prm_descricao = 'Permissão anexo do recurso Documentos';
        $permissao->save();
    }

    /** Permissões do Módulo Acadêmico */

    // 1 permissao
    private function createPermissoesAcademicoIndex()
    {
        $permissao = new Permissao(); // id = 46
        $permissao->prm_rcs_id = 13;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Dashboard';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesAcademicoPolo()
    {
        $permissao = new Permissao(); // id = 47
        $permissao->prm_rcs_id = 14;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso polos da categoria cadastro do módulo acadêmico';
        $permissao->save();

        $permissao = new Permissao(); // id = 48
        $permissao->prm_rcs_id = 14;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso polos da categoria cadastro do módulo acadêmico';
        $permissao->save();

        $permissao = new Permissao(); // id = 49
        $permissao->prm_rcs_id = 14;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso polos da categoria cadastro do módulo acadêmico';
        $permissao->save();

        $permissao = new Permissao(); // id = 50
        $permissao->prm_rcs_id = 14;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso polos da categoria cadastro do módulo acadêmico';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesAcademicoCentros()
    {
        $permissao = new Permissao(); // id = 51
        $permissao->prm_rcs_id = 15;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Centro';
        $permissao->save();

        $permissao = new Permissao(); // id = 52
        $permissao->prm_rcs_id = 15;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Centro';
        $permissao->save();

        $permissao = new Permissao(); // id = 53
        $permissao->prm_rcs_id = 15;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Centro';
        $permissao->save();

        $permissao = new Permissao(); // id = 54
        $permissao->prm_rcs_id = 15;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Centro';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesAcademicoDepartamentos()
    {
        $permissao = new Permissao(); // id = 55
        $permissao->prm_rcs_id = 16;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Departamento';
        $permissao->save();

        $permissao = new Permissao(); // id = 56
        $permissao->prm_rcs_id = 16;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Departamento';
        $permissao->save();

        $permissao = new Permissao(); // id = 57
        $permissao->prm_rcs_id = 16;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Departamento';
        $permissao->save();

        $permissao = new Permissao(); // id = 58
        $permissao->prm_rcs_id = 16;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Departamento';
        $permissao->save();
    }

    // 4 permisssoes
    private function createPermissoesAcademicoDisciplinas()
    {
        $permissao = new Permissao(); // id = 59
        $permissao->prm_rcs_id = 17;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Disciplinas';
        $permissao->save();

        $permissao = new Permissao(); // id = 60
        $permissao->prm_rcs_id = 17;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Disciplinas';
        $permissao->save();

        $permissao = new Permissao(); // id = 61
        $permissao->prm_rcs_id = 17;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Disciplinas';
        $permissao->save();

        $permissao = new Permissao(); // id = 62
        $permissao->prm_rcs_id = 17;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Disciplinas';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesAcademicoCursos()
    {
        $permissao = new Permissao(); // id = 63
        $permissao->prm_rcs_id = 18;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Curso';
        $permissao->save();

        $permissao = new Permissao(); // id = 64
        $permissao->prm_rcs_id = 18;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Curso';
        $permissao->save();

        $permissao = new Permissao(); // id = 65
        $permissao->prm_rcs_id = 18;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Curso';
        $permissao->save();

        $permissao = new Permissao(); // id = 66
        $permissao->prm_rcs_id = 18;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Curso';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesAcademicoPeriodosLetivos()
    {
        $permissao = new Permissao(); // id = 67
        $permissao->prm_rcs_id = 19;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Período Letivo';
        $permissao->save();

        $permissao = new Permissao(); // id = 68
        $permissao->prm_rcs_id = 19;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Período Letivo';
        $permissao->save();

        $permissao = new Permissao(); // id = 69
        $permissao->prm_rcs_id = 19;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Período Letivo';
        $permissao->save();

        $permissao = new Permissao(); // id = 70
        $permissao->prm_rcs_id = 19;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Período Letivo';
        $permissao->save();
    }

    // 2 permissoes
    private function createPermissoesAcademicoOfertasCursos()
    {
        $permissao = new Permissao(); // id = 71
        $permissao->prm_rcs_id = 20;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Ofertas de Cursos';
        $permissao->save();

        $permissao = new Permissao(); // id = 72
        $permissao->prm_rcs_id = 20;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Ofertas de Cursos';
        $permissao->save();
    }

    // 4 permisssoes
    private function createPermissoesAcademicoAlunos()
    {
        $permissao = new Permissao(); // id = 73
        $permissao->prm_rcs_id = 21;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Alunos';
        $permissao->save();

        $permissao = new Permissao(); // id = 74
        $permissao->prm_rcs_id = 21;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Alunos';
        $permissao->save();

        $permissao = new Permissao(); // id = 75
        $permissao->prm_rcs_id = 21;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Alunos';
        $permissao->save();

        $permissao = new Permissao(); // id = 76
        $permissao->prm_rcs_id = 21;
        $permissao->prm_nome = 'show';
        $permissao->prm_descricao = 'Permissão delete do recurso Alunos';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesAcademicoProfessores()
    {
        $permissao = new Permissao(); // id = 77
        $permissao->prm_rcs_id = 22;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Professores';
        $permissao->save();

        $permissao = new Permissao(); // id = 78
        $permissao->prm_rcs_id = 22;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Professores';
        $permissao->save();

        $permissao = new Permissao(); // id = 79
        $permissao->prm_rcs_id = 22;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Professores';
        $permissao->save();

        $permissao = new Permissao(); // id = 80
        $permissao->prm_rcs_id = 22;
        $permissao->prm_nome = 'show';
        $permissao->prm_descricao = 'Permissão show do recurso Professores';
        $permissao->save();
    }

    // 4 permisssoes
    private function createPermissoesAcademicoTutores()
    {
        $permissao = new Permissao(); // id = 81
        $permissao->prm_rcs_id = 23;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Tutores';
        $permissao->save();

        $permissao = new Permissao(); // id = 82
        $permissao->prm_rcs_id = 23;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Tutores';
        $permissao->save();

        $permissao = new Permissao(); // id = 83
        $permissao->prm_rcs_id = 23;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Tutores';
        $permissao->save();

        $permissao = new Permissao(); // id = 84
        $permissao->prm_rcs_id = 23;
        $permissao->prm_nome = 'show';
        $permissao->prm_descricao = 'Permissão show do recurso Tutores';
        $permissao->save();
    }

    // 4 permisssoes
    private function createPermissoesAcademicoVinculos()
    {
        $permissao = new Permissao(); // id = 85
        $permissao->prm_rcs_id = 24;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Vinculos';
        $permissao->save();

        $permissao = new Permissao(); // id = 86
        $permissao->prm_rcs_id = 24;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Vinculos';
        $permissao->save();

        $permissao = new Permissao(); // id = 87
        $permissao->prm_rcs_id = 24;
        $permissao->prm_nome = 'vinculos';
        $permissao->prm_descricao = 'Permissão vinculos do recurso Vinculos';
        $permissao->save();

        $permissao = new Permissao(); // id = 88
        $permissao->prm_rcs_id = 24;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Vinculos';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesMatricularAlunoCurso()
    {
        $permissao = new Permissao(); // id = 89
        $permissao->prm_rcs_id = 25;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Matricular aluno no curso';
        $permissao->save();

        $permissao = new Permissao(); // id = 90
        $permissao->prm_rcs_id = 25;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Matricular aluno no curso';
        $permissao->save();

        $permissao = new Permissao(); // id = 91
        $permissao->prm_rcs_id = 25;
        $permissao->prm_nome = 'show';
        $permissao->prm_descricao = 'Permissão show do recurso Matricular aluno no curso';
        $permissao->save();

        $permissao = new Permissao(); // id = 92
        $permissao->prm_rcs_id = 25;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Matricular aluno no curso';
        $permissao->save();
    }

    // 2 permissoes
    private function createPermissoesAcademicoOfertarDisciplina()
    {
        $permissao = new Permissao(); // id = 93
        $permissao->prm_rcs_id = 26;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Ofertar Disciplina';
        $permissao->save();

        $permissao = new Permissao(); // id = 94
        $permissao->prm_rcs_id = 26;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Ofertar Disciplina';
        $permissao->save();
    }

    // 2 permissoes
    private function createPermissoesAcademicoMatricularAlunoDisciplina()
    {
        $permissao = new Permissao(); // id = 95
        $permissao->prm_rcs_id = 27;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Matricular Aluno na Disciplina';
        $permissao->save();

        $permissao = new Permissao(); // id = 96
        $permissao->prm_rcs_id = 27;
        $permissao->prm_nome = 'show';
        $permissao->prm_descricao = 'Permissão show do recurso Matricular Aluno na Disciplina';
        $permissao->save();
    }

    // 1 permissao
    private function createPermissoesAcademicoMatriculasLote()
    {
        $permissao = new Permissao(); // id = 97
        $permissao->prm_rcs_id = 28;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Matriculas em Lote';
        $permissao->save();
    }

    // 5 permissoes
    private function createPermissoesAcademicoLancamentoTcc()
    {
        $permissao = new Permissao(); // id = 98
        $permissao->prm_rcs_id = 29; // Recurso Lançamento de Tcc
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Lançamento de Tcc da Categoria de Processos do módulo de Acadêmico';
        $permissao->save();

        $permissao = new Permissao(); // id = 99
        $permissao->prm_rcs_id = 29; // Recurso Lançamento de Tcc
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Lançamento de Tcc da Categoria de Processos do módulo de Acadêmico';
        $permissao->save();

        $permissao = new Permissao(); // id = 100
        $permissao->prm_rcs_id = 29; // Recurso Lançamento de Tcc
        $permissao->prm_nome = 'alunosturma';
        $permissao->prm_descricao = 'Permissão alunosturma do recurso Lançamento de Tcc da Categoria de Processos do módulo de Acadêmico';
        $permissao->save();

        $permissao = new Permissao(); // id = 101
        $permissao->prm_rcs_id = 29; // Recurso Lançamento de Tcc
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Lançamento de Tcc da Categoria de Processos do módulo de Acadêmico';
        $permissao->save();

        $permissao = new Permissao(); // id = 102
        $permissao->prm_rcs_id = 29; // Recurso Lançamento de Tcc
        $permissao->prm_nome = 'anexo';
        $permissao->prm_descricao = 'Permissão anexo do recurso Lançamento de Tcc da Categoria de Processos do módulo de Acadêmico';
        $permissao->save();
    }

    // 2 permissoes
    private function createPermissoesAcademicoConclusaoCurso()
    {
        $permissao = new Permissao(); // id = 103
        $permissao->prm_rcs_id = 30; // Recurso Conclusão de Curso
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Conclusão de Curso da Categoria de Processos do módulo de Acadêmico';
        $permissao->save();

        $permissao = new Permissao(); // id = 104
        $permissao->prm_rcs_id = 30; // Recurso Conclusão de Curso
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Conclusão de Curso da Categoria de Processos do módulo de Acadêmico';
        $permissao->save();
    }

    // 3 permissoes
    private function createPermissoesAcademicoTutoresGrupos()
    {
        $permissao = new Permissao(); // id = 105
        $permissao->prm_rcs_id = 31;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Tutores do Grupo';
        $permissao->save();

        $permissao = new Permissao(); // id = 106
        $permissao->prm_rcs_id = 31;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Tutores do Grupo';
        $permissao->save();

        $permissao = new Permissao(); // id = 107
        $permissao->prm_rcs_id = 31;
        $permissao->prm_nome = 'alterartutor';
        $permissao->prm_descricao = 'Permissão alterartutor do recurso Tutores do Grupo';
        $permissao->save();
    }

    // 5 permissoes
    private function createPermissoesAcademicoMatrizesCurriculares()
    {
        $permissao = new Permissao(); // id = 108
        $permissao->prm_rcs_id = 32;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Matriz Curricular';
        $permissao->save();

        $permissao = new Permissao(); // id = 109
        $permissao->prm_rcs_id = 32;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão index do recurso Matriz Curricular';
        $permissao->save();

        $permissao = new Permissao(); // id = 110
        $permissao->prm_rcs_id = 32;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão index do recurso Matriz Curricular';
        $permissao->save();

        $permissao = new Permissao(); // id = 111
        $permissao->prm_rcs_id = 32;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão index do recurso Matriz Curricular';
        $permissao->save();

        $permissao = new Permissao(); // id = 112
        $permissao->prm_rcs_id = 32;
        $permissao->prm_nome = 'anexo';
        $permissao->prm_descricao = 'Permissão anexo do recurso Matriz Curricular';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesAcademicoGrupos()
    {
        $permissao = new Permissao(); // id = 113
        $permissao->prm_rcs_id = 33;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Grupo';
        $permissao->save();

        $permissao = new Permissao(); // id = 114
        $permissao->prm_rcs_id = 33;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Grupo';
        $permissao->save();

        $permissao = new Permissao(); // id = 115
        $permissao->prm_rcs_id = 33;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Grupo';
        $permissao->save();

        $permissao = new Permissao(); // id = 116
        $permissao->prm_rcs_id = 33;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Grupo';
        $permissao->save();
    }

    // 4 permissoes
    private function createPermissoesAcademicoTurmas()
    {
        $permissao = new Permissao(); // id = 117
        $permissao->prm_rcs_id = 34;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Turmas';
        $permissao->save();

        $permissao = new Permissao(); // id = 118
        $permissao->prm_rcs_id = 34;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Turmas';
        $permissao->save();

        $permissao = new Permissao(); // id = 119
        $permissao->prm_rcs_id = 34;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Turmas';
        $permissao->save();

        $permissao = new Permissao(); // id = 120
        $permissao->prm_rcs_id = 34;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Turmas';
        $permissao->save();
    }

    // 5 permissoes
    private function createPermissoesAcademicoModulosMatrizes()
    {
        $permissao = new Permissao(); // id = 121
        $permissao->prm_rcs_id = 35;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Módulos Matrizes';
        $permissao->save();

        $permissao = new Permissao(); // id = 122
        $permissao->prm_rcs_id = 35;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Módulos Matrizes';
        $permissao->save();

        $permissao = new Permissao(); // id = 123
        $permissao->prm_rcs_id = 35;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Módulos Matrizes';
        $permissao->save();

        $permissao = new Permissao(); // id = 124
        $permissao->prm_rcs_id = 35;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Módulos Matrizes';
        $permissao->save();

        $permissao = new Permissao(); // id = 125
        $permissao->prm_rcs_id = 35;
        $permissao->prm_nome = 'gerenciardisciplinas';
        $permissao->prm_descricao = 'Permissão gerenciar disciplinas do recurso Módulos Matrizes';
        $permissao->save();
    }

    // 3 permissoes
    private function createPermissoesAcademicoHistoricoParcial()
    {
        $permissao = new Permissao(); // id = 126
        $permissao->prm_rcs_id = 36;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Historico Parcial';
        $permissao->save();

        $permissao = new Permissao(); // id = 127
        $permissao->prm_rcs_id = 36;
        $permissao->prm_nome = 'show';
        $permissao->prm_descricao = 'Permissão show do recurso Historico Parcial';
        $permissao->save();

        $permissao = new Permissao(); // id = 128
        $permissao->prm_rcs_id = 36;
        $permissao->prm_nome = 'print';
        $permissao->prm_descricao = 'Permissão print do recurso Historico Parcial';
        $permissao->save();
    }

    // 2 permissoes
    private function createPermissoesAcademicoHistoricoDefinitivo()
    {
        $permissao = new Permissao(); // id = 129
        $permissao->prm_rcs_id = 37;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Historico Definitivo';
        $permissao->save();

        $permissao = new Permissao(); // id = 130
        $permissao->prm_rcs_id = 37;
        $permissao->prm_nome = 'print';
        $permissao->prm_descricao = 'Permissão print do recurso Historico Definitivo';
        $permissao->save();
    }

    // 2 permissao
    private function createPermissoesAcademicoRelatoriosMatriculasCurso()
    {
        $permissao = new Permissao(); // id = 131
        $permissao->prm_rcs_id = 38;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Relatórios Matriculas Curso';
        $permissao->save();

        $permissao = new Permissao(); // id = 132
        $permissao->prm_rcs_id = 38;
        $permissao->prm_nome = 'print';
        $permissao->prm_descricao = 'Permissão print do recurso Relatórios Matriculas Curso';
        $permissao->save();
    }

    // 2 permissoes
    private function createPermissoesAcademicoRelatoriosMatriculasDisciplina()
    {
        $permissao = new Permissao(); // id = 133
        $permissao->prm_rcs_id = 39;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Relatórios Matriculas Disciplina';
        $permissao->save();


        $permissao = new Permissao(); // id = 134
        $permissao->prm_rcs_id = 39;
        $permissao->prm_nome = 'pdf';
        $permissao->prm_descricao = 'Permissão pdf do recurso Relatórios Matriculas Disciplina';
        $permissao->save();
    }

    private function createPermissoesAcademicoCertificacao()
    {
        $permissao = new Permissao(); // id = 135
        $permissao->prm_rcs_id = 40;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Certificação';
        $permissao->save();
    }

    private function createPermissoesAcademicoControleRegistro()
    {
        $permissao = new Permissao(); // id = 136
        $permissao->prm_rcs_id = 41;
        $permissao->prm_nome = 'controleregistro';
        $permissao->prm_descricao = 'Permissão index do recurso Controle de Registro';
        $permissao->save();
    }

    /** Permissões do Módulo Integração */

    // 1 permissao
    private function createPermissoesIntegracaoDashboard()
    {
        $permissao = new Permissao(); // id = 137
        $permissao->prm_rcs_id = 42; // Recurso Dashboard
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Dashboard da Categoria Cadastros do módulo geral';
        $permissao->save();
    }

    // 6 permissoes
    private function createPermissoesIntegracaoAmbientes()
    {
        $permissao = new Permissao(); // id = 138
        $permissao->prm_rcs_id = 43;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Ambientes';
        $permissao->save();

        $permissao = new Permissao(); // id = 139
        $permissao->prm_rcs_id = 43;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Ambientes';
        $permissao->save();

        $permissao = new Permissao(); // id = 140
        $permissao->prm_rcs_id = 43;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Ambientes';
        $permissao->save();

        $permissao = new Permissao(); // id = 141
        $permissao->prm_rcs_id = 43;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Ambientes';
        $permissao->save();

        $permissao = new Permissao(); // id = 142
        $permissao->prm_rcs_id = 43;
        $permissao->prm_nome = 'adicionarservico';
        $permissao->prm_descricao = 'Permissão de adicionar serviços do recurso Ambientes';
        $permissao->save();

        $permissao = new Permissao(); // id = 143
        $permissao->prm_rcs_id = 43;
        $permissao->prm_nome = 'deletarservico';
        $permissao->prm_descricao = 'Permissão de deletar serviços do recurso Ambientes';
        $permissao->save();

        $permissao = new Permissao(); // id = 144
        $permissao->prm_rcs_id = 43;
        $permissao->prm_nome = 'adicionarturma';
        $permissao->prm_descricao = 'Permissão turmas do recurso Ambientes';
        $permissao->save();
    }

    /** Permissões do Módulo Monitoramento */

    // 1 permissao
    private function createPermissoesMonitoramentoDashboard()
    {
        $permissao = new Permissao(); // id = 145
        $permissao->prm_rcs_id = 44; // Recurso Dashboard
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Dashboard da Categoria de Monitoramento do módulo de monitoramento';
        $permissao->save();
    }

    // 2 permissoes
    private function createPermissoesMonitoramentoTempoOnline()
    {
        $permissao = new Permissao(); // id = 146
        $permissao->prm_rcs_id = 45; // Recurso Tempo Online
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Tempo Online da Categoria de Monitoramento do módulo de monitoramento';
        $permissao->save();

        $permissao = new Permissao(); // id = 147
        $permissao->prm_rcs_id = 45; // Recurso Tempo Online
        $permissao->prm_nome = 'monitorar';
        $permissao->prm_descricao = 'Permissão monitorar do recurso Tempo Online da Categoria de Monitoramento do módulo de monitoramento';
        $permissao->save();
    }
}
