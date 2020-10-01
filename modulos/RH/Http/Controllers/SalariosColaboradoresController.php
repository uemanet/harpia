<?php

namespace Modulos\RH\Http\Controllers;

use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Http\Requests\SalarioColaboradorRequest;
use Modulos\RH\Repositories\ColaboradorRepository;
use Modulos\RH\Repositories\FontePagadoraRepository;
use Modulos\RH\Repositories\SalarioColaboradorRepository;
use Harpia\Util\Util;
use Illuminate\Http\Request;
use Modulos\RH\Repositories\ContaColaboradorRepository;

class SalariosColaboradoresController extends BaseController
{
    protected $salarioColaboradorRepository;
    protected $contaColaboradorRepository;
    protected $fontePagadoraRepository;
    protected $colaboradorRepository;

    public function __construct(SalarioColaboradorRepository $salarioColaboradorRepository, ContaColaboradorRepository $contaColaboradorRepository, FontePagadoraRepository $fontePagadoraRepository, ColaboradorRepository $colaboradorRepository)
    {
        $this->salarioColaboradorRepository = $salarioColaboradorRepository;
        $this->contaColaboradorRepository = $contaColaboradorRepository;
        $this->fontePagadoraRepository = $fontePagadoraRepository;
        $this->colaboradorRepository = $colaboradorRepository;

    }

    public function getCreate($idColaborador)
    {
        $colaborador = $this->colaboradorRepository->find($idColaborador);
        $fontes_pagadoras = $this->fontePagadoraRepository->lists('fpg_id', 'fpg_razao_social');
        $contas_colaborador = $colaborador->contas_colaboradores->pluck('ccb_conta', 'ccb_id');

        return view('RH::salarioscolaboradores.create', compact('colaborador', 'fontes_pagadoras', 'contas_colaborador'));
    }


    public function postCreate($idColaborador, SalarioColaboradorRequest $request)
    {
        $data = $request->all();
        $util = new Util();
        $data['scb_data_cadastro'] = date("d/m/Y");

        try {
            $salario_colaborador = $this->salarioColaboradorRepository->create($data);

            if (!$salario_colaborador) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Salário de Colaborador criada com sucesso.');
            return redirect()->route('rh.colaboradores.show', $idColaborador);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }


    public function getEdit($salario_colaboradorId)
    {
        $salario = $this->salarioColaboradorRepository->find($salario_colaboradorId);

        if (!$salario) {
            flash()->error('Salário de Colaborador não existe.');
            return redirect()->back();
        }

        $colaborador = $salario->conta->colaborador;
        $fontes_pagadoras = $this->fontePagadoraRepository->lists('fpg_id', 'fpg_razao_social');
        $contas_colaborador = $colaborador->contas_colaboradores->pluck('ccb_conta', 'ccb_id');

        return view('RH::salarioscolaboradores.edit', compact('colaborador', 'fontes_pagadoras', 'contas_colaborador', 'salario'));
    }

    public function putEdit($salario_colaboradorId, SalarioColaboradorRequest $request)
    {
        try {
            $salario_colaborador = $this->salarioColaboradorRepository->find($salario_colaboradorId);


            if (!$salario_colaborador) {
                flash()->error('Salário de Colaborador não existe.');
                return redirect()->route('rh.colaboradores.index');
            }
            $requestData = $request->all();
            if (!$this->salarioColaboradorRepository->update($requestData, $salario_colaborador->scb_id)) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Salário de Colaborador atualizada com sucesso.');
            return redirect()->route('rh.colaboradores.show', $salario_colaborador->conta->colaborador->col_id);
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
            $salario_colaboradorId = $request->get('id');

            $this->salarioColaboradorRepository->delete($salario_colaboradorId);
            
            flash()->success('Salário de Colaborador excluído com sucesso.');

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

