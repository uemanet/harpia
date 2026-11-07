<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Repositories\EventoAcessoRepository;

class EventoAcessoController extends BaseController
{
    public function __construct(
        private EventoAcessoRepository $eventoRepository
    ) {
    }

    public function getIndex(Request $request)
    {
        $eventos = $this->eventoRepository->paginateRequest($request->all());

        return view('RH::eventos_acesso.index', compact('eventos'));
    }

    public function getShow($id)
    {
        $evento = $this->eventoRepository->find($id);

        if (!$evento) {
            flash()->error('Evento nao encontrado.');
            return redirect()->route('rh.eventosacesso.index');
        }

        return view('RH::eventos_acesso.show', compact('evento'));
    }

    public function postColetarEventos()
    {
        try {
            Artisan::call('ponto:coletar-eventos');
            flash()->success('Comando de coleta de eventos executado com sucesso.');
        } catch (\Exception $e) {
            flash()->error('Erro ao executar coleta de eventos: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    public function postSincronizarHoras()
    {
        try {
            Artisan::call('ponto:sincronizar-horas-trabalhadas');
            flash()->success('Comando de sincronização de horas executado com sucesso.');
        } catch (\Exception $e) {
            flash()->error('Erro ao executar sincronização de horas: ' . $e->getMessage());
        }

        return redirect()->back();
    }
}
