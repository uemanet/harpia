<?php
namespace Modulos\Integracao\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Integracao\Models\Servico;

class ServicoTableSeeder extends Seeder
{
    public function run()
    {
        $Servico = new Servico();
        $Servico->ser_nome = 'MonitoramentoTempo';
        $Servico->ser_slug = 'get_tutor_online_time';
        $Servico->save();

        $Servico = new Servico();
        $Servico->ser_nome = 'MonitoramentoForuns';
        $Servico->save();

        $Servico = new Servico();
        $Servico->ser_nome = 'MonitoramentoNotas';
        $Servico->save();
    }
}
