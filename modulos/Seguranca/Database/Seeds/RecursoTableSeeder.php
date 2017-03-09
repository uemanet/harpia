<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Recurso;

class RecursoTableSeeder extends Seeder
{
    public function run()
    {
        $this->recursosModuloSeguranca();

        $this->recursosModuloGeral();

        $this->recursosModuloAcademico();

        $this->recursosModuloIntegracao();

        $this->recursosModuloMonitoramento();
    }

    private function recursosModuloSeguranca()
    {
        // Recurso Dashboard - id: 1
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Dashboard';
        $recurso->rcs_rota = 'index';
        $recurso->rcs_descricao = 'Recurso dashboard da categoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-tachometer';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        // Recurso Módulos - id: 2
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Modulos';
        $recurso->rcs_rota = 'modulos';
        $recurso->rcs_descricao = 'Recurso módulo da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-cubes';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 2;
        $recurso->save();

        // Recurso Categoria de Recursos - id: 3
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Categorias de Recursos';
        $recurso->rcs_rota = 'categoriasrecursos';
        $recurso->rcs_descricao = 'Recurso categorias de recursos da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-indent';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 3;
        $recurso->save();

        // Recurso 'Recursos' - id: 4
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Recursos';
        $recurso->rcs_rota = 'recursos';
        $recurso->rcs_descricao = 'Recurso recursos da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-puzzle-piece';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 4;
        $recurso->save();

        // Recurso 'Permissoes' - id: 5
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Permissões';
        $recurso->rcs_rota = 'permissoes';
        $recurso->rcs_descricao = 'Recurso permissões da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-unlock-alt';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 5;
        $recurso->save();

        // Recurso 'Perfis' - id: 6
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Perfis';
        $recurso->rcs_rota = 'perfis';
        $recurso->rcs_descricao = 'Recurso perfil da dategoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-user-secret';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 6;
        $recurso->save();

        // Recurso 'Usuários' - id: 7
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 1; // Categoria Seguranca
        $recurso->rcs_nome = 'Usuários';
        $recurso->rcs_rota = 'usuarios';
        $recurso->rcs_descricao = 'Recurso usuários da categoria segurança do módulo segurança';
        $recurso->rcs_icone = 'fa fa-users';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 7;
        $recurso->save();
    }

    private function recursosModuloGeral()
    {
        // Recurso Index - id: 8
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 2; // Categoria Cadastro - Modulo Geral
        $recurso->rcs_nome = 'Dashboard';
        $recurso->rcs_rota = 'index';
        $recurso->rcs_descricao = 'Recurso dashboard da categoria Cadastros do módulo Geral';
        $recurso->rcs_icone = 'fa fa-tachometer';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        // Recurso Pessoas - id: 9
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 2; // Categoria Cadastro - Modulo Geral
        $recurso->rcs_nome = 'Pessoas';
        $recurso->rcs_rota = 'pessoas';
        $recurso->rcs_descricao = 'Cadastros de Pessoas';
        $recurso->rcs_icone = 'fa fa-user';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        // Recurso Titulacoes - id: 10
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 2; // Categoria Cadastro - Modulo Geral
        $recurso->rcs_nome = 'Titulações';
        $recurso->rcs_rota = 'titulacoes';
        $recurso->rcs_descricao = 'Recurso titulacoes da categoria cadastro do módulo geral';
        $recurso->rcs_icone = 'fa fa-file-text-o';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 3;
        $recurso->save();

        //Recurso Titulacoes Informacoes - id: 11
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Oculto - Modulo Geral
        $recurso->rcs_nome = 'Titulações Informações';
        $recurso->rcs_rota = 'titulacoesinformacoes';
        $recurso->rcs_descricao = 'Recurso titulacoesinformacoes do módulo geral';
        $recurso->rcs_icone = 'fa fa-plus';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        // Recurso Documento - id = 12
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 3; // Categoria Oculto - Modulo Geral
        $recurso->rcs_nome = 'Documentos';
        $recurso->rcs_rota = 'documentos';
        $recurso->rcs_descricao = 'Recurso documentos do módulo geral';
        $recurso->rcs_icone = 'fa fa-address-card-o';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();
    }

