<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Repositories\ConfiguracaoPontoRepository;

class ConfiguracaoPontoController extends BaseController
{
    public function __construct(
        private ConfiguracaoPontoRepository $configuracaoRepository
    ) {
    }

    public function getIndex()
    {
        $configuracoes = $this->configuracaoRepository->all();

        return view('RH::configuracao_ponto.index', compact('configuracoes'));
    }

    public function postUpdate(Request $request)
    {
        foreach ($request->input('configuracoes', []) as $chave => $valor) {
            $this->configuracaoRepository->setValor($chave, (string) $valor);
        }

        flash()->success('Configuracoes atualizadas com sucesso.');
        return redirect()->route('rh.configuracoesponto.index');
    }
}
