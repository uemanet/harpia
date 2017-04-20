<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class MenuAcademicoSeeder extends Seeder
{
    public function run()
    {
        // Criando itens no menu

      // Categoria Cadastros
      $cadastro = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_nome' => 'Cadastros',
          'mit_icone' => 'fa fa-plus',
          'mit_ordem' => 1
      ]);

        $dashboard = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $cadastro->mit_id,
          'mit_nome' => 'Dashboard',
          'mit_icone' => 'fa fa-cubes',
          'mit_rota' => 'academico.index.index',
          'mit_ordem' => 1
      ]);

      // Subcategoria Institucional
      $institucional = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $cadastro->mit_id,
          'mit_nome' => 'Institucional',
          'mit_icone' => 'fa fa-graduation-cap',
          'mit_visivel' => 1,
          'mit_ordem' => 2
      ]);

        $polos = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $institucional->mit_id,
          'mit_nome' => 'Polos',
          'mit_rota' => 'academico.polos.index',
          'mit_icone' => 'fa fa-ellipsis-h',
          'mit_ordem' => 1
      ]);

        $centros = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $institucional->mit_id,
          'mit_nome' => 'Centros',
          'mit_rota' => 'academico.centros.index',
          'mit_icone' => 'fa fa-map-marker',
          'mit_ordem' => 2
      ]);

        $departamentos = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $institucional->mit_id,
          'mit_nome' => 'Departamentos',
          'mit_rota' => 'academico.departamentos.index',
          'mit_icone' => 'fa fa-sitemap',
          'mit_ordem' => 3
      ]);

      // Subcategoria Pessoas
      $pessoas = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $cadastro->mit_id,
          'mit_nome' => 'Pessoas',
          'mit_icone' => 'fa fa-plus',
          'mit_ordem' => 3
      ]);

        $alunos = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $pessoas->mit_id,
          'mit_nome' => 'Alunos',
          'mit_rota' => 'academico.alunos.index',
          'mit_icone' => 'fa fa-address-card-o',
          'mit_ordem' => 1
      ]);

        $professores = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $pessoas->mit_id,
          'mit_nome' => 'Professores',
          'mit_rota' => 'academico.professores.index',
          'mit_icone' => 'fa fa-id-card-o',
          'mit_visivel' => 1,
          'mit_ordem' => 2
      ]);

        $tutores = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $pessoas->mit_id,
          'mit_nome' => 'Tutores',
          'mit_rota' => 'academico.tutores.index',
          'mit_icone' => 'fa fa-group',
          'mit_visivel' => 1,
          'mit_ordem' => 3
      ]);

      // Subcategoria Educação
      $educacao = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $cadastro->mit_id,
          'mit_nome' => 'Educação',
          'mit_icone' => 'fa fa-plus',
          'mit_ordem' => 4
      ]);

        $vinculos = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $educacao->mit_id,
          'mit_nome' => 'Vínculos',
          'mit_rota' => 'academico.vinculos.index',
          'mit_icone' => 'fa fa-link',
          'mit_visivel' => 1,
          'mit_ordem' => 1
      ]);

        $cursos = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $educacao->mit_id,
          'mit_nome' => 'Cursos',
          'mit_rota' => 'academico.cursos.index',
          'mit_icone' => 'fa fa-graduation-cap',
          'mit_ordem' => 2
      ]);

        $disciplinas = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $educacao->mit_id,
          'mit_nome' => 'Disciplinas',
          'mit_rota' => 'academico.disciplinas.index',
          'mit_icone' => 'fa fa-book',
          'mit_ordem' => 3
      ]);

        $periodos = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $educacao->mit_id,
          'mit_nome' => 'Períodos Letivos',
          'mit_rota' => 'academico.periodosletivos.index',
          'mit_icone' => 'fa fa-calendar',
          'mit_ordem' => 4
      ]);

      // Categoria Processos
      $processos = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_nome' => 'Processos',
          'mit_icone' => 'fa fa-plus',
          'mit_ordem' => 2
      ]);

      // Subcategoria Ofertas
      $ofertas = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $processos->mit_id,
          'mit_nome' => 'Ofertas',
          'mit_icone' => 'fa fa-plus',
          'mit_ordem' => 1
      ]);

        $ofertascurso = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $ofertas->mit_id,
          'mit_nome' => 'Cursos',
          'mit_rota' => 'academico.ofertascursos.index',
          'mit_icone' => 'fa fa-circle-o',
          'mit_ordem' => 1
      ]);

        $ofertasdisciplinas = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $ofertas->mit_id,
          'mit_nome' => 'Disciplinas',
          'mit_rota' => 'academico.ofertasdisciplinas.index',
          'mit_icone' => 'fa fa-circle-o',
          'mit_visivel' => 1,
          'mit_ordem' => 2
      ]);

      // Subcategoria Matriculas
      $matriculas = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $processos->mit_id,
          'mit_nome' => 'Matrículas',
          'mit_icone' => 'fa fa-plus',
          'mit_ordem' => 2
      ]);

        $matricularalunocurso = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $matriculas->mit_id,
          'mit_nome' => 'Aluno em Curso',
          'mit_rota' => 'academico.matricularalunocurso.index',
          'mit_icone' => 'fa fa-university',
          'mit_ordem' => 1
      ]);

        $alunosdisciplinas = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $matriculas->mit_id,
          'mit_nome' => 'Aluno em Disciplina',
          'mit_rota' => 'academico.matriculasofertasdisciplinas.index',
          'mit_icone' => 'fa fa-book',
          'mit_ordem' => 2
      ]);

        $disciplinaslotes = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $matriculas->mit_id,
          'mit_nome' => 'Disciplinas em lote',
          'mit_rota' => 'academico.matriculaslote.index',
          'mit_icone' => 'fa fa-database',
          'mit_ordem' => 3
      ]);

      // Subcategoria Conclusão
      $processoconclusao = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $processos->mit_id,
          'mit_nome' => 'Conclusão',
          'mit_icone' => 'fa fa-plus',
          'mit_ordem' => 3
      ]);

        $lancamentotcc = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $processoconclusao->mit_id,
          'mit_nome' => 'Lançamento de TCC',
          'mit_rota' => 'academico.lancamentostccs.index',
          'mit_icone' => 'fa fa-archive',
          'mit_ordem' => 1
      ]);

        $conclusaocurso = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $processoconclusao->mit_id,
          'mit_nome' => 'Conclusão de Curso',
          'mit_rota' => 'academico.conclusaocurso.index',
          'mit_icone' => 'fa fa-graduation-cap',
          'mit_ordem' => 2
      ]);

      // Categoria Documentos
      $documentos = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_nome' => 'Documentos',
          'mit_icone' => 'fa fa-file-text-o',
          'mit_ordem' => 3
      ]);

      // Subcategorias Históricos
      $historicos = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $documentos->mit_id,
          'mit_nome' => 'Históricos',
          'mit_icone' => 'fa fa-file-text-o',
          'mit_ordem' => 1
      ]);

        $historicoparcial = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $historicos->mit_id,
          'mit_nome' => 'Parcial',
          'mit_rota' => 'academico.historicoparcial.index',
          'mit_icone' => 'fa fa-file-text-o',
          'mit_ordem' => 1
      ]);

        $historicodefinitivo = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $historicos->mit_id,
          'mit_nome' => 'Definitivo',
          'mit_rota' => 'academico.historicodefinitivo.index',
          'mit_icone' => 'fa fa-file-text-o',
          'mit_ordem' => 2
      ]);

      // Subcategoria Conclusão
      $historicoconclusao = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $documentos->mit_id,
          'mit_nome' => 'Conclusão',
          'mit_icone' => 'fa fa-plus',
          'mit_ordem' => 2
      ]);

        $certificados = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $historicoconclusao->mit_id,
          'mit_nome' => 'Certificados',
          'mit_rota' => 'academico.certificacao.index',
          'mit_icone' => 'fa fa-ellipsis-h',
          'mit_ordem' => 1
      ]);

      // Categoria Relatórios
      $relatorios = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_nome' => 'Relatórios',
          'mit_icone' => 'fa fa-file-text-o',
          'mit_ordem' => 4
      ]);

      // Subcategoria Matriculas
      $relatoriosmatriculas = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $relatorios->mit_id,
          'mit_nome' => 'Matrículas',
          'mit_icone' => 'fa fa-file-text-o',
          'mit_ordem' => 1
      ]);

        $relatoriosmatriculascurso = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $relatoriosmatriculas->mit_id,
          'mit_nome' => 'Por Curso',
          'mit_rota' => 'academico.relatoriosmatriculascurso.index',
          'mit_icone' => 'fa fa-file-text-o',
          'mit_ordem' => 1
      ]);

        $relatoriosmatriculasdisciplina = MenuItem::create([
          'mit_mod_id' => 3,
          'mit_item_pai' => $relatoriosmatriculas->mit_id,
          'mit_nome' => 'Por Disciplina',
          'mit_rota' => 'academico.relatoriosmatriculasdisciplinas.index',
          'mit_icone' => 'fa fa-file-text-o',
          'mit_ordem' => 2
      ]);
    }
}