    private function recursosModuloAcademico()
    {
        /** Categoria Cadastros */

        // Recurso Dashboard - id: 13
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 4; // Categoria Cadastros - Modulo Academico
        $recurso->rcs_nome = 'Dashboard';
        $recurso->rcs_rota = 'index';
        $recurso->rcs_descricao = 'Recurso dashboard do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-tachometer';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        // Recurso Polos - id: 14
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 4; // Categoria Cadastros - Modulo Acadêmico
        $recurso->rcs_nome = 'Polos';
        $recurso->rcs_rota = 'polos';
        $recurso->rcs_descricao = 'Recurso polos da categoria cadastro do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-ellipsis-h';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 2;
        $recurso->save();

        // Recurso Centros - id: 15
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 4; // Categoria Cadastros - Modulo Acadêmico
        $recurso->rcs_nome = 'Centros';
        $recurso->rcs_rota = 'centros';
        $recurso->rcs_descricao = 'Recurso centro do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-map-marker';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 3;
        $recurso->save();

        // Recurso Departamentos - id: 16
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 4; // Categoria Cadastros - Modulo Acadêmico
        $recurso->rcs_nome = 'Departamentos';
        $recurso->rcs_rota = 'departamentos';
        $recurso->rcs_descricao = 'Recurso departamento do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-sitemap';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 4;
        $recurso->save();

        // Recurso Disciplinas - id: 17
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 4; // Categoria Cadastros - Modulo Acadêmico
        $recurso->rcs_nome = 'Disciplinas';
        $recurso->rcs_rota = 'disciplinas';
        $recurso->rcs_descricao = 'Recurso disciplinas do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-book';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 5;
        $recurso->save();

        // Recurso Cursos - id: 18
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 4; // Categoria Cadastros - Modulo Acadêmico
        $recurso->rcs_nome = 'Cursos';
        $recurso->rcs_rota = 'cursos';
        $recurso->rcs_descricao = 'Recurso curso do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-graduation-cap';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 6;
        $recurso->save();

        // Recurso Periodos Letivos - id: 19
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 4; // Categoria Cadastros - Modulo Acadêmico
        $recurso->rcs_nome = 'Períodos Letivos';
        $recurso->rcs_rota = 'periodosletivos';
        $recurso->rcs_descricao = 'Recurso período letivo do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-calendar';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 7;
        $recurso->save();

        // Recurso Ofertas de Cursos - id: 20
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 4; // Categoria Cadastros - Modulo Acadêmico
        $recurso->rcs_nome = 'Ofertas de Cursos';
        $recurso->rcs_rota = 'ofertascursos';
        $recurso->rcs_descricao = 'Recurso ofertas de cursos do módulo acadêmico na categoria de processos';
        $recurso->rcs_icone = 'fa fa-plus';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 8;
        $recurso->save();

        // Recurso Alunos - id: 21
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 4; // Categoria Cadastros - Modulo Acadêmico
        $recurso->rcs_nome = 'Alunos';
        $recurso->rcs_rota = 'alunos';
        $recurso->rcs_descricao = 'Recurso alunos do módulo Acadêmico';
        $recurso->rcs_icone = 'fa fa-address-card-o';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 9;
        $recurso->save();

        // Recurso Professores - id: 22
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 4; // Categoria Cadastros - Modulo Acadêmico
        $recurso->rcs_nome = 'Professores';
        $recurso->rcs_rota = 'professores';
        $recurso->rcs_descricao = 'Recurso professores do módulo Acadêmico';
        $recurso->rcs_icone = 'fa fa-id-card-o';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 10;
        $recurso->save();

        // Recurso Tutores - id: 23
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 4; // Categoria Cadastros - Modulo Acadêmico
        $recurso->rcs_nome = 'Tutores';
        $recurso->rcs_rota = 'tutores';
        $recurso->rcs_descricao = 'Recurso tutores do módulo Acadêmico';
        $recurso->rcs_icone = 'fa fa-group';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 11;
        $recurso->save();

        /** Categoria Processos */

        // Recurso Vinculos - id: 24
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 4; // Categoria Academico  -> Processos
        $recurso->rcs_nome = 'Vínculos';
        $recurso->rcs_rota = 'usuarioscursos';
        $recurso->rcs_descricao = 'Recurso vincular usuário ao curso do módulo Segurança';
        $recurso->rcs_icone = 'fa fa-link';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        // Recurso Matricular Aluno No Curso - id: 25
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 5; // Categoria Processos do Módulo Acadêmico
        $recurso->rcs_nome = 'Matricular aluno no curso';
        $recurso->rcs_rota = 'matricularalunocurso';
        $recurso->rcs_descricao = 'Recurso Matricular aluno no curso do módulo Acadêmico';
        $recurso->rcs_icone = 'fa fa-university';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 2;
        $recurso->save();

        // Recurso Ofertar Disciplina - id: 26
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 5; // Categoria Processos do Módulo Acadêmico
        $recurso->rcs_nome = 'Ofertar Disciplina';
        $recurso->rcs_rota = 'ofertasdisciplinas';
        $recurso->rcs_descricao = 'Recurso Ofertar Disciplina';
        $recurso->rcs_icone = 'fa fa-clipboard';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 3;
        $recurso->save();

        // Recurso Matricular Aluno na Disciplina - id: 27
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 5; // Categoria Processos do Módulo Acadêmico
        $recurso->rcs_nome = 'Matricular Aluno na Disciplina';
        $recurso->rcs_rota = 'matricularalunodisciplina';
        $recurso->rcs_descricao = 'Recurso Matricular Aluno na Disciplina';
        $recurso->rcs_icone = 'fa fa-book';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 4;
        $recurso->save();

        // Recurso Matricular Aluno na Disciplina - id: 28
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 5; // Categoria Processos do Módulo Acadêmico
        $recurso->rcs_nome = 'Matriculas em Lote';
        $recurso->rcs_rota = 'matriculaslote';
        $recurso->rcs_descricao = 'Recurso Matriculas em Lote';
        $recurso->rcs_icone = 'fa fa-database';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 5;
        $recurso->save();

        // Recurso Lançamento de TCC - id: 29
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 5; // Categoria Processos do Módulo Acadêmico
        $recurso->rcs_nome = 'Lançamento de TCC';
        $recurso->rcs_rota = 'lancamentostccs';
        $recurso->rcs_descricao = 'Recurso lançamento de tcc do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-archive';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 6;
        $recurso->save();

        // Recurso Conclusão de Curso - id: 30
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 5; // Categoria Processos do Módulo Acadêmico
        $recurso->rcs_nome = 'Conclusão de Curso';
        $recurso->rcs_rota = 'conclusaocurso';
        $recurso->rcs_descricao = 'Recurso Conclusão de Curso do Módulo Acadêmico';
        $recurso->rcs_icone = 'fa fa-graduation-cap';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 7;
        $recurso->save();

        // Recurso Relatórios - id: 31
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 5; // Categoria Processos do Módulo Acadêmico
        $recurso->rcs_nome = 'Relatórios';
        $recurso->rcs_rota = 'relatorios';
        $recurso->rcs_descricao = 'Recurso de Geração de Relatórios do Módulo Acadêmico';
        $recurso->rcs_icone = 'fa fa-file-text-o';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 7;
        $recurso->save();

        /* Categoria Oculto */

        // Recurso Tutor do Grupo - id: 32
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 6; // Categoria Oculto - Módulo Acadêmico
        $recurso->rcs_nome = 'Tutor do Grupo';
        $recurso->rcs_rota = 'tutoresgrupos';
        $recurso->rcs_descricao = 'Recurso tutoresgrupos do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-plus';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        // Recurso Matrizes Curriculares - id: 33
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 6; // Categoria Oculto - Módulo Acadêmico
        $recurso->rcs_nome = 'Matrizes Curriculares';
        $recurso->rcs_rota = 'matrizescurriculares';
        $recurso->rcs_descricao = 'Recurso matriz curricular do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-table';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 5;
        $recurso->save();

        // Recurso Grupos - id: 34
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 6; // Categoria Oculto - Módulo Acadêmico
        $recurso->rcs_nome = 'Grupos';
        $recurso->rcs_rota = 'grupos';
        $recurso->rcs_descricao = 'Recurso grupo do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-group';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 8;
        $recurso->save();

        // Recurso Turmas - id: 35
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 6; // Categoria Oculto - Módulo Acadêmico
        $recurso->rcs_nome = 'Turmas';
        $recurso->rcs_rota = 'turmas';
        $recurso->rcs_descricao = 'Recurso turmas do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-plus';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 8;
        $recurso->save();

        // Recurso Módulos das Matrizes - id: 36
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 6; // Categoria Oculto - Módulo Acadêmico
        $recurso->rcs_nome = 'Módulos das Matrizes';
        $recurso->rcs_rota = 'modulosmatrizes';
        $recurso->rcs_descricao = 'Recurso Módulos Matriz do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-book';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 3;
        $recurso->save();

        // Recurso Relatório Matricula por Curso - id: 37
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 6; // Categoria Oculto - Módulo Acadêmico
        $recurso->rcs_nome = 'Relatório Matricula por Curso';
        $recurso->rcs_rota = 'relatoriosmatriculas';
        $recurso->rcs_descricao = 'Recurso Relatório Matricula do módulo acadêmico';
        $recurso->rcs_icone = 'fa fa-book';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 3;
        $recurso->save();
    }

