<?php

namespace Modulos\RH\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\RH\Models\DispositivoAcesso;

class DispositivoAcessoSeeder extends Seeder
{
    public function run(): void
    {
        $dispositivos = [
            [
                'dis_nome' => 'iDFace Entrada',
                'dis_identificador' => 'IDFACE-ENTRADA-01',
                'dis_tipo' => 'entrada',
                'dis_ip' => '192.168.1.101',
                'dis_modelo' => 'iDFace',
                'dis_token_api' => hash('sha256', 'idface-entrada-dev'),
                'dis_status' => 'ativo',
                'dis_observacao' => 'Dispositivo de desenvolvimento para testes de entrada.',
            ],
            [
                'dis_nome' => 'iDFace Saida',
                'dis_identificador' => 'IDFACE-SAIDA-02',
                'dis_tipo' => 'saida',
                'dis_ip' => '192.168.1.102',
                'dis_modelo' => 'iDFace',
                'dis_token_api' => hash('sha256', 'idface-saida-dev'),
                'dis_status' => 'ativo',
                'dis_observacao' => 'Dispositivo de desenvolvimento para testes de saida.',
            ],
        ];

        foreach ($dispositivos as $dispositivo) {
            DispositivoAcesso::updateOrCreate(
                ['dis_identificador' => $dispositivo['dis_identificador']],
                $dispositivo
            );
        }
    }
}
