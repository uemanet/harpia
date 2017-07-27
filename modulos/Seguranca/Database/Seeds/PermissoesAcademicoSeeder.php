<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class PermissoesAcademicoSeeder extends Seeder
{
    public function run()
    {

        // Cria perfil de Administrador
        $perfil = Perfil::create([
            'prf_mod_id' => 3,
            'prf_nome' => 'Administrador Acadêmico'
        ]);

        $arrPermissoes = [];

        // Criar permissao index do modulo Academico (DASHBOARD)
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.index.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        //permissões do recurso polos
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.polos.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.polos.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'academico.polos.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'academico.polos.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso departamentos
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.departamentos.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.departamentos.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'academico.departamentos.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'academico.departamentos.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso periodosletivos
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.periodosletivos.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.periodosletivos.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'academico.periodosletivos.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'academico.periodosletivos.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        //permissões do recurso cursos
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.cursos.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.cursos.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'academico.cursos.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'academico.cursos.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso matrizescurriculares
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.cursos.matrizescurriculares.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.cursos.matrizescurriculares.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'academico.cursos.matrizescurriculares.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'anexo',
            'prm_rota' => 'academico.cursos.matrizescurriculares.anexo'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'academico.cursos.matrizescurriculares.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;



        //permissões do recurso centros
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.centros.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.centros.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'academico.centros.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'academico.centros.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;



        //permissões do recurso ofertascursos
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.ofertascursos.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.ofertascursos.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso turmas
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.ofertascursos.turmas.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.ofertascursos.turmas.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'academico.ofertascursos.turmas.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'academico.ofertascursos.turmas.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        //permissões do recurso grupos
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.ofertascursos.turmas.grupos.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.ofertascursos.turmas.grupos.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'academico.ofertascursos.turmas.grupos.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'academico.ofertascursos.turmas.grupos.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso tutoresgrupos
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.ofertascursos.turmas.grupos.tutoresgrupos.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.ofertascursos.turmas.grupos.tutoresgrupos.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'alterartutor',
            'prm_rota' => 'academico.ofertascursos.turmas.grupos.tutoresgrupos.alterartutor'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'academico.ofertascursos.turmas.grupos.tutoresgrupos.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso disciplinas
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.disciplinas.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.disciplinas.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'academico.disciplinas.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'academico.disciplinas.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;



        //permissões do recurso modulosmatrizes
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.cursos.matrizescurriculares.modulosmatrizes.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.cursos.matrizescurriculares.modulosmatrizes.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'academico.cursos.matrizescurriculares.modulosmatrizes.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'academico.cursos.matrizescurriculares.modulosmatrizes.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'gerenciardisciplinas',
            'prm_rota' => 'academico.cursos.matrizescurriculares.modulosmatrizes.gerenciardisciplinas'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'adicionardisciplinas',
            'prm_rota' => 'academico.cursos.matrizescurriculares.modulosmatrizes.adicionardisciplinas'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        //permissões do recurso usuarioscursos
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.vinculos.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.vinculos.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'vinculos',
            'prm_rota' => 'academico.vinculos.vinculos'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'delete',
            'prm_rota' => 'academico.vinculos.delete'
        ]);
        $arrPermissoes[] = $permissao->prm_id;




        //permissões do recurso tutores
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.tutores.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.tutores.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'academico.tutores.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'show',
            'prm_rota' => 'academico.tutores.show'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso alunos
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.alunos.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.alunos.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'academico.alunos.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'show',
            'prm_rota' => 'academico.alunos.show'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso professores
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.professores.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.professores.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'academico.professores.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'show',
            'prm_rota' => 'academico.professores.show'
        ]);
        $arrPermissoes[] = $permissao->prm_id;




        //permissões do recurso ofertasdisciplinas
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.ofertasdisciplinas.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.ofertasdisciplinas.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;



        //permissões do recurso matricularalunocurso
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.matricularalunocurso.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.matricularalunocurso.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'academico.matricularalunocurso.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'show',
            'prm_rota' => 'academico.matricularalunocurso.show'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        //permissões do recurso relatoriosmatriculascurso
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.relatoriosmatriculascurso.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'pdf',
            'prm_rota' => 'academico.relatoriosmatriculascurso.pdf'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'xls',
            'prm_rota' => 'academico.relatoriosmatriculascurso.xls'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        //permissões do recurso matriculasofertasdisciplinas
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.matriculasofertasdisciplinas.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'pdf',
            'prm_rota' => 'academico.matriculasofertasdisciplinas.show'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        //permissões do recurso relatoriosmatriculasdisciplinas
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.relatoriosmatriculasdisciplinas.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'pdf',
            'prm_rota' => 'academico.relatoriosmatriculasdisciplinas.pdf'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'xls',
            'prm_rota' => 'academico.relatoriosmatriculasdisciplinas.xls'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        //permissões do recurso matriculaslote
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.matriculaslote.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso lancamentostccs
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.lancamentostccs.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'create',
            'prm_rota' => 'academico.lancamentostccs.create'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'edit',
            'prm_rota' => 'academico.lancamentostccs.edit'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'alunosturma',
            'prm_rota' => 'academico.lancamentostccs.alunosturma'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'tccanexo',
            'prm_rota' => 'academico.lancamentostccs.anexo'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        //permissões do recurso conclusaocurso
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.conclusaocurso.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso certificacao
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.certificacao.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        //permissões do recurso controlederegistro
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.controlederegistro.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        //permissões do recurso historicoparcial
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.historicoparcial.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'show',
            'prm_rota' => 'academico.historicoparcial.show'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'print',
            'prm_rota' => 'academico.historicoparcial.print'
        ]);
        $arrPermissoes[] = $permissao->prm_id;



        //permissões do recurso historicodefinitivo
        $permissao = Permissao::create([
            'prm_nome' => 'index',
            'prm_rota' => 'academico.historicodefinitivo.index'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'print',
            'prm_rota' => 'academico.historicodefinitivo.print'
        ]);
        $arrPermissoes[] = $permissao->prm_id;

        $permissao = Permissao::create([
            'prm_nome' => 'print',
            'prm_rota' => 'academico.controlederegistro.show'
        ]);
        $arrPermissoes[] = $permissao->prm_id;


        // Atirbuir permissao index ao perfil de Administrador
        $perfil->permissoes()->attach($arrPermissoes);

        // Atribuir perfil de Administrador ao usuario criado
        $perfil->usuarios()->attach(1);
    }
}
