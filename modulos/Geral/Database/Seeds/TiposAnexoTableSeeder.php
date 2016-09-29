<?php
namespace Modulos\Geral\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Geral\Models\TipoAnexo;

class TiposAnexoTableSeeder extends Seeder
{
    public function run()
    {
        $tipoAnexo = new TipoAnexo();
        $tipoAnexo->tax_nome = 'PDF';
        $tipoAnexo->save();

        $tipoAnexo = new TipoAnexo();
        $tipoAnexo->tax_nome = 'DOCX';
        $tipoAnexo->save();

        $tipoAnexo = new TipoAnexo();
        $tipoAnexo->tax_nome = 'PPTX';
        $tipoAnexo->save();
    }
}
