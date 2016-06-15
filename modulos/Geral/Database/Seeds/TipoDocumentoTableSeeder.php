<?php

namespace Modulos\Geral\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Geral\Models\TipoDocumento;

class TipoDocumentoTableSeeder extends Seeder
{
    public function run()
    {
        $tipo = new TipoDocumento;
        $tipo->tpd_nome = 'CPF';
        $tipo->save();

        $tipo = new TipoDocumento;
        $tipo->tpd_nome = 'RG';
        $tipo->save();
    }
}