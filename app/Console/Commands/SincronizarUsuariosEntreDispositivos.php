<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modulos\RH\Models\DispositivoAcesso;
use Modulos\RH\Services\SincronizacaoUsuariosDispositivoService;

class SincronizarUsuariosEntreDispositivos extends Command
{
    protected $signature = 'ponto:sincronizar-entre-dispositivos
                            {dis_id? : ID do dispositivo de origem}
                            {--todos : Sincroniza entre todos os pares de dispositivos ativos}';

    protected $description = 'Replica usuarios de um dispositivo para outro(s). Ex: sincronizar usuarios do dispositivo de entrada com o de saida.';

    public function __construct(
        private SincronizacaoUsuariosDispositivoService $sincronizacaoService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $dispositivoId = $this->argument('dis_id');
        $todos = $this->option('todos');

        $dispositivosAtivos = DispositivoAcesso::where('dis_status', 'ativo')->get();

        if ($dispositivosAtivos->count() < 2) {
            $this->warn('E necessario pelo menos 2 dispositivos ativos para sincronizar entre eles.');

            return 0;
        }

        if ($todos) {
            return $this->sincronizarTodosPares($dispositivosAtivos);
        }

        if ($dispositivoId) {
            return $this->sincronizarOrigemParaDemais((int) $dispositivoId, $dispositivosAtivos);
        }

        $this->error('Informe o ID do dispositivo de origem ou use --todos.');

        return 1;
    }

    private function sincronizarOrigemParaDemais(int $origemId, $dispositivosAtivos): int
    {
        $origem = $dispositivosAtivos->firstWhere('dis_id', $origemId);

        if (!$origem) {
            $this->error('Dispositivo de origem nao encontrado ou nao esta ativo.');

            return 1;
        }

        $destinos = $dispositivosAtivos->where('dis_id', '!=', $origemId);

        if ($destinos->isEmpty()) {
            $this->warn('Nenhum dispositivo de destino encontrado.');

            return 0;
        }

        $resultado = $this->sincronizacaoService->sincronizarUsuariosEntreDispositivosEmLote($origem, $destinos);
        $this->exibirResultado($resultado);

        return 0;
    }

    private function sincronizarTodosPares($dispositivos): int
    {
        foreach ($dispositivos as $origem) {
            $destinos = $dispositivos->where('dis_id', '!=', $origem->dis_id);

            if ($destinos->isEmpty()) {
                continue;
            }

            $resultado = $this->sincronizacaoService->sincronizarUsuariosEntreDispositivosEmLote($origem, $destinos);
            $this->exibirResultado($resultado);
        }

        return 0;
    }

    private function exibirResultado(array $resultado): void
    {
        $resumo = $resultado['resumo'];

        $this->info(sprintf(
            '[Origem: %s] %d dispositivo(s): %d criados, %d atualizados, %d inalterados.',
            $resultado['origem'],
            $resumo['dispositivos'],
            $resumo['criados'],
            $resumo['atualizados'],
            $resumo['inalterados']
        ));

        foreach ($resultado['erros'] as $erro) {
            $this->error(sprintf('  ERRO [%s]: %s', $erro['dispositivo'], $erro['mensagem']));
        }
    }
}
