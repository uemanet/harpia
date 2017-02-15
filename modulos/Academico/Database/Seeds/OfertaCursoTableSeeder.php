<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\OfertaCurso;

class OfertaCursoTableSeeder extends Seeder
{
    public function run()
    {
        $oferta = new OfertaCurso();

        $oferta->ofc_crs_id = 1;
        $oferta->ofc_mtc_id = 1;
        $oferta->ofc_mdl_id = 3;
        $oferta->ofc_ano = 2017;

        $oferta->save();

        for ($i=1;$i<6;$i++) {
            $oferta->polos()->attach($i);
        }

        $oferta = new OfertaCurso();

        $oferta->ofc_crs_id = 1;
        $oferta->ofc_mtc_id = 1;
        $oferta->ofc_mdl_id = 2;
        $oferta->ofc_ano = 2018;

        $oferta->save();

        for ($i=6;$i<11;$i++) {
            $oferta->polos()->attach($i);
        }
    }
}