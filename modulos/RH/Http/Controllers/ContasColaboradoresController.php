<?php

namespace Modulos\RH\Http\Controllers;

use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Http\Requests\ContaColaboradorRequest;
use Modulos\RH\Repositories\BancoRepository;
use Modulos\RH\Repositories\ContaColaboradorRepository;
use Illuminate\Http\Request;
use Modulos\RH\Repositories\ColaboradorRepository;

class ContasColaboradoresController extends BaseController
{
    protected $contaColaboradorRepository;
    protected $colaboradorRepository;
    protected $bancoRepository;

    public function __construct(ContaColaboradorRepository $contaColaboradorRepository, ColaboradorRepository $colaboradorRepository, BancoRepository $bancoRepository)
    {
        $this->contaColaboradorRepository = $contaColaboradorRepository;
        $this->bancoRepository = $bancoRepository;
        $this->colaboradorRepository = $colaboradorRepository;

    }

    public function getCreate($idColaborador)
    {

        $colaborador = $this->colaboradorRepository->find($idColaborador);
        $bancos = $this->bancoRepository->lists('ban_id', 'ban_nome');

        return view('RH::contascolaboradores.create', compact('colaborador', 'bancos'));
    }

    public function postCreate( $idColaborador, ContaColaboradorRequest $request)
    {
        $data = $request->all();
        $data['ccb_col_id'] = $idColaborador;

        try {
            $conta_colaborador = $this->contaColaboradorRepository->create($data);

            if (!$conta_colaborador) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Conta de Colaborador criada com sucesso.');
            return redirect()->route('rh.colaboradores.show', $idColaborador);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($conta_colaboradorId)
    {
        $conta_colaborador = $this->contaColaboradorRepository->find($conta_colaboradorId);
        $bancos = $this->bancoRepository->lists('ban_id', 'ban_nome');

        if (!$conta_colaborador) {
            flash()->error('Conta de Colaborador não existe.');
            return redirect()->back();
        }

        return view('RH::contascolaboradores.edit', compact('conta_colaborador', 'bancos'));
    }

    public function putEdit($conta_colaboradorId, ContaColaboradorRequest $request)
    {
        try {
            $conta_colaborador = $this->contaColaboradorRepository->find($conta_colaboradorId);


            if (!$conta_colaborador) {
                flash()->error('Conta de Colaborador não existe.');
                return redirect()->route('rh.colaboradores.index');
            }
            $requestData = $request->all();
            if (!$this->contaColaboradorRepository->update($requestData, $conta_colaborador->ccb_id)) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Conta de Colaborador atualizada com sucesso.');
            return redirect()->route('rh.colaboradores.show', $conta_colaborador->ccb_col_id);
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
            $conta_colaboradorId = $request->get('id');

            $this->contaColaboradorRepository->delete($conta_colaboradorId);

            flash()->success('Conta de Colaborador excluída com sucesso.');

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
