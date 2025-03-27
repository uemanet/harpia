<?php

namespace Modulos\RH\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Illuminate\Contracts\Foundation\Application;
use Modulos\RH\Database\Seeds\Development\BancoTableSeeder;
use Modulos\RH\Database\Seeds\Development\FontePagadoraTableSeeder;
use Modulos\RH\Database\Seeds\Development\FuncaoTableSeeder;
use Modulos\RH\Database\Seeds\Production\TipoJustificativaSeeder;
use Modulos\RH\Models\Colaborador;

class RHSeeder extends Seeder
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
        $this->call(BancoTableSeeder::class);
        $this->command->info('Bancos seeded!');

        $this->call(FontePagadoraTableSeeder::class);
        $this->command->info('Fontes Pagadoras Table seeded!');

        $this->call(FuncaoTableSeeder::class);
        $this->command->info('Função Table seeded!');

        $this->call(VinculoTableSeeder::class);
        $this->command->info('Vínculo Table seeded!');

        $this->call(PeriodosLaboraisTableSeeder::class);
        $this->command->info('Periodos Laborais Table seeded!');

        $this->call(ColaboradoresTableSeeder::class);
        $this->command->info('Colaborador Table seeded!');

        $this->call(HorasTrabalhadasTableSeeder::class);
        $this->command->info('Horas Trabalhadas Table seeded!');

        $this->call(TipoJustificativaSeeder::class);
        $this->command->info('Tipos Justificativas Table seeded!');

    }
}
