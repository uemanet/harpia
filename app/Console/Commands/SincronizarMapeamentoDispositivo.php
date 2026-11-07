<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modulos\RH\Models\DispositivoAcesso;
use Modulos\RH\Repositories\MapeamentoDispositivoRepository;
use Modulos\RH\Services\ControlIdApiClient;

class SincronizarMapeamentoDispositivo extends Command
{
    protected $signature = 'ponto:sincronizar-mapeamento {dis_id?}';

    protected $description = 'Sincroniza usuarios do iDFace com a tabela de mapeamento';

    public function __construct(
        private MapeamentoDispositivoRepository $mapeamentoRepo,
        private ControlIdApiClient $apiClient
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
            $usuarios = $this->apiClient->loadObjects($dispositivo, 'users');
            $total = count($usuarios['users'] ?? []);
            $vinculados = 0;

            foreach ($usuarios['users'] ?? [] as $user) {
                $map = $this->mapeamentoRepo->buscarPorUserId($dispositivo->dis_id, (string) $user['user_id']);

                if (!$map) {
                    $this->mapeamentoRepo->create([
                        'map_dis_id' => $dispositivo->dis_id,
                        'map_user_id' => (string) $user['user_id'],
                        'map_registration' => (string) ($user['registration'] ?? ''),
                        'map_nome_dispositivo' => $user['name'] ?? null,
                        'map_col_id' => null,
                        'map_ativo' => true,
                    ]);
                } else {
                    $this->mapeamentoRepo->update([
                        'map_registration' => (string) ($user['registration'] ?? ''),
                        'map_nome_dispositivo' => $user['name'] ?? null,
                    ], $map->map_id);
                }

                if ($map && $map->map_col_id) {
                    $vinculados++;
                }
            }

            $this->info(sprintf(
                '[%s] %d usuarios, %d vinculados, %d pendentes.',
                $dispositivo->dis_nome,
                $total,
                $vinculados,
                $total - $vinculados
            ));
        }

        return 0;
    }
}
