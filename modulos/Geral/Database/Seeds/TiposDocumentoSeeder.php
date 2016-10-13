<?php

namespace Modulos\Geral\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Geral\Models\TipoDocumento;

class TiposDocumentoSeeder extends Seeder
{
    public function run()
    {
        $tipos = [
            'IDENTIDADE',
            'CPF',
            'HISTÓRICO ESCOLAR',
            'TÍTULO DE ELEITOR',
            'QUITAÇÃO ELEITORAL',
            'DOCUMENTAÇÃO MILITAR',
            'FOTO',
            'HISTÓRICO ORIGINAL',
            'ATESTADO MÉDICO',
            'DIPLOMA AUTENTICADO',
            'SOLTEIRO EMANCIPADO'
        ];

        foreach($tipos as $tipo)
        {
            $obj = new TipoDocumento();
            $obj->tpd_nome = $tipo;
            $obj->save();
        }
    }
}