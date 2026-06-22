<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Models\Colaborador;
use Modulos\Geral\Models\Pessoa;
use Modulos\RH\Services\PontoRemotoService;

class PontoRemotoApiController extends BaseController
{
    public function __construct(
        private PontoRemotoService $pontoRemotoService
    ) {
    }

    public function postEntrada(Request $request)
    {
        return $this->registrar($request, 'entrada');
    }

    public function postSaida(Request $request)
    {
        return $this->registrar($request, 'saida');
    }

    private function registrar(Request $request, string $tipo)
    {
        $rules = [
            'email' => 'required|email',
            'data_nascimento' => 'required|date_format:Y-m-d',
        ];

        if ($tipo === 'saida') {
            $rules['atividades'] = 'required|string|min:5|max:5000';
        }

        $request->validate($rules);

        $pessoa = Pessoa::where('pes_email', $request->email)->first();

        if (!$pessoa) {
            return response()->json(['error' => 'Credenciais invalidas.'], 401);
        }

        $colaborador = Colaborador::where('col_pes_id', $pessoa->pes_id)
            ->where('col_status', 'ativo')
            ->first();

        if (!$colaborador) {
            return response()->json(['error' => 'Colaborador nao encontrado.'], 404);
        }

        try {
            $evento = $this->pontoRemotoService->registrar($colaborador, $tipo, [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'canal' => 'api',
                'atividades' => $request->input('atividades'),
            ]);

            return response()->json([
                'success' => true,
                'evento_id' => $evento->eva_id,
                'status' => $evento->eva_status,
                'message' => 'Registro enviado para aprovacao.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
