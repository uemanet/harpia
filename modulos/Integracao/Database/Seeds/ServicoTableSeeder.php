<?php
namespace Modulos\Integracao\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Integracao\Models\Servico;

class ServicoTableSeeder extends Seeder
{
    public function run()
    {
        $servico = new Servico();
        $servico->ser_nome = 'Monitor';
        $servico->ser_slug = 'get_tutor_online_time';
        $servico->save();

        $servico = new Servico();
        $servico->ser_nome = 'Integracao';
        $servico->ser_slug = 'local_integracao';
        $servico->save();
    }
}
