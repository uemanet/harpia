<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modulos\RH\Models\DispositivoAcesso;
use Modulos\RH\Models\MapeamentoDispositivo;

class ExportarUsuariosDispositivo extends Command
{
    protected $signature = 'ponto:exportar-usuarios {dis_id?}';

    protected $description = 'Exporta CSV de mapeamento para o painel Control iD';

    public function handle()
    {
        $dispositivoId = $this->argument('dis_id');

        if ($dispositivoId) {
            $dispositivos = [DispositivoAcesso::findOrFail((int) $dispositivoId)];
        } else {
            $dispositivos = DispositivoAcesso::where('dis_status', 'ativo')->get();
        }

        foreach ($dispositivos as $dispositivo) {
            $mapeamentos = MapeamentoDispositivo::where('map_dis_id', $dispositivo->dis_id)
                ->where('map_ativo', true)
                ->get();

            $output = "user_id,registration,name,col_id\n";

            foreach ($mapeamentos as $map) {
                $output .= sprintf(
                    "%s,%s,%s,%s\n",
                    $map->map_user_id,
                    $map->map_registration,
                    $map->map_nome_dispositivo ?? '',
                    $map->map_col_id ?? ''
                );
            }

            $filename = sprintf('export_%s_%s.csv', $dispositivo->dis_identificador, date('YmdHis'));
            $path = storage_path($filename);
            file_put_contents($path, $output);

            $this->info(sprintf('[%s] Exportado: %s (%d usuarios)', $dispositivo->dis_nome, $filename, $mapeamentos->count()));
        }

        return 0;
    }
}
