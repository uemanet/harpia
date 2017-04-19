<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ModulosSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modulos:seed {--dev}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executa os seeds dos modulos';

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

        if ($this->option('dev')) {
            while (list(, $modulo) = each($modulos)) {

              // Load the migrations
              if ($modulo == 'Seguranca') {
                  $class = '\Modulos\\'. $modulo .'\Database\Seeds\\'. $modulo .'Seeder';
              } else {
                  $class = '\Modulos\\'. $modulo .'\Database\Seeds\Production\\'. $modulo .'Seeder';
              }
                if (class_exists($class)) {
                    Artisan::call('db:seed', ['--class' => $class]);
                    $this->info($modulo . ' Seed complete!');
                }

              // Load the migrations
              if ($modulo == 'Seguranca') {
                  $class = null;
              } else {
                  $class = '\Modulos\\'. $modulo .'\Database\Seeds\Development\\'. $modulo .'Seeder';
              }
                if (class_exists($class)) {
                    Artisan::call('db:seed', ['--class' => $class]);
                    $this->info($modulo . ' Development Seed complete!');
                }
            }
        } else {
            while (list(, $modulo) = each($modulos)) {

              // Load the migrations
              if ($modulo == 'Seguranca') {
                  $class = '\Modulos\\'. $modulo .'\Database\Seeds\\'. $modulo .'Seeder';
              } else {
                  $class = '\Modulos\\'. $modulo .'\Database\Seeds\Production\\'. $modulo .'Seeder';
              }

                if (class_exists($class)) {
                    Artisan::call('db:seed', ['--class' => $class]);

                    $this->info($modulo . ' Seed complete!');
                }
            }
        }
    }
}
