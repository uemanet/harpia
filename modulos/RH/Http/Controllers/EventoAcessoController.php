<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Http\Request;
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
}
