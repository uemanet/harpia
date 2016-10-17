<?php
namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Departamento;

class DepartamentoTableSeeder extends Seeder
{
    public function run()
    {
        $departamento = new Departamento();

        $departamento->dep_cen_id = 1;
        $departamento->dep_prf_diretor = 5;
        $departamento->dep_nome = 'Departamento Acadêmico de Informática';

        $departamento->save();
    }
}
