<?php

namespace Modulos\RH\Http\Controllers;

use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Http\Requests\PeriodoAquisitivoRequest;
use Modulos\RH\Http\Requests\PeriodoGozoRequest;
use Modulos\RH\Models\MatriculaColaborador;
use Modulos\RH\Repositories\MatriculaColaboradorRepository;
use Modulos\RH\Repositories\PeriodoAquisitivoRepository;
use Illuminate\Http\Request;
use Modulos\RH\Repositories\ColaboradorRepository;

class PeriodosAquisitivosController extends BaseController
{
    protected $periodoAquisitivoRepository;
    protected $colaboradorRepository;
    protected $matriculaColaboradorRepository;

    public function __construct(
        PeriodoAquisitivoRepository $periodoAquisitivoRepository,
        ColaboradorRepository $colaboradorRepository,
        MatriculaColaboradorRepository $matriculaColaboradorRepository
    )
    {
        $this->periodoAquisitivoRepository = $periodoAquisitivoRepository;
        $this->colaboradorRepository = $colaboradorRepository;
        $this->matriculaColaboradorRepository = $matriculaColaboradorRepository;

    }

    public function getCreate($idColaborador)
    {

        $matriculas = MatriculaColaborador::where('mtc_col_id', $idColaborador)
            ->pluck('mtc_data_inicio', 'mtc_id');
        $colaborador = $this->colaboradorRepository->find($idColaborador);

        // Adicionar os períodos de gozo disponíveis
        $matricula_colaborador = $this->matriculaColaboradorRepository->find($matriculas->keys()->first());
        $periodosDisponiveis = $this->periodoAquisitivoRepository->periodData($matricula_colaborador);

        return view('RH::periodosaquisitivos.create', compact('colaborador', 'matriculas', 'periodosDisponiveis'));
    }

    public function postCreate( $idColaborador, PeriodoGozoRequest $request)
    {
        $data = $request->all();
        $colaborador = $this->colaboradorRepository->find($idColaborador);


        dd($data);

        $inicio_request = strtotime(str_replace('/', '-', $request->paq_data_inicio));
        $fim_request = strtotime(str_replace('/', '-', $request->paq_data_fim));
        $now = time();

//        $isInPeriodDate = $this->periodoAquisitivoRepository->isInPeriodData($matricula_colaborador, $inicio_request);
//        if (!$isInPeriodDate) {
//            flash()->error('O início das férias do colaborador deve pertencer a algum dos períodos aquisitivos');
//            return redirect()->back()->withInput($request->all());
//        }

        // Captura os valores selecionados do período de gozo
        if ($request->filled('paq_periodo_aquisitivo')) {
            [$data['paq_gozo_inicio'], $data['paq_gozo_fim']] = explode('|', $request->paq_periodo_aquisitivo);
        }

        if ($inicio_request > $fim_request) {
            flash()->error('A data de fim deve ser maior que a data de início');
            return redirect()->back()->withInput($request->all());
        }

        $monthInSeconds = 30*24*60*60;
        if ($fim_request - $inicio_request >= $monthInSeconds) {
            flash()->error('Não deve ser possível cadastrar um período maior que 30 dias');
            return redirect()->back()->withInput($request->all());
        }

        try {
            $periodo_aquisitivo = $this->periodoAquisitivoRepository->create($data);

            if (!$periodo_aquisitivo) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Periodo Aquisitivo criado com sucesso.');
            return redirect()->route('rh.colaboradores.show', $idColaborador);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($periodo_aquisitivoId)
    {
        $periodo_aquisitivo = $this->periodoAquisitivoRepository->find($periodo_aquisitivoId);

        $matriculas = MatriculaColaborador::where('mtc_col_id', $periodo_aquisitivo->paq_col_id)
            ->pluck('mtc_data_inicio', 'mtc_id');

        if (!$periodo_aquisitivo) {
            flash()->error('Periodo Aquisitivo não existe.');
            return redirect()->back();
        }

        $matricula_colaborador = $this->matriculaColaboradorRepository->find($matriculas->keys()->first());
        $periodosDisponiveis = $this->periodoAquisitivoRepository->periodData($matricula_colaborador);

        return view('RH::periodosaquisitivos.edit', compact('periodo_aquisitivo', 'matriculas','periodosDisponiveis'));
    }

    public function putEdit($periodo_aquisitivoId, PeriodoAquisitivoRequest $request)
    {
        try {
            $periodo_aquisitivo = $this->periodoAquisitivoRepository->find($periodo_aquisitivoId);

            if (!$periodo_aquisitivo) {
                flash()->error('Periodo Aquisitivo não existe.');
                return redirect()->route('rh.colaboradores.index');
            }

            $matricula_colaborador = $this->matriculaColaboradorRepository->find($periodo_aquisitivo->paq_mtc_id);

            $inicio_request = strtotime(str_replace('/', '-', $request->paq_data_inicio));
            $fim_request = strtotime(str_replace('/', '-', $request->paq_data_fim));
            $now = time();

//            $isInPeriodDate = $this->periodoAquisitivoRepository->isInPeriodData($matricula_colaborador, $inicio_request);
//            if (!$isInPeriodDate) {
//                flash()->error('O início das férias do colaborador deve pertencer a algum dos períodos aquisitivos');
//                return redirect()->back()->withInput($request->all());
//            }

            if ($inicio_request > $fim_request) {
                flash()->error('A data de fim deve ser maior que a data de início');
                return redirect()->back()->withInput($request->all());
            }

            $monthInSeconds = 30*24*60*60;
            if ($fim_request - $inicio_request >= $monthInSeconds) {
                flash()->error('Não deve ser possível cadastrar um período maior que 30 dias');
                return redirect()->back()->withInput($request->all());
            }

            if ($request->filled('paq_periodo_aquisitivo')) {
                [$paq_gozo_inicio, $paq_gozo_fim] = explode('|', $request->paq_periodo_aquisitivo);
                $data['paq_gozo_inicio'] = $paq_gozo_inicio;
                $data['paq_gozo_fim'] = $paq_gozo_fim;
            }

            $requestData = $request->only($this->periodoAquisitivoRepository->getFillableModelFields());

            // Adiciona os campos de gozo ao requestData
            $requestData = array_merge($requestData, $data);

            if (!$this->periodoAquisitivoRepository->update($requestData, $periodo_aquisitivo->paq_id)) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Periodo Aquisitivo atualizado com sucesso.');
            return redirect()->route('rh.colaboradores.show', $periodo_aquisitivo->paq_col_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function putConfirm($periodo_aquisitivoId, Request $request)
    {
        try {
            $periodo_aquisitivo = $this->periodoAquisitivoRepository->find($periodo_aquisitivoId);

            if (!$periodo_aquisitivo) {
                flash()->error('Periodo Aquisitivo não existe.');
                return redirect()->route('rh.colaboradores.index');
            }

            if (!$this->periodoAquisitivoRepository->update(['paq_ferias_gozadas' => 1], $periodo_aquisitivo->paq_id)) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Periodo Aquisitivo atualizado com sucesso.');
            return redirect()->route('rh.colaboradores.show', $periodo_aquisitivo->paq_col_id);
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
            $periodo_aquisitivoId = $request->get('id');

            $this->periodoAquisitivoRepository->delete($periodo_aquisitivoId);

            flash()->success('Periodo Aquisitivo excluída com sucesso.');

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
