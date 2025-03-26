<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\Colaborador;
use Modulos\RH\Models\MatriculaColaborador;
use Modulos\RH\Models\PeriodoAquisitivo;
use Modulos\RH\Models\PeriodoGozo;
use Carbon\Carbon;

class PeriodoAquisitivoRepository extends BaseRepository
{
    public function __construct(PeriodoAquisitivo $periodo_aquisitivo, PeriodoGozo $periodo_gozo)
    {
        $this->model = $periodo_aquisitivo;
        $this->modelPeriodoGozo = $periodo_gozo;
    }

    /**
     * Return data about Acquirer Periods.
     *
     * @param MatriculaColaborador $matricula_colaborador
     * @return array|int
     */

    public function generatePeriodData(MatriculaColaborador $matricula_colaborador):array
    {

        $primeiro_periodo_adquirido = $this->getDate('+1 year', $matricula_colaborador->getRawOriginal('mtc_data_inicio'));
        if(time() <  strtotime($primeiro_periodo_adquirido)){
            return [];
        }

        if( $matricula_colaborador->mtc_data_fim and
            strtotime($primeiro_periodo_adquirido) > strtotime($matricula_colaborador->getRawOriginal('mtc_data_fim')) ){
            return [];
        }

        $returnData = [];

        $inicioPeriodo = $this->getDate('+0 year', $matricula_colaborador->getRawOriginal('mtc_data_inicio'));
        $inicioPeriodoAdquirido = $inicioPeriodo;
        $countYear = 0;
        $ano_valido = true;
        while ($ano_valido){

            $count = $countYear? 1 : 0;
            $inicioPeriodoAdquirido = $this->getDate('+'.$count.' year', $inicioPeriodoAdquirido);
            $fimPeriodoAdquirido = $this->getDate('1 year', $inicioPeriodoAdquirido);

            $inicioPeriodo = $this->getDate('+1 year', $inicioPeriodo);
            $finalPeriodo = $this->getDate('+1 year', $inicioPeriodo);

            $data['inicio_adquirido'] = date('d/m/Y',strtotime($inicioPeriodoAdquirido));
            $data['fim_adquirido'] = date('d/m/Y',strtotime($fimPeriodoAdquirido));
            $countYear++;
            $returnData[$countYear] = $data;

            if( $matricula_colaborador->mtc_data_fim and
                strtotime($finalPeriodo) > strtotime($matricula_colaborador->getRawOriginal('mtc_data_fim')) ){
                $returnData[$countYear]['fim'] = $matricula_colaborador->mtc_data_fim;
                $ano_valido = false;
                break;
            }

            $final = $this->getDate('+12 months', $inicioPeriodo);

            if( strtotime($inicioPeriodo) <  time() and time()  < strtotime($final)){
                $ano_valido = false;
                break;
            }
        }

        foreach ($returnData as $data) {
            $modelData = $this->model
                ->where('paq_data_inicio', '=', $this->convertDateFormat($data['inicio_adquirido'] )  )
                ->where('paq_data_fim', '=',  $this->convertDateFormat($data['fim_adquirido'] )    )
                ->where('paq_col_id', $matricula_colaborador->colaborador->col_id)
                ->where('paq_mtc_id', $matricula_colaborador->mtc_id)->first();

            if (!$modelData) {
                // Criando um novo registro no modelo
                $insertData = [
                    'paq_data_inicio' => $data['inicio_adquirido'],
                    'paq_data_fim' => $data['fim_adquirido'],
                    'paq_col_id' => $matricula_colaborador->colaborador->col_id,
                    'paq_mtc_id' => $matricula_colaborador->mtc_id
                ];
                $this->model->create($insertData);
            }
        }

        return $returnData;
    }


