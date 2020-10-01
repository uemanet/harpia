<?php

namespace Modulos\Academico\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\MatrizCurricular;
use Modulos\Academico\Models\OfertaCurso;
use Modulos\Academico\Models\Polo;

class OfertaCursoTableSeeder extends Seeder
{
    public function run()
    {
        $matrizes = MatrizCurricular::all();

        foreach ($matrizes as $matriz) {
            $oferta = new OfertaCurso();

            $oferta->ofc_crs_id = $matriz->mtc_crs_id;
            $oferta->ofc_mtc_id = $matriz->mtc_id;
            $oferta->ofc_mdl_id = 3;
            $oferta->ofc_ano = 2020;

            $oferta->save();

            // cadastra 5 polos por oferta
            $polos = Polo::all();
            $polos = $polos->random(5);

            foreach ($polos as $polo) {
                $oferta->polos()->attach($polo->pol_id);
            }
        }
    }
}
