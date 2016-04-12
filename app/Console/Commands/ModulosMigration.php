<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ModulosMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modulos:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executa as migrations dos modulos';

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

        while (list(,$modulo) = each($modulos)) {

            // Load the migrations
            $moduloPath = '/modulos/' . $modulo.'/Database/Migrations';
            $dirPath = base_path() . $moduloPath;
            if (is_dir($dirPath )) {
                Artisan::call('migrate', ['--path' => $moduloPath]);

                $this->info($modulo . ' migration complete!');
            }
        }
    }
}
