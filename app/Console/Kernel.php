<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        Commands\ModulosMigrate::class,
        Commands\ModulosSeed::class,
        Commands\ColetarEventosControlId::class,
        Commands\SincronizarUsuariosEntreDispositivos::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // EVERY MINUTE PARA TESTES. DEPOIS ALTERAR PARA 5 MINUTOS OU MAIS, DEPENDENDO DO VOLUME DE EVENTOS.
        $schedule->command('ponto:coletar-eventos')->everyMinute();

        // Sincroniza usuarios entre dispositivos a cada 5 minutos.
        // Exemplo: replica usuarios do dispositivo de entrada no dispositivo de saida.
        $schedule->command('ponto:sincronizar-entre-dispositivos --todos')->everyFiveMinutes();
    }
}
