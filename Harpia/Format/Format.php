<?php
namespace Harpia\Format;

class Format
{
    public function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i<=strlen($mask)-1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }
        return $maskared;
    }

    public function formatDate($date, $format)
    {
        if (!empty($date)) {
            $date = date_create($date);
            return date_format($date, $format);
        }

        return '';
    }

    public function generateCpf()
    {
        $numbers = array();

        for($i = 0; $i < 9; $i++) {
            $numbers[] = rand(0,9);
        }

        $d1 = 0;
        $tam = count($numbers) - 1;
        for($j = 2; $j < 11; $j++, $tam--) {
            $d1 += ($numbers[$tam] * $j);
        }

        $d1 = 11 - ($this->mod($d1, 11));

        if($d1 >= 10) {
            $d1 = 0;
        }

        $d2 = $d1 * 2;
        $tam = count($numbers) - 1;
        for($j = 3; $j < 12; $j++, $tam--) {
            $d2 += ($numbers[$tam] * $j);
        }

        $d2 = 11 - ($this->mod($d2,11));

        if($d2 >= 10) {
            $d2 = 0;
        }

        $cpf = '';

        for($i = 0; $i < count($numbers); $i++) {
            $cpf .= $numbers[$i];
        }

        $cpf .= $d1;
        $cpf .= $d2;

        return $cpf;
    }

    private function mod($dividendo,$divisor)
    {
        return round($dividendo - (floor($dividendo/$divisor)*$divisor));
    }
}
