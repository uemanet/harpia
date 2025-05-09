<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\Colaborador;
use Modulos\RH\Models\ColaboradorFuncao;
use Modulos\RH\Models\HoraTrabalhada;
use DB;
use Modulos\RH\Models\PeriodoLaboral;

class HoraTrabalhadaRepository extends BaseRepository
{
    public function __construct(HoraTrabalhada $horaTrabalhada)
    {
        $this->model = $horaTrabalhada;
    }

    public function sincronizarHorasTrabalhadas(PeriodoLaboral $periodoLaboral){

        $colaboradores = Colaborador::where('col_status', 'ativo')->get();
        foreach ($colaboradores as $colaborador){
            $colaboradorHoraTrabalhada = $this->model
                ->where([
                    'htr_col_id' => $colaborador->col_id,
                    'htr_pel_id' => $periodoLaboral->pel_id])->first();
            if($colaboradorHoraTrabalhada){
                $horasTrabalhadas = $this->calculaDadosDeHorasTrabalhadasDoColaborador($colaborador, $periodoLaboral);

                $horasJustificadas = $this->buscaHorasJustificadasDoPeriodo($colaboradorHoraTrabalhada);
                $horasTrabalhadas['htr_horas_justificadas'] =   str_pad($horasJustificadas, 2, '0', STR_PAD_LEFT).':00:00';
                $horasTrabalhadas['htr_saldo'] = $this->calculaSaldo(
                    $horasTrabalhadas['htr_horas_trabalhadas'],
                    $horasTrabalhadas['htr_horas_previstas'],
                    $horasTrabalhadas['htr_horas_justificadas']
                );
                $colaboradorHoraTrabalhada->update($horasTrabalhadas);
            } else{
                $horasTrabalhadas = $this->calculaDadosDeHorasTrabalhadasDoColaborador($colaborador, $periodoLaboral);
                $horasTrabalhadas['htr_horas_justificadas'] = '00:00:00';
                $this->create($horasTrabalhadas);
            }
        }

        return true;
    }

    public function sincronizarJustificativa(HoraTrabalhada $horaTrabalhada){

        $horasJustificadas = $this->buscaHorasJustificadasDoPeriodo($horaTrabalhada);

        $horasTrabalhadas = $this->calculaDadosDeHorasTrabalhadasDoColaborador(
            $horaTrabalhada->colaborador, $horaTrabalhada->periodo);

        $horasTrabalhadas['htr_horas_justificadas'] =
            str_pad($horasJustificadas, 2, '0', STR_PAD_LEFT).':00:00';

        $horasTrabalhadas['htr_saldo'] = $this->calculaSaldo(
            $horasTrabalhadas['htr_horas_trabalhadas'],
            $horasTrabalhadas['htr_horas_previstas'],
            $horasTrabalhadas['htr_horas_justificadas']
        );

        $horaTrabalhada->update($horasTrabalhadas);
    }

    public function buscaHorasJustificadasDoPeriodo(HoraTrabalhada $horaTrabalhada){
        return array_sum( array_map(function ($justificativa): string {
            return $justificativa['jus_horas'];
        }, $horaTrabalhada->justificativas->toArray()));
    }

    public function calculaDadosDeHorasTrabalhadasDoColaborador(Colaborador $colaborador, PeriodoLaboral $periodoLaboral){

        $diasUteis = $this->getWorkdays(
            $periodoLaboral->getRawOriginal('pel_inicio'),
            $periodoLaboral->getRawOriginal('pel_termino')
        );

        $horasPrevistas = $diasUteis*$colaborador->col_ch_diaria.':00:00';
        $horasTrabalhadas = $this->sumTimes(array_map(function ($horaDiaria): string {
            return $horaDiaria->htd_horas;
        }, $this->buscaHorasTrabalhadasNoPeriodoLaboral(
            $periodoLaboral->getRawOriginal('pel_inicio'),
            $periodoLaboral->getRawOriginal('pel_termino'),
            $colaborador->col_id
        )));

        $saldo = $this->calculaSaldo($horasTrabalhadas, $horasPrevistas);

        return [
            'htr_col_id' => $colaborador->col_id,
            'htr_pel_id' => $periodoLaboral->pel_id,
            'htr_horas_previstas' => $horasPrevistas,
            'htr_horas_trabalhadas' => $horasTrabalhadas,
            'htr_saldo' => $saldo
        ];

    }

    public function buscaHorasTrabalhadasNoPeriodoLaboral($pelInicio, $pelFim, $colId){
        $select = DB::table('reh_horas_trabalhadas_diarias')
            ->where('htd_data', '>=', $pelInicio)
            ->where('htd_data', '<=', $pelFim)
            ->where('htd_col_id', $colId)
            ->get();

        return$select->toArray();
    }

    public function sumTimes(array $times): string
    {
        $sumSeconds = 0;
        foreach($times as $time) {
            $explodedTime = explode(':', $time);
            $seconds = $explodedTime[0]*3600+$explodedTime[1]*60+$explodedTime[2];

            $sumSeconds += $seconds;
        }
        $hours = floor($sumSeconds/3600);
        $minutes = floor(($sumSeconds % 3600)/60);
        $seconds = (($sumSeconds%3600)%60);
        return
            str_pad($hours, 2, '0', STR_PAD_LEFT).':' .
            str_pad(abs($minutes), 2, '0', STR_PAD_LEFT).':'.
            str_pad(abs($seconds), 2, '0', STR_PAD_LEFT);
    }


