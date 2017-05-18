<?php
namespace Modulos\Academico\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Centro;

class CentroTableSeeder extends Seeder
{
    public function run()
    {
        $centro1 = new Centro();

        $centro1->cen_prf_diretor = 1;
        $centro1->cen_nome = 'Centro de Tecnologia Integrada';
        $centro1->cen_sigla = 'CTI';

        $centro1->save();

        $centro2 = new Centro();

        $centro2->cen_prf_diretor = 2;
        $centro2->cen_nome = 'Centro de Engenharia Mecânica';
        $centro2->cen_sigla = 'CEM';

        $centro2->save();
    }
}
