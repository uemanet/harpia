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

    }

    /** Permissões do Módulo Segurança */
    private function createPermissoesIndex()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 1;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Dashboard';
        $permissao->save();
    }

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
    }

    /** Permissões do Módulo Geral */

    private function createPermissoesGeralDashboard()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 8; // Recurso Dashboard
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Dashboard da Categoria Cadastros do módulo geral';
        $permissao->save();
    }

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
    }

    /** Permissões do Módulo Acadêmico */

    private function createPermissoesAcademicoIndex()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 10;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Dashboard';
        $permissao->save();
    }

    private function createPermissoesAcademicoPolo()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 11;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso polos da categoria cadastro do módulo acadêmico';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 11;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso polos da categoria cadastro do módulo acadêmico';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 11;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso polos da categoria cadastro do módulo acadêmico';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 11;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso polos da categoria cadastro do módulo acadêmico';
        $permissao->save();
    }

    private function createPermissoesAcademicoDepartamentos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 12;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Departamento';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 12;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Departamento';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 12;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Departamento';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 12;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Departamento';
        $permissao->save();
    }

    private function createPermissoesAcademicoPeriodosLetivos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 13;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Período Letivo';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 13;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Período Letivo';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 13;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Período Letivo';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 13;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Período Letivo';
        $permissao->save();
    }

    private function createPermissoesAcademicoCursos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 14;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Curso';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 14;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Curso';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 14;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Curso';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 14;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Curso';
        $permissao->save();
    }

    private function createPermissoesAcademicoCentros()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 15;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Centro';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 15;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Centro';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 15;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Centro';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 15;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Centro';
        $permissao->save();
    }

    private function createPermissoesAcademicoMatrizesCurriculares()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 16;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Matriz Curricular';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 16;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão index do recurso Matriz Curricular';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 16;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão index do recurso Matriz Curricular';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 16;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão index do recurso Matriz Curricular';
        $permissao->save();
    }

    private function createPermissoesAcademicoOfertasCursos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 17;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Ofertas de Cursos';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 17;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Ofertas de Cursos';
        $permissao->save();
    }

    private function createPermissoesAcademicoGrupos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 18;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Grupo';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 18;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Grupo';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 18;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Grupo';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 18;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Grupo';
        $permissao->save();
    }

    private function createPermissoesAcademicoTurmas()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 19;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Turmas';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 19;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Turmas';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 19;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Turmas';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 19;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Turmas';
        $permissao->save();
    }

    private function createPermissoesAcademicoModulosMatrizes()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 20;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Módulos Matrizes';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 20;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Módulos Matrizes';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 20;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Módulos Matrizes';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 20;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Módulos Matrizes';
        $permissao->save();
    }

    private function createPermissoesAcademicoDisciplinas()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 21;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Disciplinas';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 21;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Disciplinas';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 21;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Disciplinas';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 21;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Disciplinas';
        $permissao->save();
    }

    private function createPermissoesAcademicoVinculos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 22;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Vinculos';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 22;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Vinculos';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 22;
        $permissao->prm_nome = 'vinculos';
        $permissao->prm_descricao = 'Permissão vinculos do recurso Vinculos';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 22;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Vinculos';
        $permissao->save();
    }

    private function createPermissoesAcademicoTutoresGrupos()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 23;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Tutores do Grupo';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 23;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Tutores do Grupo';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 23;
        $permissao->prm_nome = 'alterartutor';
        $permissao->prm_descricao = 'Permissão alterartutor do recurso Tutores do Grupo';
        $permissao->save();
    }

    private function createPermissoesAcademicoTutores()
    {
        $permissao = new Permissao();
        $permissao->prm_rcs_id = 24;
        $permissao->prm_nome = 'index';
        $permissao->prm_descricao = 'Permissão index do recurso Tutores';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 24;
        $permissao->prm_nome = 'create';
        $permissao->prm_descricao = 'Permissão create do recurso Tutores';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 24;
        $permissao->prm_nome = 'edit';
        $permissao->prm_descricao = 'Permissão edit do recurso Tutores';
        $permissao->save();

        $permissao = new Permissao();
        $permissao->prm_rcs_id = 24;
        $permissao->prm_nome = 'delete';
        $permissao->prm_descricao = 'Permissão delete do recurso Tutores';
        $permissao->save();
    }
}
