<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Models\Colaborador;
use Modulos\RH\Models\DispositivoAcesso;
use Modulos\RH\Repositories\MapeamentoDispositivoRepository;
use Modulos\RH\Services\ControlIdApiClient;

class VincularColaboradoresController extends BaseController
{
    public function __construct(
        private MapeamentoDispositivoRepository $mapeamentoRepo,
        private ControlIdApiClient $apiClient
    ) {
    }

    public function getIndex(Request $request)
    {
        $dispositivos = DispositivoAcesso::where('dis_status', 'ativo')->get();
        $colaboradores = Colaborador::where('col_status', 'ativo')->with('pessoa')->get();

        $dispositivo = null;
        $usuarios = [];
        $resumo = null;

        if ($request->filled('dis_id')) {
            $dispositivo = DispositivoAcesso::where('dis_status', 'ativo')->find((int) $request->get('dis_id'));

            if ($dispositivo) {
                try {
                    $response = $this->apiClient->loadObjects($dispositivo, 'users');
                    $rawUsers = $response['users'] ?? [];
                    $total = count($rawUsers);
                    $vinculados = 0;
                    $pendentes = 0;

                    foreach ($rawUsers as $user) {
                        $map = $this->mapeamentoRepo->buscarPorUserId($dispositivo->dis_id, (string) $user['user_id']);
                        $status = ($map && $map->map_col_id) ? 'vinculado' : 'pendente';

                        if ($status === 'vinculado') {
                            $vinculados++;
                            $colaborador = Colaborador::with('pessoa')->find($map->map_col_id);
                        } else {
                            $pendentes++;
                            $colaborador = null;
                        }

                        $usuarios[] = [
                            'user_id' => $user['user_id'],
                            'registration' => $user['registration'] ?? '',
                            'nome' => $user['name'] ?? 'Sem nome',
                            'col_id' => $map->map_col_id ?? null,
                            'colaborador_nome' => $colaborador ? $colaborador->pessoa->pes_nome : null,
                            'status' => $status,
                        ];
                    }

                    $resumo = ['total' => $total, 'vinculados' => $vinculados, 'pendentes' => $pendentes];
                } catch (\Exception $e) {
                    flash()->error('Erro ao consultar usuarios: ' . $e->getMessage());
                }
            }
        }

        return view('RH::vincular_colaboradores.index', compact('dispositivos', 'colaboradores', 'dispositivo', 'usuarios', 'resumo'));
    }

    public function postVincular(Request $request)
    {
        $request->validate([
            'dis_id' => 'required|integer|exists:reh_dispositivos_acesso,dis_id',
            'user_id' => 'required|string|max:20',
            'col_id' => 'required|integer|exists:reh_colaboradores,col_id',
        ]);

        $map = $this->mapeamentoRepo->buscarPorUserId((int) $request->dis_id, $request->user_id);

        if ($map) {
            $this->mapeamentoRepo->update(['map_col_id' => (int) $request->col_id, 'map_ativo' => true], $map->map_id);
            flash()->success('Colaborador vinculado com sucesso.');
        } else {
            flash()->error('Mapeamento nao encontrado. Sincronize o dispositivo primeiro.');
        }

        return redirect()->route('rh.vincularcolaboradores.index', ['dis_id' => $request->dis_id]);
    }

    public function postDesvincular(Request $request)
    {
        $request->validate([
            'dis_id' => 'required|integer|exists:reh_dispositivos_acesso,dis_id',
            'user_id' => 'required|string|max:20',
        ]);

        $map = $this->mapeamentoRepo->buscarPorUserId((int) $request->dis_id, $request->user_id);

        if ($map) {
            $this->mapeamentoRepo->update(['map_ativo' => false], $map->map_id);
            flash()->success('Usuario desvinculado com sucesso.');
        } else {
            flash()->error('Mapeamento nao encontrado.');
        }

        return redirect()->route('rh.vincularcolaboradores.index', ['dis_id' => $request->dis_id]);
    }

    public function postSincronizar(Request $request)
    {
        $request->validate(['dis_id' => 'required|integer|exists:reh_dispositivos_acesso,dis_id']);
        $dispositivo = DispositivoAcesso::findOrFail((int) $request->dis_id);

        try {
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

                if ($map && $map->map_col_id) $vinculados++;
            }

            flash()->success(sprintf('%d usuarios, %d vinculados, %d pendentes.', $total, $vinculados, $total - $vinculados));
        } catch (\Exception $e) {
            flash()->error('Falha ao sincronizar: ' . $e->getMessage());
        }

        return redirect()->route('rh.vincularcolaboradores.index', ['dis_id' => $request->dis_id]);
    }

    public function getExportarCsv($dispositivoId)
    {
        $dispositivo = DispositivoAcesso::findOrFail((int) $dispositivoId);
        $mapeamentos = $this->mapeamentoRepo->search([['map_dis_id', '=', $dispositivo->dis_id], ['map_ativo', '=', true]]);

        $conteudo = "user_id,registration,name,col_id\n";
        foreach ($mapeamentos as $map) {
            $conteudo .= sprintf("%s,%s,%s,%s\n", $map->map_user_id, $map->map_registration, $map->map_nome_dispositivo ?? '', $map->map_col_id ?? '');
        }

        return response($conteudo, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="export_' . $dispositivo->dis_identificador . '.csv"',
        ]);
    }
}
