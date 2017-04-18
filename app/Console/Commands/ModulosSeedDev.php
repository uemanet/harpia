<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ModulosSeedDev extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modulos:seeddev';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executa os seeds dos modulos com dados de teste';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $modulos = config('modulos.modulos');

        while (list(, $modulo) = each($modulos)) {

            // Load the migrations
            $class = '\Modulos\\'. $modulo .'\Database\Seeds\Development\\'. $modulo .'Seeder';
            if (class_exists($class)) {
                Artisan::call('db:seed', ['--class' => $class]);

                $this->info($modulo . ' Seed complete!');
            }
        }
    }
}
