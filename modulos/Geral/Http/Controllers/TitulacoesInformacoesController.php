<?php

namespace Modulos\Geral\Http\Controllers;

use Modulos\Geral\Http\Requests\TitulacaoInformacaoRequest;
use Modulos\Geral\Repositories\TitulacaoInformacaoRepository;
use Modulos\Geral\Repositories\TitulacaoRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Illuminate\Http\Request;

class TitulacoesInformacoesController extends BaseController
{
    protected $titulacaoInformacaoRepository;
    protected $titulacaoRepository;
    protected $pessoaRepository;

    public function __construct(TitulacaoInformacaoRepository $titulacaoInformacaoRepository, TitulacaoRepository $titulacaoRepository, PessoaRepository $pessoaRepository)
    {
        $this->titulacaoInformacaoRepository = $titulacaoInformacaoRepository;
        $this->titulacaoRepository = $titulacaoRepository;
        $this->pessoaRepository = $pessoaRepository;
    }

    public function getCreate($pessoaId, Request $request)
    {
        $url = $request->session()->get('last_acad_route');
        $id = $request->session()->get('last_id');

        $titulacoes = $this->titulacaoRepository->lists('tit_id', 'tit_nome');
        $pessoa = $this->pessoaRepository->find($pessoaId);


        if (is_null($pessoa)) {
            flash()->error('Pessoa não existe!');
            return redirect()->back();
        }

        return view('Geral::titulacoesinformacoes.create', compact('titulacoes', 'pessoa'));
    }

    public function postCreate($pessoaId, TitulacaoInformacaoRequest $request)
    {
        $url = $request->session()->get('last_acad_route');
        $id = $request->session()->get('last_id');

        try {
            $data = $request->all();
            $data['tin_pes_id'] = $pessoaId;

            $titulacao = $this->titulacaoInformacaoRepository->create($data);

            if (!$titulacao) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Titulação adicionada com sucesso.');
            return redirect()->route($url, ['id' => $id]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($titulacaoId, Request $request)
    {
        $url = $request->session()->get('last_acad_route');
        $id = $request->session()->get('last_id');

        $titulacaoInfo = $this->titulacaoInformacaoRepository->find($titulacaoId);

        if (!$titulacaoInfo) {
            flash()->error('Titulação não existe.');
            return redirect()->back();
        }

        $pessoa = $titulacaoInfo->tin_pes_id;

        $titulacoes = $this->titulacaoRepository->lists('tit_id', 'tit_nome');

        if (!$titulacaoInfo) {
            flash()->error('Titulação não existe.');
            return redirect()->back();
        }

        return view('Geral::titulacoesinformacoes.edit', ['pessoa' => $pessoa, 'titulacaoInfo' => $titulacaoInfo, 'titulacoes' => $titulacoes]);
    }

    public function putEdit($titulacaoId, TitulacaoInformacaoRequest $request)
    {
        $url = $request->session()->get('last_acad_route');
        $id = $request->session()->get('last_id');

        try {
            $titulacao = $this->titulacaoInformacaoRepository->find($titulacaoId);

            if (!$titulacao) {
                flash()->error('Titulação não existe.');
                return redirect()->back();
            }

            $requestData = $request->only($this->titulacaoInformacaoRepository->getFillableModelFields());

            if (!$this->titulacaoInformacaoRepository->update($requestData, $titulacao->tin_id, 'tin_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Titulação atualizada com sucesso.');
            return redirect()->route($url, ['id' => $id]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $titulacaoId = $request->get('id');

            if ($this->titulacaoInformacaoRepository->delete($titulacaoId)) {
                flash()->success('Titulação excluída com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir a titulação');
            }

            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
}
