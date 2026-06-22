<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Models\DispositivoAcesso;
use Modulos\RH\Services\EventoAcessoService;

class MonitorController extends BaseController
{
    public function __construct(
        private EventoAcessoService $eventoAcessoService
    ) {
    }

    public function receberLogAcesso(Request $request): JsonResponse
    {
        $dispositivo = $this->dispositivo($request);
        $disparados = 0;

        foreach ($request->input('object_changes', []) as $objectChange) {
            if (($objectChange['object'] ?? null) !== 'access_logs') continue;
            if (($objectChange['type'] ?? null) !== 'inserted') continue;

            $this->eventoAcessoService->processarEventoMonitor(
                $dispositivo,
                $objectChange['values'] ?? [],
                ['ip' => $request->ip(), 'user_agent' => (string) $request->userAgent()]
            );
            $disparados++;
        }

        return response()->json(['success' => true, 'queued' => $disparados]);
    }

    public function receberNotificacao(string $action, Request $request): JsonResponse
    {
        $dispositivo = $this->dispositivo($request);
        \Log::info('Notificacao auxiliar do Control iD', [
            'action' => $action,
            'dispositivo' => $dispositivo->dis_id,
            'payload' => $request->all(),
        ]);

        return response()->json(['success' => true, 'type' => $action]);
    }

    private function dispositivo(Request $request): DispositivoAcesso
    {
        return $request->attributes->get('dispositivo')
            ?? $request->attributes->get('dispositivoAcesso')
            ?? $request->attributes->get('dispositivo_acesso');
    }
}
