<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modulos\RH\Repositories\DispositivoAcessoRepository;
use Modulos\RH\Services\ColetaEventosDispositivoService;

class ColetarEventosControlId extends Command
{
    protected $signature = 'ponto:coletar-eventos {dis_id?} {--limit=}';

    protected $description = 'Coleta logs de acesso do iDFace via polling com cursor incremental';

    public function __construct(
        private DispositivoAcessoRepository $dispositivoRepository,
        private ColetaEventosDispositivoService $coletaEventosDispositivoService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $dispositivoId = $this->argument('dis_id');
        $limit = (int) ($this->option('limit') ?: config('ponto.polling.limit', 500));

        if ($dispositivoId) {
            $dispositivo = $this->dispositivoRepository->find((int) $dispositivoId);

            if (!$dispositivo || $dispositivo->dis_status !== 'ativo') {
                $this->error('Dispositivo ativo nao encontrado.');
                return 1;
            }

            $dispositivos = [$dispositivo];
        } else {
            $dispositivos = $this->dispositivoRepository->buscarAtivos();
        }

        foreach ($dispositivos as $dispositivo) {
            try {
                $resultado = $this->coletaEventosDispositivoService->coletarNovosEventos($dispositivo, $limit);

                $this->info(sprintf(
                    '[%s] lidos=%d processado=%d erro=%d duplicado=%d ignorado=%d ultimo_id=%d',
                    $dispositivo->dis_nome,
                    $resultado['lidos'],
                    $resultado['processado'],
                    $resultado['erro'],
                    $resultado['duplicado'],
                    $resultado['ignorado'],
                    $resultado['ultimo_id_atual']
                ));
            } catch (\Exception $e) {
                $this->error(sprintf(
                    '[%s] falha na coleta: %s',
                    $dispositivo->dis_nome,
                    $e->getMessage()
                ));
            }
        }

        return 0;
    }
}
