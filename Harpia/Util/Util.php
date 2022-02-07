<?php

namespace Harpia\Util;

class Util
{
    const SECRET_KEY = 'be3494ff4904fd83bf78e3cec0d38dda';

    /**
     * Removes created_at and updated_at from a given array
     *
     * @param $array
     * @return array $array
     */
    public function removeDatesPropertiesFromArray($array): array
    {
        unset($array['created_at']);
        unset($array['updated_at']);
        return $array;
    }

    /**
     * Recebe um timestamp e retorna o valor do dia por extenso.
     *
     * @return string
     */
    public function getDiaExtenso($date)
    {
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
            15 => 'quinze',
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

        $dia = $diaextenso[str_replace(' ', '', strftime('%e', $date))];
        return $dia;
    }

    /**
     * Recebe um timestamp e retorna o valor do mês por extenso.
     *
     * @return string
     */
    public function getMesExtenso($date)
    {
        setlocale(LC_ALL, config('app.locale'), 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set(config('app.timezone'));

        $mesextenso = strftime('%B', $date);
        return $mesextenso;
    }

    /**
     * Recebe um timestamp e retorna o dia.
     *
     * @return int
     */
    public function getDia($date)
    {
        return strftime('%d', $date);
    }

    /**
     * Recebe um timestamp e retorna mês.
     *
     * @return int
     */
    public function getMes($date)
    {
        return strftime('%m', $date);
    }

    /**
     * Recebe um timestamp e retorna o ano.
     *
     * @return int
     */
    public function getAno($date)
    {
        return strftime('%Y', $date);
    }

    public function encrypt($string)
    {

        $TextoClaro = $string;

        $TextoPublico = '';

        $IV = random_bytes(SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES);

        $TextoCifrado = sodium_crypto_aead_chacha20poly1305_ietf_encrypt($TextoClaro, $TextoPublico, $IV, self::SECRET_KEY);

        $Resultado = base64_encode($IV . $TextoCifrado);

        return ['ciphertext' => $Resultado];

    }

    public function decrypt($Resultado)
    {

        $Resultado = base64_decode($Resultado);

        $TextoCifrado = mb_substr($Resultado, SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES, null, '8bit');

        $IV = mb_substr($Resultado, 0, SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES, '8bit');

        $TextoPublico = '';

        $TextoClaro = sodium_crypto_aead_chacha20poly1305_ietf_decrypt($TextoCifrado, $TextoPublico, $IV, self::SECRET_KEY);

        return $TextoClaro;

    }
}
