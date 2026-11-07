<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modulos\RH\Repositories\EventoAcessoRepository;

class LimparEventosAntigos extends Command
{
    protected $signature = 'ponto:limpar-eventos-antigos {--days=30}';

    protected $description = 'Expurga eventos de acesso antigos (LGPD)';

    public function __construct(
        private EventoAcessoRepository $eventoRepository
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $dias = (int) $this->option('days');
        $removidos = $this->eventoRepository->expurgarAntigos($dias);

        $this->info(sprintf('%d eventos de acesso removidos (mais de %d dias).', $removidos, $dias));

        return 0;
    }
}
