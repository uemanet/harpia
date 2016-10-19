<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Geral\Models\Pessoa;
use Modulos\Seguranca\Models\Usuario;

class UsuarioTableSeeder extends Seeder
{
    public function run()
    {
        $pessoa = new Pessoa;
        $pessoa->pes_nome = 'Administrador';
        $pessoa->pes_sexo = 'M';
        $pessoa->pes_email = 'admin@admin.com';
        $pessoa->pes_telefone = '98988888888';
        $pessoa->pes_nascimento = '2016-05-01';
        $pessoa->pes_mae = 'Mãe Administrador';
        $pessoa->pes_pai = 'Pai Administrador';
        $pessoa->pes_estado_civil = 'solteiro';
        $pessoa->pes_naturalidade = 'São Luís';
        $pessoa->pes_nacionalidade = 'Brasil';
        $pessoa->pes_raca = 'Branco';
        $pessoa->pes_necessidade_especial = 'Não';
        $pessoa->pes_estrangeiro = 0;

        $pessoa->save();

        $usuario = new Usuario();
        $usuario->usr_pes_id = $pessoa->pes_id;
        $usuario->usr_usuario = $pessoa->pes_email;
        $usuario->usr_senha = bcrypt('123456');
        $usuario->usr_ativo = 1;

        $usuario->save();

        $pessoa = new Pessoa;
        $pessoa->pes_nome = 'Administrador Acadêmico';
        $pessoa->pes_sexo = 'M';
        $pessoa->pes_email = 'admin2@admin.com';
        $pessoa->pes_telefone = '98988888888';
        $pessoa->pes_nascimento = '2016-05-01';
        $pessoa->pes_mae = 'Mãe Administrador';
        $pessoa->pes_pai = 'Pai Administrador';
        $pessoa->pes_estado_civil = 'solteiro';
        $pessoa->pes_naturalidade = 'São Luís';
        $pessoa->pes_nacionalidade = 'Brasil';
        $pessoa->pes_raca = 'Branco';
        $pessoa->pes_necessidade_especial = 'Não';
        $pessoa->pes_estrangeiro = 0;

        $pessoa->save();

        $usuario = new Usuario();
        $usuario->usr_pes_id = $pessoa->pes_id;
        $usuario->usr_usuario = $pessoa->pes_email;
        $usuario->usr_senha = bcrypt('123456');
        $usuario->usr_ativo = 1;

        $usuario->save();
    }
}
