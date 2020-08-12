<?php

namespace Modulos\RH\Http\Controllers;

use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Http\Requests\VinculoFontePagadoraRequest;
use Modulos\RH\Models\VinculoFontePagadora;
use Modulos\RH\Repositories\VinculoRepository;
use Modulos\RH\Repositories\VinculoFontePagadoraRepository;
use Illuminate\Http\Request;
use Modulos\RH\Repositories\FontePagadoraRepository;

class VinculosFontesPagadorasController extends BaseController
{
    protected $vinculoFpgRepository;
    protected $fontePagadoraRepository;
    protected $vinculoRepository;

    public function __construct(VinculoFontePagadoraRepository $vinculoFpgRepository, FontePagadoraRepository $fontePagadoraRepository, VinculoRepository $vinculoRepository)
    {
        $this->vinculoFpgRepository = $vinculoFpgRepository;
        $this->vinculoRepository = $vinculoRepository;
        $this->fontePagadoraRepository = $fontePagadoraRepository;

    }

    public function getCreate($idFontePagadora)
    {

        $fonte_pagadora = $this->fontePagadoraRepository->find($idFontePagadora);
        $vinculos = $this->vinculoRepository->lists('vin_id', 'vin_descricao');

        return view('RH::vinculosfontespagadoras.create', compact('fonte_pagadora', 'vinculos'));
    }

    public function postCreate($idFontePagadora, VinculoFontePagadoraRequest $request)
    {
        $data = $request->except('_token');

        try {

            $vinculo_fpg = new VinculoFontePagadora();

            $vinculo_fpg->vfp_fpg_id = $data['vfp_fpg_id'];
            $vinculo_fpg->vfp_vin_id = $data['vfp_vin_id'];
            $vinculo_fpg->vfp_unidade =isset($data['vfp_unidade']) ? isset($data['vfp_unidade']) : null ;
            $vinculo_fpg->vfp_valor = isset($data['vfp_valor']) ? $data['vfp_valor'] : null;

            $vinculo_fpg->save();

            if (!$vinculo_fpg) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Salário Base criada com sucesso.');
            return redirect()->route('rh.fontespagadoras.show', $idFontePagadora);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($vinculo_FpgId)
    {
        $vinculo_fpg = $this->vinculoFpgRepository->find($vinculo_FpgId);
        $vinculos = $this->vinculoRepository->lists('vin_id', 'vin_descricao');

        if (!$vinculo_fpg) {
            flash()->error('Salário Base não existe.');
            return redirect()->back();
        }

        return view('RH::vinculosfontespagadoras.edit', compact('vinculo_fpg', 'vinculos'));
    }

    public function putEdit($vinculo_FpgId, VinculoFontePagadoraRequest $request)
    {
        try {
            $vinculo_fpg = $this->vinculoFpgRepository->find($vinculo_FpgId);


            if (!$vinculo_fpg) {
                flash()->error('Salário Base não existe.');
                return redirect()->route('rh.fontespagadoras.index');
            }
            $requestData = $request->all();
            if (!$this->vinculoFpgRepository->update($requestData, $vinculo_fpg->vfp_id)) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Salário Base atualizado com sucesso.');
            return redirect()->route('rh.fontespagadoras.show', $vinculo_fpg->vfp_fpg_id);
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
            $vinculo_FpgId = $request->get('id');

            $this->vinculoFpgRepository->delete($vinculo_FpgId);

            flash()->success('Salário Base excluída com sucesso.');

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
