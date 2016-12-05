<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Academico\Http\Requests\TitulacaoInformacaoRequest;
use Modulos\Academico\Repositories\TitulacaoInformacaoRepository;
use Modulos\Academico\Repositories\TitulacaoRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Illuminate\Http\Request;

class TitulacoesInformacoesController extends BaseController
{
    protected $titulacaoInformacaoRepository;
    protected $titulacaoRepository;
    protected $pessoaRepository;
    protected $url;

    public function __construct(TitulacaoInformacaoRepository $titulacaoInformacaoRepository, TitulacaoRepository $titulacaoRepository, PessoaRepository $pessoaRepository)
    {
        $this->titulacaoInformacaoRepository = $titulacaoInformacaoRepository;
        $this->titulacaoRepository = $titulacaoRepository;
        $this->pessoaRepository = $pessoaRepository;
    }

    public function getCreate($pessoaId, Request $request)
    {

        $titulacoes = $this->titulacaoRepository->lists('tit_id', 'tit_nome');
        $pessoa = $this->pessoaRepository->find($pessoaId);


        if (is_null($pessoa)) {
            flash()->error('Pessoa não existe!');
            return redirect()->back();
        }

        return view('Academico::titulacoesinformacoes.create', compact('titulacoes', 'pessoa'));
    }

    public function postCreate($pessoaId, TitulacaoInformacaoRequest $request)
    {
        try {

            $data = $request->all();
            $data['tin_pes_id'] = $pessoaId;

            $titulacao = $this->titulacaoInformacaoRepository->create($data);



            if (!$titulacao) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Titulação adicionada com sucesso.');
            return redirect()->back();

        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($titulacaoId)
    {
        $titulacaoInfo = $this->titulacaoInformacaoRepository->find($titulacaoId);
        $pessoa = $titulacaoInfo->tin_pes_id;

        $titulacoes = $this->titulacaoRepository->lists('tit_id', 'tit_nome');

        if (!$titulacaoInfo) {
            flash()->error('Titulação não existe.');
            return redirect()->back();
        }

        return view('Academico::titulacoesinformacoes.edit', ['pessoa' => $pessoa,'titulacaoInfo' => $titulacaoInfo, 'titulacoes' => $titulacoes]);
    }

    public function putEdit($titulacaoId, TitulacaoInformacaoRequest $request)
    {
        try {
            $titulacao = $this->titulacaoInformacaoRepository->find($titulacaoId);

            if (!$titulacao) {
                flash()->error('Titulação não existe.');
                return redirect('academico/titulacoesinformacoes/index');
            }

            $requestData = $request->only($this->titulacaoInformacaoRepository->getFillableModelFields());

            if (!$this->titulacaoInformacaoRepository->update($requestData, $titulacao->tin_id, 'tin_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Titulação atualizada com sucesso.');
            return redirect()->back();
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