<?php

namespace Modulos\RH\Database\Seeds\Production;


use Illuminate\Database\Seeder;
use Modulos\RH\Models\TipoJustificativa;

class TipoJustificativaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipos = [
            'Falta Justificada',
            'Atestado Médico',
            'Trabalho Externo',
            'Home Office',
            'Compensação de Horas',
            'Justificado e aceito pelo coordenador de área',
            'Férias',
            'Outros'
        ];

        foreach ($tipos as $tipo) {
            TipoJustificativa::create(['tipo_jus_descricao' => $tipo]);
        }
    }
}
