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
        if(!empty($date))
        {
            $date = date_create($date);
            return date_format($date, $format);
        }

        return '';
    }
}
