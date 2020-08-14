<?php

namespace Modulos\RH\Http\Controllers;

use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Http\Requests\SalarioColaboradorRequest;
use Modulos\RH\Repositories\FontePagadoraRepository;
use Modulos\RH\Repositories\SalarioColaboradorRepository;
use Illuminate\Http\Request;
use Modulos\RH\Repositories\ContaColaboradorRepository;

class SalariosColaboradoresController extends BaseController
{
    protected $salarioColaboradorRepository;
    protected $contaColaboradorRepository;
    protected $fontePagadoraRepository;

    public function __construct(SalarioColaboradorRepository $salarioColaboradorRepository, ContaColaboradorRepository $contaColaboradorRepository, FontePagadoraRepository $fontePagadoraRepository)
    {
        $this->salarioColaboradorRepository = $salarioColaboradorRepository;
        $this->contaColaboradorRepository = $contaColaboradorRepository;
        $this->fontePagadoraRepository = $fontePagadoraRepository;

    }

    public function getCreate($idColaborador)
    {
        $colaborador = $this->contaColaboradorRepository->find($idColaborador);
        $fontes_pagadoras = $this->fontePagadoraRepository->lists('fpg_id', 'fpg_razao_social');
        $contas_colaborador = $colaborador->contas_colaboradores->pluck('ccb_id', 'ccb_conta');

        return view('RH::salarioscolaboradores.create', compact('colaborador', 'fontes_pagadoras', 'contas_colaborador'));
    }

    public function postCreate( $idColaborador, SalarioColaboradorRequest $request)
    {
        $data = $request->all();
        $data['ccb_col_id'] = $idColaborador;

        try {
            $conta_colaborador = $this->contaColaboradorRepository->create($data);

            if (!$conta_colaborador) {
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

}
