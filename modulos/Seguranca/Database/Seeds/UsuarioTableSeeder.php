<?php
namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Geral\Models\Documento;
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
        $pessoa->pes_nascimento = '01/05/2016';
        $pessoa->pes_mae = 'Mãe Administrador';
        $pessoa->pes_pai = 'Pai Administrador';
        $pessoa->pes_estado_civil = 'solteiro';
        $pessoa->pes_naturalidade = 'São Luís';
        $pessoa->pes_nacionalidade = 'Brasil';
        $pessoa->pes_endereco = 'Endereço de Administrador';
        $pessoa->pes_numero = 12;
        $pessoa->pes_cep = 651300000;
        $pessoa->pes_cidade = 'São Luís';
        $pessoa->pes_bairro = 'Cohama';
        $pessoa->pes_estado = 'MA';
        $pessoa->pes_raca = 'Branco';
        $pessoa->pes_necessidade_especial = 'Não';
        $pessoa->pes_estrangeiro = 0;
        $pessoa->save();
        $documento = new Documento();
        $documento->doc_conteudo = '63737257400';
        $documento->doc_tpd_id = 2;
        $documento->doc_pes_id = $pessoa->pes_id;
        $documento->save();
        $usuario = new Usuario();
        $usuario->usr_pes_id = $pessoa->pes_id;
        $usuario->usr_usuario = $pessoa->pes_email;
        $usuario->usr_senha = bcrypt('123456');
        $usuario->usr_ativo = 1;
        $usuario->save();
    }
}