    public function getPeriodData(MatriculaColaborador $matricula_colaborador):array
    {

        $periodosAquisitivos = $this->model->where('paq_col_id', $matricula_colaborador->colaborador->col_id)
            ->where('paq_mtc_id', $matricula_colaborador->mtc_id)->get();

        $returnData = [];
        foreach ($periodosAquisitivos as $periodoAquisitivo) {
            $data['inicio_adquirido'] = $periodoAquisitivo->paq_data_inicio;
            $data['fim_adquirido'] = $periodoAquisitivo->paq_data_fim;
            $data['saldo_periodo']= 0;

            $limiteGozo = Carbon::createFromFormat('d/m/Y', $periodoAquisitivo->paq_data_fim)
                ->addMonths(11)
                ->format('d/m/Y');

            $data['limite_gozo'] = $limiteGozo;
            $data['periodo']=$periodoAquisitivo;
            $periodosGozo = $periodoAquisitivo->periodos_gozo;
            foreach ($periodosGozo as $item) {
                $diasEntreDatas = $this->contarDias($item['pgz_data_inicio'], $item['pgz_data_fim']);
                $data['saldo_periodo'] = $data['saldo_periodo'] + $diasEntreDatas;
            }
            $data['saldo_periodo']=30-$data['saldo_periodo'];;
            $returnData[] = $data;
        }
        return $returnData;
    }

    public function getPeriodDataReport($colaborador_id):array
    {

        $periodoGozo = $this->modelPeriodoGozo
            ->join('reh_periodos_aquisitivos', function ($join) {
                $join->on('pgz_paq_id', '=', 'paq_id');
            })
            ->where('paq_col_id', $colaborador_id)
            ->orderBy('paq_data_inicio', 'desc')
            ->select(['pgz_paq_id', 'reh_periodos_aquisitivos.created_at', 'paq_id', 'paq_data_inicio'])
            ->first();

        if($periodoGozo){

            $periodoAquisitivo = $this->model->where('paq_id', $periodoGozo->pgz_paq_id)
                ->orderBy('paq_data_inicio', 'desc') ->first();
        } else{
            $periodoAquisitivo = $this->model->where('paq_col_id', $colaborador_id)
                ->orderBy('paq_data_inicio', 'desc') ->first();
        }

        $data['inicio_gozo'] = Carbon::createFromFormat('d/m/Y', $periodoAquisitivo->paq_data_fim)
            ->format('d/m/Y');
        $data['fim_gozo'] = Carbon::createFromFormat('d/m/Y', $periodoAquisitivo->paq_data_fim)
            ->addMonths(12)
            ->format('d/m/Y');;
        $data['gozados']= 0;
        $data['dias_vencidos'] = -Carbon::now()->diffInDays(Carbon::createFromFormat('d/m/Y', $data['fim_gozo']), false);
        $limiteGozo = Carbon::createFromFormat('d/m/Y', $periodoAquisitivo->paq_data_fim)
            ->addMonths(11)
            ->format('d/m/Y');

        $data['limite_gozo'] = $limiteGozo;
        $data['periodo']=$periodoAquisitivo;
        $periodosGozo = $periodoAquisitivo->periodos_gozo;
        foreach ($periodosGozo as $item) {
            $diasEntreDatas = $this->contarDias($item['pgz_data_inicio'], $item['pgz_data_fim']);
            $data['gozados'] = $data['gozados'] + $diasEntreDatas;
        }
        $data['gozados']=$data['gozados'];
        return $data;
    }

    public function verificaSaldoDeDias($periodoAquisitivoId, $dataInicio, $dataFim, $periodoGozoId = null ):bool
    {

        $periodoAquisitivo = $this->model->find($periodoAquisitivoId);
        $periodosGozo = $periodoAquisitivo->periodos_gozo;

        $gozados = 0;
        foreach ($periodosGozo as $item) {
            if($item->pgz_id== $periodoGozoId){
                break;
            }
            $diasEntreDatas = $this->contarDias($item['pgz_data_inicio'], $item['pgz_data_fim']);
            $gozados = $gozados + $diasEntreDatas;
        }

        $diasEntreDatasAdicionadas = $this->contarDias($dataInicio, $dataFim);

        if($diasEntreDatasAdicionadas+ $gozados > 30){
            return false;
        }

        return true;
    }

