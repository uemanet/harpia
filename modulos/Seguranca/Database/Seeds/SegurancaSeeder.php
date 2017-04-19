<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class SegurancaSeeder extends Seeder
{
    public function run()
    {
        $this->call(UsuarioTableSeeder::class);


        // Criar o modulo Seguranca
        $modulo = Modulo::create([
            'mod_nome' => 'Acadêmico',
            'mod_slug' => 'academico',
            'mod_icone' => 'fa fa-lock',
            'mod_classes' => 'bg-green'
        ]);

        // Cria perfil de Administrador
        $perfil = Perfil::create([
            'prf_mod_id' => $modulo->mod_id,
            'prf_nome' => 'Administrador'
        ]);

        // Criar permissao index do modulo Seguranca
        $permissaoIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.index.index'
        ]);

        $permissaoModulosIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.index.index'
        ]);

        $permissaoPolosIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.polos.index'
        ]);

        $permissaoCentrosIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.centros.index'
        ]);

        $permissaoDepartamentosIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.departamentos.index'
        ]);

        $permissaoAlunosIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.alunos.index'
        ]);

        $permissaoProfessoresIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.professores.index'
        ]);

        $permissaoTutoresIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.tutores.index'
        ]);

        $permissaoVinculosIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.vinculos.index'
        ]);

        $permissaoCursosIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.cursos.index'
        ]);

        $permissaoDisciplinasIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.disciplinas.index'
        ]);

        $permissaoPeriodosLetivosIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.periodosletivos.index'
        ]);

        $permissaoOfertasCursoIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.ofertascursos.index'
        ]);

        $permissaoOfertasDisciplinaIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.ofertasdisciplinas.index'
        ]);

        $permissaoAlunosCursosIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.matricularalunocurso.index'
        ]);

        $permissaoAlunosDisciplinasIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.matriculasofertasdisciplinas.index'
        ]);

        $permissaoDisciplinasLotesIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.matriculaslote.index'
        ]);

        $permissaoLancamentoTccIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.lancamentostccs.index'
        ]);

        $permissaoConclusaoCursoIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.conclusaocurso.index'
        ]);

        $permissaoHistoricoParcialIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.historicoparcial.index'
        ]);

        $permissaoHistoricoDefinitivoIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.historicodefinitivo.index'
        ]);


        $permissaoCertificadosIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.certificacao.index'
        ]);

        $permissaoRelatoriosMatriculasCursoIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.relatoriosmatriculascurso.index'
        ]);

        $permissaoRelatoriosMatriculasDisciplinaIndex = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.relatoriosmatriculasdisciplina.index'
        ]);

        // Atirbuir permissao index ao perfil de Administrador
        $perfil->permissoes()->attach([$permissaoIndex->prm_id,
            $permissaoModulosIndex->prm_id,
            $permissaoPolosIndex->prm_id,
            $permissaoCentrosIndex->prm_id,
            $permissaoDepartamentosIndex->prm_id,
            $permissaoAlunosIndex->prm_id,
            $permissaoProfessoresIndex->prm_id,
            $permissaoTutoresIndex->prm_id,
            $permissaoVinculosIndex->prm_id,
            $permissaoCursosIndex->prm_id,
            $permissaoDisciplinasIndex->prm_id,
            $permissaoPeriodosLetivosIndex->prm_id,
            $permissaoOfertasCursoIndex->prm_id,
            $permissaoOfertasDisciplinaIndex->prm_id,
            $permissaoAlunosCursosIndex->prm_id,
            $permissaoAlunosDisciplinasIndex->prm_id,
            $permissaoDisciplinasLotesIndex->prm_id,
            $permissaoLancamentoTccIndex->prm_id,
            $permissaoConclusaoCursoIndex->prm_id,
            $permissaoHistoricoParcialIndex->prm_id,
            $permissaoHistoricoDefinitivoIndex->prm_id,

            $permissaoCertificadosIndex->prm_id,
            $permissaoRelatoriosMatriculasCursoIndex->prm_id,
            $permissaoRelatoriosMatriculasDisciplinaIndex->prm_id]);

        // Atribuir perfil de Administrador ao usuario criado
        $perfil->usuarios()->attach(1);

        // Criando itens no menu

        // Categoria Cadastros
        $cadastro = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_nome' => 'Cadastros',
            'mit_icone' => 'fa fa-plus',
            'mit_visivel' => 1,
            'mit_ordem' => 1
        ]);

        $moduloItem = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $cadastro->min_id,
            'mit_nome' => 'Dashboard',
            'mit_icone' => 'fa fa-cubes',
            'mit_rota' => 'academico.index.index',
            'mit_visivel' => 1,
            'mit_ordem' => 1
        ]);

        $institucional = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $cadastro->min_id,
            'mit_nome' => 'Institucional',
            'mit_icone' => 'fa fa-graduation-cap',
            'mit_visivel' => 1,
            'mit_ordem' => 2
        ]);

        $polos = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $institucional->min_id,
            'mit_nome' => 'Polos',
            'mit_rota' => 'academico.polos.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 1
        ]);

        $centros = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $institucional->min_id,
            'mit_nome' => 'Centros',
            'mit_rota' => 'academico.centros.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 2
        ]);

        $departamentos = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $institucional->min_id,
            'mit_nome' => 'Departamentos',
            'mit_rota' => 'academico.departamentos.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 3
        ]);
        $pessoas = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $cadastro->min_id,
            'mit_nome' => 'Pessoas',
            'mit_icone' => 'fa fa-plus',
            'mit_visivel' => 1,
            'mit_ordem' => 3
        ]);

        $alunos = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $pessoas->min_id,
            'mit_nome' => 'Alunos',
            'mit_rota' => 'academico.alunos.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 1
        ]);

        $professores = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $pessoas->min_id,
            'mit_nome' => 'Professores',
            'mit_rota' => 'academico.professores.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 2
        ]);

        $tutores = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $pessoas->min_id,
            'mit_nome' => 'Professores',
            'mit_rota' => 'academico.tutores.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 3
        ]);

        $educacao = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $cadastro->min_id,
            'mit_nome' => 'Educação',
            'mit_icone' => 'fa fa-plus',
            'mit_visivel' => 1,
            'mit_ordem' => 4
        ]);

        $vinculos = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $educacao->min_id,
            'mit_nome' => 'Vínculos',
            'mit_rota' => 'academico.vinculos.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 1
        ]);

        $cursos = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $educacao->min_id,
            'mit_nome' => 'Cursos',
            'mit_rota' => 'academico.cursos.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 1
        ]);

        $disciplinas = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $educacao->min_id,
            'mit_nome' => 'Disciplinas',
            'mit_rota' => 'academico.disciplinas.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 2
        ]);

        $periodos = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $educacao->min_id,
            'mit_nome' => 'Períodos Letivos',
            'mit_rota' => 'academico.periodosletivos.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 3
        ]);

        // Categoria Processos
        $processo = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_nome' => 'Processos',
            'mit_icone' => 'fa fa-plus',
            'mit_visivel' => 1,
            'mit_ordem' => 2
        ]);

        $ofertas = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $processo->min_id,
            'mit_nome' => 'Ofertas',
            'mit_icone' => 'fa fa-plus',
            'mit_visivel' => 1,
            'mit_ordem' => 1
        ]);

        $ofertascurso = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $ofertas->min_id,
            'mit_nome' => 'Cursos',
            'mit_rota' => 'academico.ofertascursos.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 1
        ]);

        $ofertasdisciplinas = MenuItem::create([

            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $ofertas->min_id,
            'mit_nome' => 'Disciplinas',
            'mit_rota' => 'academico.ofertasdisciplinas.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 2
        ]);
        $matriculas = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $processo->min_id,
            'mit_nome' => 'Matrículas',
            'mit_icone' => 'fa fa-plus',
            'mit_visivel' => 1,
            'mit_ordem' => 2
        ]);

        $matricularalunocurso = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $matriculas->min_id,
            'mit_nome' => 'Aluno em Curso',
            'mit_rota' => 'academico.matricularalunocurso.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 1
        ]);

        $alunosdisciplinas = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $matriculas->min_id,
            'mit_nome' => 'Aluno em Disciplina',
            'mit_rota' => 'academico.matriculasofertasdisciplinas.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 2
        ]);

        $disciplinaslotes = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $matriculas->min_id,
            'mit_nome' => 'Disciplinas em lote',
            'mit_rota' => 'academico.matriculaslote.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 3
        ]);

        $processoconclusao = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $processo->min_id,
            'mit_nome' => 'Conclusão',
            'mit_icone' => 'fa fa-plus',
            'mit_visivel' => 1,
            'mit_ordem' => 3
        ]);

        $lancamentotcc = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $processoconclusao->min_id,
            'mit_nome' => 'Lançamento de TCC',
            'mit_rota' => 'academico.lancamentostccs.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 1
        ]);

        $conclusaocurso = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $processoconclusao->min_id,
            'mit_nome' => 'Conclusão de Curso',
            'mit_rota' => 'academico.conclusaocurso.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 2
        ]);

        // Categoria Documentos
        $documentos = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_nome' => 'Documentos',
            'mit_icone' => 'fa fa-plus',
            'mit_visivel' => 1,
            'mit_ordem' => 3
        ]);

        $historicos = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $documentos->min_id,
            'mit_nome' => 'Históricos',
            'mit_icone' => 'fa fa-plus',
            'mit_visivel' => 1,
            'mit_ordem' => 1
        ]);

        $historicoparcial = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $historicos->min_id,
            'mit_nome' => 'Parcial',
            'mit_rota' => 'academico.historicoparcial.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 1
        ]);

        $historicodefinitivo = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $historicos->min_id,
            'mit_nome' => 'Definitivo',
            'mit_rota' => 'academico.historicodefinitivo.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 2
        ]);

        $historicoconclusao = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $documentos->min_id,
            'mit_nome' => 'Conclusão',
            'mit_icone' => 'fa fa-plus',
            'mit_visivel' => 1,
            'mit_ordem' => 2
        ]);


        $certificados = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $historicoconclusao->min_id,
            'mit_nome' => 'Certificados',
            'mit_rota' => 'academico.certificacao.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 1
        ]);

        // Categoria Relatórios
        $relatorios = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_nome' => 'Relatórios',
            'mit_icone' => 'fa fa-plus',
            'mit_visivel' => 1,
            'mit_ordem' => 4
        ]);

        $relatoriosmatriculas = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $relatorios->min_id,
            'mit_nome' => 'Matrículas',
            'mit_icone' => 'fa fa-plus',
            'mit_visivel' => 1,
            'mit_ordem' => 1
        ]);

        $relatoriosmatriculascurso = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $relatoriosmatriculas->min_id,
            'mit_nome' => 'Por Curso',
            'mit_rota' => 'academico.relatoriosmatriculascurso.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 1
        ]);

        $relatoriosmatriculasdisciplina = MenuItem::create([
            'mit_mod_id' => $modulo->mod_id,
            'mit_item_pai' => $relatoriosmatriculas->min_id,
            'mit_nome' => 'Por Disciplina',
            'mit_rota' => 'academico.relatoriosmatriculasdisciplina.index',
            'mit_icone' => 'fa fa-ellipsis-h',
            'mit_visivel' => 1,
            'mit_ordem' => 2
        ]);
    }
}
