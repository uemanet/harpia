<?php

namespace App\Console\Commands;

use Harpia\Event\SincronizacaoFactory;
use Illuminate\Console\Command;
use Modulos\Integracao\Models\Sincronizacao;

class RetrySincronizacaoMoodleCommand extends Command
{
    protected $signature = 'integracao:retry-sincronizacao
                            {--limit=50 : Número máximo de registros processados por execução}';

    protected $description = 'Retenta automaticamente sincronizações pendentes ou com falha no Moodle (máximo 3 tentativas)';

    const MAX_TENTATIVAS = 3;

    public function handle()
    {
        $limit = (int) $this->option('limit');

        $pendentes = Sincronizacao::whereIn('sym_status', [1, 3])
            ->where('sym_tentativas', '<', self::MAX_TENTATIVAS)
            ->limit($limit)
            ->get();

        if ($pendentes->isEmpty()) {
            $this->info('Nenhuma sincronização pendente para reprocessar.');
            return;
        }

        $this->info("Reprocessando {$pendentes->count()} sincronização(ões)...");

        $sucesso = 0;
        $erro = 0;

        foreach ($pendentes as $sincronizacao) {
            try {
                $sincronizacao->increment('sym_tentativas');

                $evento = SincronizacaoFactory::factory($sincronizacao);
                event($evento);

                $sucesso++;
            } catch (\Exception $e) {
                $erro++;
                $this->error("Falha ao reprocessar sym_id={$sincronizacao->sym_id}: {$e->getMessage()}");
            }
        }

        $this->info("Concluído. Sucesso: {$sucesso} | Erros: {$erro}");

        $esgotados = Sincronizacao::where('sym_status', 3)
            ->where('sym_tentativas', '>=', self::MAX_TENTATIVAS)
            ->count();

        if ($esgotados > 0) {
            $this->warn("{$esgotados} registro(s) atingiram o limite de tentativas e precisam de intervenção manual.");
        }
    }
}
