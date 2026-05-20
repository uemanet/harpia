<?php

namespace Modulos\RH\Http\Controllers;

use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Models\DispositivoAcesso;
use Modulos\RH\Services\ControlIdApiClient;
use Illuminate\Http\Request;

class TesteDispositivoController extends BaseController
{
    private ControlIdApiClient $apiClient;

    public function __construct(ControlIdApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function getIndex()
    {
        $dispositivos = DispositivoAcesso::where('dis_status', 'ativo')->get();

        return view('RH::teste_dispositivo.index', compact('dispositivos'));
    }

    public function postPing(Request $request)
    {
        $dispositivo = DispositivoAcesso::findOrFail($request->input('dis_id'));

        try {
            $resultado = $this->apiClient->ping($dispositivo);
            flash()->success('Ping realizado com sucesso.');

            return redirect()->back()->with('resultado', $resultado);
        } catch (\Exception $e) {
            flash()->error('Erro no ping: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function postListarUsuarios(Request $request)
    {
        $dispositivo = DispositivoAcesso::findOrFail($request->input('dis_id'));

        try {
            $resultado = $this->apiClient->loadObjects($dispositivo, 'users');
            flash()->success('Usuarios listados com sucesso.');

            return redirect()->back()->with('resultado', $resultado);
        } catch (\Exception $e) {
            flash()->error('Erro ao listar usuarios: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function postConsultarLogs(Request $request)
    {
        $dispositivo = DispositivoAcesso::findOrFail($request->input('dis_id'));

        try {
            $resultado = $this->apiClient->loadObjects($dispositivo, 'access_logs');
            flash()->success('Logs consultados com sucesso.');

            return redirect()->back()->with('resultado', $resultado);
        } catch (\Exception $e) {
            flash()->error('Erro ao consultar logs: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function getSystemInfo(Request $request)
    {
        $dispositivo = DispositivoAcesso::findOrFail($request->input('dis_id'));

        try {
            $resultado = $this->apiClient->getSystemInfo($dispositivo);
            flash()->success('Informacoes do sistema obtidas.');

            return redirect()->back()->with('resultado', $resultado);
        } catch (\Exception $e) {
            flash()->error('Erro ao obter informacoes: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
