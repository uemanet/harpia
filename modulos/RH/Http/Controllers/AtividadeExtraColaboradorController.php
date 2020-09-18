<?php

namespace Modulos\RH\Http\Controllers;

use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Http\Requests\AtividadeExtraColaboradorRequest;
use Modulos\RH\Repositories\AtividadeExtraColaboradorRepository;
use Illuminate\Http\Request;
use Modulos\RH\Repositories\ColaboradorRepository;

class AtividadeExtraColaboradorController extends BaseController
{
    protected $atividadeColaboradorRepository;
    protected $colaboradorRepository;

    public function __construct(AtividadeExtraColaboradorRepository $atividadeColaboradorRepository, ColaboradorRepository $colaboradorRepository)
    {
        $this->atividadeColaboradorRepository = $atividadeColaboradorRepository;
        $this->colaboradorRepository = $colaboradorRepository;

    }

    public function getCreate($idColaborador)
    {

        $colaborador = $this->colaboradorRepository->find($idColaborador);

        return view('RH::atividadesextrascolaboradores.create', compact('colaborador'));
    }

    public function postCreate( $idColaborador, AtividadeExtraColaboradorRequest $request)
    {
        $data = $request->all();
        $data['atc_col_id'] = $idColaborador;

        try {
            $atividade_extra = $this->atividadeColaboradorRepository->create($data);

            if (!$atividade_extra) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Atividade Extra criada com sucesso.');
            return redirect()->route('rh.colaboradores.show', $idColaborador);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($atividade_extraId)
    {
        $atividade_extra = $this->atividadeColaboradorRepository->find($atividade_extraId);

        if (!$atividade_extra) {
            flash()->error('Atividade Extra não existe.');
            return redirect()->back();
        }

        return view('RH::atividadesextrascolaboradores.edit', compact('atividade_extra'));
    }

    public function putEdit($atividade_extraId, AtividadeExtraColaboradorRequest $request)
    {
        try {
            $atividade_extra = $this->atividadeColaboradorRepository->find($atividade_extraId);

            if (!$atividade_extra) {
                flash()->error('Atividade Extra não existe.');
                return redirect()->route('rh.colaboradores.index');
            }

            $requestData = $request->only($this->atividadeColaboradorRepository->getFillableModelFields());

            if (!$this->atividadeColaboradorRepository->update($requestData, $atividade_extra->atc_id)) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Atividade Extra atualizada com sucesso.');
            return redirect()->route('rh.colaboradores.show', $atividade_extra->atc_col_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $atividade_extraId = $request->get('id');

            $this->atividadeColaboradorRepository->delete($atividade_extraId);

            flash()->success('Atividade Extra excluída com sucesso.');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. O recurso contém dependências no sistema.');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

}
