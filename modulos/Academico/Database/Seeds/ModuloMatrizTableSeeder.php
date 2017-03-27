<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\MatrizCurricular;
use Modulos\Academico\Models\ModuloMatriz;

class ModuloMatrizTableSeeder extends Seeder
{
    public function run()
    {
        // criar modulos para todos os cursos

        $matrizes = MatrizCurricular::all();

        foreach ($matrizes as $matriz) {

            // cria 3 módulos pra cada matriz
            for ($i=1;$i<=3;$i++) {
                $modulo = new ModuloMatriz();

                $modulo->mdo_mtc_id = $matriz->mtc_id;
                $modulo->mdo_nome = 'Módulo '.$i;
                $modulo->mdo_descricao = 'Módulo '.$i.' da '.$matriz->mtc_nome;
                $modulo->mdo_qualificacao = 'Qualificado';
                $modulo->mdo_cargahoraria_min_eletivas = 0;
                $modulo->mdo_creditos_min_eletivas = 0;

                $modulo->save();
            }
        }
    }
}
