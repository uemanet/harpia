<?php

namespace Modulos\RH\Repositories;

use Carbon\Traits\Date;
use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\Colaborador;
use Modulos\RH\Models\MatriculaColaborador;
use Modulos\RH\Models\PeriodoAquisitivo;

class PeriodoAquisitivoRepository extends BaseRepository
{
    public function __construct(PeriodoAquisitivo $periodo_aquisitivo)
    {
        $this->model = $periodo_aquisitivo;
    }

    /**
     * Return data about Acquirer Periods.
     *
     * @param MatriculaColaborador $matricula_colaborador
     * @return array|int
     */

    public function periodData(MatriculaColaborador $matricula_colaborador):array
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

            $feriasPeriodos = $this->model
                ->where('paq_col_id', $matricula_colaborador->mtc_col_id)
                ->where('paq_mtc_id', $matricula_colaborador->mtc_id)
                ->where(function ($query) use ($inicioPeriodo, $finalPeriodo) {
                    $query->where(function ($q) use ($inicioPeriodo, $finalPeriodo) {
                        // Modelo Antigo: não tem paq_gozo_inicio/fim e usa paq_data_inicio
                        $q->whereNull('paq_gozo_inicio')
                            ->where('paq_data_inicio', '>', $inicioPeriodo)
                            ->where('paq_data_inicio', '<', $finalPeriodo);
                    })->orWhere(function ($q) use ($inicioPeriodo, $finalPeriodo) {
                        // Modelo Novo: tem paq_gozo_inicio/fim e os mesmos valores do período de gozo
                        $q->whereNotNull('paq_gozo_inicio')
                            ->where('paq_gozo_inicio', $inicioPeriodo)
                            ->where('paq_gozo_fim', $finalPeriodo);
                    });
                })
                ->get();

            foreach ($feriasPeriodos as &$registro) {
                if (!empty($registro->paq_gozo_inicio) && !empty($registro->paq_gozo_fim)) {
                    $registro->modelo = 'Modelo Novo';
                } else {
                    $registro->modelo = 'Modelo Antigo';
                }
            }

            $between = 0;
            foreach ($feriasPeriodos as $feriasPeriodo) {
                $date1 = $feriasPeriodo->getRawOriginal('paq_data_inicio');
                $date2 = $feriasPeriodo->getRawOriginal('paq_data_fim');
                $between += $this->daysBetween($date1, $date2)+1;
            }

            $data['inicio_adquirido'] = date('d/m/Y',strtotime($inicioPeriodoAdquirido));
            $data['fim_adquirido'] = date('d/m/Y',strtotime($fimPeriodoAdquirido));
            $data['inicio'] = date('d/m/Y',strtotime($inicioPeriodo));
            $data['fim'] = date('d/m/Y',strtotime($finalPeriodo));
            $data['dias'] = $between;
            $data['periodos'] = $feriasPeriodos;

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

        return $returnData;
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
