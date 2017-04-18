<?php

namespace Modulos\Academico\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Illuminate\Contracts\Foundation\Application;

class AcademicoSeeder extends Seeder
{

    /**
     * The Laravel Application.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AlunosTutoresProfessoresTableSeeder::class);
        $this->command->info('Alunos, Tutores e Professores seeded!');

        $this->call(CentroTableSeeder::class);
        $this->command->info('Centro Table seeded!');

        $this->call(DepartamentoTableSeeder::class);
        $this->command->info('Departamento Table seeded!');

        $this->call(PoloTableSeeder::class);
        $this->command->info('Polo Table seeded!');

        $this->call(CursoTableSeeder::class);
        $this->command->info('Curso Table seeded!');

        $this->call(MatrizCurricularSeeder::class);
        $this->command->info('MatrizCurricular Table seeded!');

        $this->call(PeriodoLetivoSeeder::class);
        $this->command->info('Periodo Letivo Table seeded!');

        $this->call(DisciplinasTableSeeder::class);
        $this->command->info('Disciplinas Table seeded!');

        $this->call(VinculoTableSeeder::class);
        $this->command->info('Vinculo Table seeded!');

        $this->call(OfertaCursoTableSeeder::class);
        $this->command->info('Oferta de Curso Table seeded!');

        $this->call(ModuloMatrizTableSeeder::class);
        $this->command->info('Modulo Matriz Table seeded!');

        $this->call(ModuloDisciplinasTableSeeder::class);
        $this->command->info('Modulo Disciplina Table seeded!');

        $this->call(TurmaTableSeeder::class);
        $this->command->info('Turma Table seeded!');

        $this->call(GrupoTableSeeder::class);
        $this->command->info('Grupo Table seeded!');

        $this->call(MatriculaCursoTableSeeder::class);
        $this->command->info('Matricula Curso Table seeded!');

        $this->call(OfertaDisciplinasTableSeeder::class);
        $this->command->info('Oferta Disciplina Table seeded!');

        $this->call(MatriculaOfertaDisciplinaTableSeeder::class);
        $this->command->info('Matricula Oferta Disciplina Table seeded!');

        $this->call(LivrosTableSeeder::class);
        $this->command->info('Livro Table seeded!');

        $this->call(MatriculasConcluidasSeeder::class);
        $this->command->info('Matriculas Concluidas seeded!');
    }
}
