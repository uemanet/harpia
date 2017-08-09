<?php
namespace Harpia\Util;

class Util
{

    /**
     * Retorna um array com o mês e o dia por extenso.
     *
     * @return array
     */
    public function getDiaMesExtenso($date)
    {

      setlocale(LC_ALL, config('app.locale'), 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
      date_default_timezone_set(config('app.timezone'));

      $diaextenso = [
                0 => 'zero',
                1 => 'um',
                2 => 'dois',
                3 => 'três',
                4 => 'quatro',
                5 => 'cinco',
                6 => 'seis',
                7 => 'sete',
                8 => 'oito',
                9 => 'nove',
                10 => 'dez',
                11 => 'onze',
                12 => 'doze',
                13 => 'treze',
                14 => 'quatorze',
                15=> 'quinze',
                16 => 'dezesseis',
                17 => 'dezessete',
                18 => 'dezoito',
                19 => 'dezenove',
                20 => 'vinte',
                21 => 'vinte e um',
                22 => 'vinte e dois',
                23 => 'vinte e três',
                24 => 'vinte e quatro',
                25 => 'vinte e cinco',
                26 => 'vinte e seis',
                27 => 'vinte e sete',
                28 => 'vinte e oito',
                29 => 'vinte e nove',
                30 => 'trinta',
                31 => 'trinta e um'
            ];

      $dia = strftime('%d', $date);
      $mes = strftime('%m', $date);
      $ano = strftime('%Y', $date);
      $returnData = [
        'DIA' => $dia,
        'DIAEXTENSO' => $diaextenso[str_replace(' ', '', strftime('%e', $date))],
        'MES' => $mes,
        'MESEXTENSO' => strftime('%B', $date),
        'ANO' => $ano
      ];
      return $returnData;

    }


}
