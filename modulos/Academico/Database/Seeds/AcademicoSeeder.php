<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;

class AcademicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AlunosTutoresProfessoresTableSeeder::class);
        $this->command->info('Alunos, Tutores e Professores seeded!');

        $this->call(ModalidadeTableSeeder::class);
        $this->command->info('Modalidades table seeded!');

        $this->call(NivelCursoTableSeeder::class);
        $this->command->info('NiveisCursos table seeded!');

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

        $this->call(MatriculaCursoTableSeeder::class);
        $this->command->info('Matricula Curso Table seeded!');

        $this->call(OfertaDisciplinasTableSeeder::class);
        $this->command->info('Oferta Disciplina Table seeded!');

        $this->call(MatriculaOfertaDisciplinaTableSeeder::class);
        $this->command->info('Matricula Oferta Disciplina Table seeded!');

        $this->call(LivrosTableSeeder::class);
        $this->command->info('Livro Table seeded!');
    }
}