    public function calculaSaldo($horasTrabalhadas, $horasPrevistas, $horasJustificadas = null): string
    {
        $explodedTime1 = explode(':', $horasTrabalhadas);
        $seconds1 = $explodedTime1[0]*3600+$explodedTime1[1]*60+$explodedTime1[2];
        $explodedTime2 = explode(':', $horasPrevistas);
        $seconds2 = $explodedTime2[0]*3600+$explodedTime2[1]*60+$explodedTime2[2];

        $seconds3 = null;
        if($horasJustificadas){
            $explodedTime3 = explode(':', $horasJustificadas);
            $seconds3 = $explodedTime3[0]*3600+$explodedTime3[1]*60+$explodedTime3[2];
        }

        $secondsDiff = $seconds1-$seconds2+$seconds3;

        $hours = floor($secondsDiff/3600);
        $minutes = abs(floor(($secondsDiff % 3600)/60));
        $seconds = abs((($secondsDiff%3600)%60));
        return
            str_pad($hours, 2, '0', STR_PAD_LEFT).':' .
            str_pad($minutes, 2, '0', STR_PAD_LEFT).':'.
            str_pad($seconds, 2, '0', STR_PAD_LEFT);
    }

    function getWorkdays($date1, $date2, $workSat = FALSE, $patron = NULL) {
        if (!defined('SATURDAY')) define('SATURDAY', 6);
        if (!defined('SUNDAY')) define('SUNDAY', 0);

        $publicHolidays = DB::table('reh_calendarios')->where('cld_data', '>=', $date1)->where('cld_data', '<=', $date2)->get();

        $publicHolidays = array_map(function ($publicHoliday): string {
            return date('m-d',strtotime($publicHoliday->cld_data));
        }, $publicHolidays->toArray() );


        if ($patron) {
            $publicHolidays[] = $patron;
        }


        $yearStart = date('Y', strtotime($date1));
        $yearEnd   = date('Y', strtotime($date2));

        for ($i = $yearStart; $i <= $yearEnd; $i++) {
            $easter = date('Y-m-d', easter_date($i));
            list($y, $m, $g) = explode("-", $easter);
            $monday = mktime(0,0,0, date($m), date($g)+1, date($y));
            $easterMondays[] = $monday;
        }

        $start = strtotime($date1);
        $end   = strtotime($date2);
        $workdays = 0;
        for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
            $day = date("w", $i);  // 0=sun, 1=mon, ..., 6=sat
            $mmgg = date('m-d', $i);
            if ($day != SUNDAY &&
                !in_array($mmgg, $publicHolidays) &&
                !in_array($i, $easterMondays) &&
                !($day == SATURDAY && $workSat == FALSE)) {
                $workdays++;
            }
        }

        return intval($workdays);
    }


    public function paginate($sort = null, $search = null)
    {
        $result = $this->model->join('reh_colaboradores', function ($join) {
            $join->on('htr_col_id', '=', 'col_id');
        })->leftJoin('reh_colaboradores_funcoes', function ($join) {
            $join->on('col_id', '=', 'cfn_col_id');
        })->leftJoin('gra_pessoas', function ($join) {
            $join->on('reh_colaboradores.col_pes_id', '=', 'gra_pessoas.pes_id');
        })->leftJoin('reh_setores', function ($join) {
            $join->on('reh_colaboradores_funcoes.cfn_set_id', '=', 'reh_setores.set_id');
        })->where('cfn_data_fim', null)->groupBy('col_id');

        if (!empty($search)) {
            foreach ($search as $value) {
                if ($value['field'] == 'cfn_set_id') {
                    if (!empty($value['term']) && is_array($value['term'])) {
                        $result = $result->whereIn('cfn_set_id', $value['term'])->where('cfn_data_fim', null);
                    } else {
                        $result = $result->where('cfn_set_id', $value['term'])->where('cfn_data_fim', null);
                    }
                    continue;
                }

                if ($value['field'] == 'col_pes_id') {
                    if (!empty($value['term']) && is_array($value['term'])) {
                        $result = $result->whereIn('col_id', $value['term']);
                    }
                    continue;
                }

                switch ($value['type']) {
                    case 'like':
                        $result = $result->where($value['field'], $value['type'], "%{$value['term']}%");
                        break;
                    default:
                        $result = $result->where($value['field'], $value['type'], $value['term']);
                }
            }
        }

        if (!empty($sort)) {
            if ($sort['field'] === 'htr_col_id') {
                $result = $result->orderBy('gra_pessoas.pes_nome', $sort['sort']);
            } elseif ($sort['field'] === 'htr_saldo') {
                // Converte o formato HH:MM:SS para segundos para ordenação
                $result = $result->orderByRaw("
            CASE 
                WHEN htr_saldo LIKE '-%' 
                THEN -TIME_TO_SEC(SUBSTRING(htr_saldo, 2))
                ELSE TIME_TO_SEC(htr_saldo)
            END " . $sort['sort']);
            } elseif ($sort['field'] === 'htr_setor') {
                // Ordena pelo nome do setor
                $result = $result->orderBy('reh_setores.set_descricao', $sort['sort']);
            } else {
                $result = $result->orderBy($sort['field'], $sort['sort']);
            }
        }

        return $result->paginate(15);
    }
}
