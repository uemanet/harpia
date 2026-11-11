<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modulos\RH\Models\DispositivoAcesso;
use Modulos\RH\Services\SincronizacaoUsuariosDispositivoService;

class SincronizarMapeamentoDispositivo extends Command
{
    protected $signature = 'ponto:sincronizar-mapeamento {dis_id?}';

    protected $description = 'Sincroniza usuarios do iDFace com a tabela de mapeamento';

    public function __construct(
        private SincronizacaoUsuariosDispositivoService $sincronizacaoService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $dispositivoId = $this->argument('dis_id');

        if ($dispositivoId) {
            $dispositivos = [DispositivoAcesso::findOrFail((int) $dispositivoId)];
        } else {
            $dispositivos = DispositivoAcesso::where('dis_status', 'ativo')->get();
        }

        foreach ($dispositivos as $dispositivo) {
            $resultado = $this->sincronizacaoService->sincronizar($dispositivo);
            $resumo = $resultado['resumo'];

            $this->info(sprintf(
                '[%s] %d usuarios, %d vinculados, %d pendentes.',
                $dispositivo->dis_nome,
                $resumo['total'],
                $resumo['vinculados'],
                $resumo['pendentes']
            ));
        }

        return 0;
    }
}