    private function recursosModuloIntegracao()
    {
        // Recurso Dashboard - id: 38
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 7; // Categoria Cadastros - Módulo Integração
        $recurso->rcs_nome = 'Dashboard';
        $recurso->rcs_rota = 'index';
        $recurso->rcs_descricao = 'Recurso dashboard do módulo integração';
        $recurso->rcs_icone = 'fa fa-tachometer';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        // Recurso AmbientesVirtuais - id: 39
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 7; // Categoria Cadastros - Módulo Integracao
        $recurso->rcs_nome = 'Ambientes Virtuais';
        $recurso->rcs_rota = 'ambientesvirtuais';
        $recurso->rcs_descricao = 'Recurso ambientes virtuais do módulo integração';
        $recurso->rcs_icone = 'fa fa-laptop';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 2;
        $recurso->save();
    }

    private function recursosModuloMonitoramento()
    {
        // Recurso Dashboard - id: 40
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 8; // Categoria Monitoramento - Módulo Monitoramento
        $recurso->rcs_nome = 'Dashboard';
        $recurso->rcs_rota = 'index';
        $recurso->rcs_descricao = 'Recurso dashboard do módulo monitoramento';
        $recurso->rcs_icone = 'fa fa-tachometer';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();

        // Recurso Tempo Online - id: 41
        $recurso = new Recurso();
        $recurso->rcs_ctr_id = 8; // Categoria Monitoramento - Módulo Monitoramento
        $recurso->rcs_nome = 'Tempo Online';
        $recurso->rcs_rota = 'tempoonline';
        $recurso->rcs_descricao = 'Recurso tempo online do módulo monitoramento';
        $recurso->rcs_icone = 'fa fa-bar-chart';
        $recurso->rcs_ativo = 1;
        $recurso->rcs_ordem = 1;
        $recurso->save();
    }
}