    public function contarDias($pgz_data_inicio, $pgz_data_fim)
    {
        print_r($pgz_data_inicio, $pgz_data_fim);

        try {
            $dataInicio = Carbon::createFromFormat('d/m/Y', $pgz_data_inicio);
            $dataFim = Carbon::createFromFormat('d/m/Y', $pgz_data_fim);

            $dias = $dataInicio->diffInDays($dataFim)+1;

            return $dias;
        }catch (\Exception $e){
            dd($e->getMessage());
        }

    }


    function convertDateFormat($dateString) {
        // Cria um objeto DateTime a partir da data no formato d/m/Y
        $date = \DateTime::createFromFormat('d/m/Y', $dateString);

        // Verifica se a conversão foi bem-sucedida
        if ($date) {
            // Converte para o formato Y-m-d
            return $date->format('Y-m-d');
        } else {
            // Retorna uma mensagem de erro caso o formato seja inválido
            return 'Formato de data inválido';
        }
    }


    /**
     * Return data about Acquirer Periods.
     *
     * @param MatriculaColaborador $matricula_colaborador
     * @param $dateTimestamp
     * @return array|int
     */
    public function isInPeriodData(MatriculaColaborador $matricula_colaborador, $dateTimestamp):bool
    {
        if(time() < $this->getDate('+1 year', $matricula_colaborador->getRawOriginal('mtc_data_inicio')) ){
            return false;
        }

        $inicioPeriodo = $this->getDate('+0 year', $matricula_colaborador->getRawOriginal('mtc_data_inicio'));
        while (1){
            $inicioPeriodo = $this->getDate('+1 year', $inicioPeriodo);
            $finalPeriodo = $this->getDate('+1 year', $inicioPeriodo);

            $inicioTimestamp = strtotime($inicioPeriodo);
            $fimTimestamp = strtotime($finalPeriodo);

            if($inicioTimestamp < $dateTimestamp and $dateTimestamp < $fimTimestamp){
                if( $matricula_colaborador->mtc_data_fim and
                    $dateTimestamp > strtotime($matricula_colaborador->getRawOriginal('mtc_data_fim')) ){
                    return false;
                }
                return true;
            }

            if( strtotime($inicioPeriodo) <  time() and time()  < strtotime($finalPeriodo)){
                break;
            }
        }
        return false;
    }

    /**
     * Return data about Acquirer Periods.
     *
     * @param Colaborador $colaborador
     * @return array|int
     */
    public function currentPeriod(Colaborador $colaborador):array
    {
        if(time() < $this->getDate('+1 year', $colaborador->getRawOriginal('col_data_admissao')) ){
            return 0;
        }

        $inicioPeriodo = $this->getDate('+0 year', $colaborador->getRawOriginal('col_data_admissao'));
        while (1){
            $inicioPeriodo = $this->getDate('+1 year', $inicioPeriodo);
            $finalPeriodo = $this->getDate('+1 year', $inicioPeriodo);
            $finalAno = $this->getDate('+12 months', $inicioPeriodo);

            $data['inicio'] = date('d/m/Y',strtotime($inicioPeriodo));
            $data['fim'] = date('d/m/Y',strtotime($finalPeriodo));

            if( strtotime($inicioPeriodo) <  time() and time()  < strtotime($finalAno)){
                break;
            }
        }

        $feriasPeriodos = $this->model
            ->where('paq_data_inicio' , '>', $inicioPeriodo)
            ->where('paq_data_inicio' , '<', $finalPeriodo)
            ->where('paq_col_id', $colaborador->col_id)
            ->get();

        $data['adquiridos'] = '';
        foreach ($feriasPeriodos as $feriasPeriodo){
            $data['adquiridos'] .= $feriasPeriodo->paq_data_inicio.' a '.$feriasPeriodo->paq_data_fim.' ';
        }

        return $data;
    }

    public function getDate($string, $date){
        return date('Y-m-d',strtotime($string,strtotime($date)));
    }

    /**
     * Number of days between two dates.
     *
     * @param $dt1
     * @param $dt2
     * @return int
     */
    function daysBetween($dt1, $dt2) {
        return date_diff(
            date_create($dt2),
            date_create($dt1)
        )->format('%a');
    }
}
