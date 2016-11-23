<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Vinculo;

class VinculoTableSeeder extends Seeder
{
    public function run()
    {
        $vinculo = new Vinculo();
        $vinculo->ucr_usr_id = 1;
        $vinculo->ucr_crs_id = 1;
        $vinculo->save();
    }
}
