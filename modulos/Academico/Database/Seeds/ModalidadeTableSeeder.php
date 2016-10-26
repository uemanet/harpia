<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Modalidade;

class ModalidadeTableSeeder extends Seeder
{
    public function run()
    {
        $modalidade = new Modalidade();
        $modalidade->mdl_nome = 'Presencial';
        $modalidade->save();

        $modalidade = new Modalidade();
        $modalidade->mdl_nome = 'Semi-presencial';
        $modalidade->save();

        $modalidade = new Modalidade();
        $modalidade->mdl_nome = 'EaD';
        $modalidade->save();
    }
}
