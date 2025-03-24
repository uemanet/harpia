<?php

namespace Modulos\RH\Http\Controllers;

use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Http\Requests\PeriodoAquisitivoRequest;
use Modulos\RH\Http\Requests\PeriodoGozoRequest;
use Modulos\RH\Models\MatriculaColaborador;
use Modulos\RH\Models\PeriodoGozo;
use Modulos\RH\Repositories\MatriculaColaboradorRepository;
use Modulos\RH\Repositories\PeriodoAquisitivoRepository;
use Illuminate\Http\Request;
use Modulos\RH\Repositories\ColaboradorRepository;
use Modulos\RH\Repositories\PeriodoGozoRepository;

class PeriodosGozoController extends BaseController
{
    protected $periodoAquisitivoRepository;
    protected $periodoGozoRepository;
    protected $colaboradorRepository;
    protected $matriculaColaboradorRepository;

    public function __construct(
        PeriodoAquisitivoRepository $periodoAquisitivoRepository,
        PeriodoGozoRepository $periodoGozoRepository,
        ColaboradorRepository $colaboradorRepository,
        MatriculaColaboradorRepository $matriculaColaboradorRepository
    )
    {
        $this->periodoAquisitivoRepository = $periodoAquisitivoRepository;
        $this->colaboradorRepository = $colaboradorRepository;
        $this->matriculaColaboradorRepository = $matriculaColaboradorRepository;
        $this->periodoGozoRepository = $periodoGozoRepository;
    }

    public function getCreate($idColaborador)
    {
        $matriculas = MatriculaColaborador::where('mtc_col_id', $idColaborador)
            ->pluck('mtc_data_inicio', 'mtc_id');
        $colaborador = $this->colaboradorRepository->find($idColaborador);

        // Adicionar os períodos de gozo disponíveis
        $matricula_colaborador = $this->matriculaColaboradorRepository->find($matriculas->keys()->first());
        $periodosDisponiveis = $this->periodoAquisitivoRepository->search(array(['paq_col_id', '=', $colaborador->col_id]));

        $periodos = [];
        foreach ($periodosDisponiveis as $item) {
            $periodos[$item->paq_id] = $item->paq_data_inicio . ' - ' . $item->paq_data_fim;
        }

        return view('RH::periodosgozo.create', compact('colaborador', 'matriculas', 'periodos','periodosDisponiveis'));
    }


    public function postCreate( $idColaborador, PeriodoGozoRequest $request)
    {
        $data = $request->all();
        $colaborador = $this->colaboradorRepository->find($idColaborador);

        $inicio_request = strtotime(str_replace('/', '-', $request->pgz_data_inicio));
        $fim_request = strtotime(str_replace('/', '-', $request->pgz_data_fim));
        $now = time();

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
            $periodo_aquisitivo = $this->periodoGozoRepository->create($data);

            if (!$periodo_aquisitivo) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Férias cadastradas com sucesso.');
            return redirect()->route('rh.colaboradores.show', $idColaborador);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }


    public function getEdit($periodoGozoId)
    {

        $periodoGozo = $this->periodoGozoRepository->find($periodoGozoId);
        $colaborador = $periodoGozo->periodoAquisitivo->colaborador;
        $periodosDisponiveis = $this->periodoAquisitivoRepository->search(array(['paq_col_id', '=', $colaborador->col_id]));
        $periodos = [];
        foreach ($periodosDisponiveis as $item) {
            $periodos[$item->paq_id] = $item->paq_data_inicio . ' - ' . $item->paq_data_fim;
        }

        return view('RH::periodosgozo.edit', compact('periodoGozo','colaborador',  'periodos','periodosDisponiveis'));
    }

    public function putEdit( $idPeriodoGozo, PeriodoGozoRequest $request)
    {
        $data = $request->all();
        $periodoGozo = $this->periodoGozoRepository->find($idPeriodoGozo);

        $inicio_request = strtotime(str_replace('/', '-', $request->pgz_data_inicio));
        $fim_request = strtotime(str_replace('/', '-', $request->pgz_data_fim));
        $now = time();

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
            $periodo_gozo = $this->periodoGozoRepository->update($data, $idPeriodoGozo);

            if (!$periodo_gozo) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Férias cadastradas com sucesso.');
            return redirect()->route('rh.colaboradores.show', $periodoGozo->periodoAquisitivo->colaborador->col_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }



    public function putConfirm($periodo_gozoId, Request $request)
    {
        try {
            $periodo_gozo = $this->periodoGozoRepository->find($periodo_gozoId);

            if (!$periodo_gozo) {
                flash()->error('Periodo Gozo não existe.');
                return redirect()->route('rh.colaboradores.index');
            }

            if (!$this->periodoGozoRepository->update(['pgz_ferias_gozadas' => 1], $periodo_gozo->pgz_id)) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Periodo de Gozo atualizado com sucesso.');
            return redirect()->route('rh.colaboradores.show', $periodo_gozo->periodoAquisitivo->colaborador->col_id);
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
