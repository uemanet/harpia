<?php
namespace Modulos\RH\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\RH\Models\Vinculo;

class VinculoTableSeeder extends Seeder
{
    public function run()
    {

        $vinculo = new Vinculo();
        $vinculo->vin_descricao = 'Bolsa';
        $vinculo->save();

        $vinculo = new Vinculo();
        $vinculo->vin_descricao = 'Pessoa Física';
        $vinculo->save();

    }
}
