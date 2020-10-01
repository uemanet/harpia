<?php

namespace Modulos\RH\Repositories;

use Harpia\Util\Util;
use Illuminate\Support\Facades\DB;
use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\SalarioColaborador;
use Modulos\RH\Models\VinculoFontePagadora;

class SalarioColaboradorRepository extends BaseRepository
{
    public function __construct(SalarioColaborador $salario)
    {
        $this->model = $salario;
    }

    protected $tabelaIR = array(
        array(
            'min' => null,
            'operador' => '<=',
            'max' => 1903.98,
            'valor' => 0
        ),
        array(
            'min' => 1903.99,
            'operador' => '<=',
            'max' => 2826.65,
            'valor' => 142.80
        ),
        array(
            'min' => 2826.66,
            'operador' => '<=',
            'max' => 3751.05,
            'valor' => 354.80
        ),
        array(
            'min' => 3751.06,
            'operador' => '<=',
            'max' => 4664.68,
            'valor' => 636.13
        ),
        array(
            'min' => null,
            'operador' => '>',
            'max' => 4664.68,
            'valor' => 869.36
        )
    );

    public function create(array $data)
    {

        $vinculoFpg = new VinculoFontePagadora();
        $vinculoFpg = $vinculoFpg->find($data['scb_vfp_id']);

        $data['scb_valor_liquido'] = $data['scb_valor'];

        if ($vinculoFpg->vinculo->vin_descricao == 'Pessoa Física') {
            $data['scb_valor_liquido'] = $this->salarioPessoaFisica($data['scb_valor']);

        }

        $util = new Util();
        $encrypt = $util->encrypt($data['scb_valor']);
        $data['scb_valor'] = $encrypt['ciphertext'];

        $encrypt = $util->encrypt($data['scb_valor_liquido']);
        $data['scb_valor_liquido'] = $encrypt['ciphertext'];

        return $this->model->create($data);
    }


    public function salarioPessoaFisica($valorBruto)
    {
        // 1º imposto - INSS
        $inss = 0.11; // 11%

        $valorInss = $valorBruto * $inss;

        // 2º imposto - INSS
        $valorLiquidoParcial = $valorBruto - $valorInss;

        $valorLiquido = $valorLiquidoParcial;

        $valorIR = 0;
        foreach ($this->tabelaIR as $item) {

            if (is_null($item['min'])) {
                $expression = null;

                if ($item['operador'] == '<=') {
                    $expression = $valorLiquidoParcial <= $item['max'];
                }

                if ($item['operador'] == '>') {
                    $expression = $valorLiquidoParcial > $item['max'];
                }

                if ($expression == true) {
                    $valorIR = $item['valor'];
                    break;
                }
            }

            if (($valorLiquidoParcial >= $item['min']) && ($valorLiquidoParcial <= $item['max'])) {
                $valorIR = $item['valor'];
                break;
            }
        }

        $valorLiquido -= $valorIR;

        // 3º imposto - ISS
        $iss = 0.03;

        $valorISS = $valorBruto * $iss;

        $valorLiquido -= $valorISS;

        return $valorLiquido;
    }
}
