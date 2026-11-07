<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class PermissoesControleAcessoFacialSeeder extends Seeder
{
    public function run()
    {
        $modulo = Modulo::where('mod_slug', 'rh')->first();

        if (!$modulo) {
            return;
        }

        $perfil = Perfil::firstOrCreate(
            [
                'prf_mod_id' => $modulo->mod_id,
                'prf_nome' => 'Administrador RH',
            ]
        );

        $permissoes = [
            ['nome' => 'foto', 'rota' => 'rh.colaboradores.foto', 'descricao' => 'Visualizar foto facial do colaborador'],

            ['nome' => 'index', 'rota' => 'rh.dispositivosacesso.index', 'descricao' => 'Listar dispositivos de acesso'],
            ['nome' => 'create', 'rota' => 'rh.dispositivosacesso.create', 'descricao' => 'Cadastrar dispositivos de acesso'],
            ['nome' => 'edit', 'rota' => 'rh.dispositivosacesso.edit', 'descricao' => 'Editar dispositivos de acesso'],
            ['nome' => 'delete', 'rota' => 'rh.dispositivosacesso.delete', 'descricao' => 'Excluir dispositivos de acesso'],
            ['nome' => 'regenerartoken', 'rota' => 'rh.dispositivosacesso.regenerartoken', 'descricao' => 'Regenerar token do dispositivo'],
            ['nome' => 'ping', 'rota' => 'rh.dispositivosacesso.ping', 'descricao' => 'Testar comunicacao com o dispositivo'],
            ['nome' => 'sincronizarmapeamento', 'rota' => 'rh.dispositivosacesso.sincronizarmapeamento', 'descricao' => 'Sincronizar mapeamento do dispositivo'],

            ['nome' => 'index', 'rota' => 'rh.testedispositivo.index', 'descricao' => 'Acessar tela de teste de dispositivo'],
            ['nome' => 'ping', 'rota' => 'rh.testedispositivo.ping', 'descricao' => 'Executar ping no dispositivo'],
            ['nome' => 'listarusuarios', 'rota' => 'rh.testedispositivo.listarusuarios', 'descricao' => 'Listar usuarios do dispositivo'],
            ['nome' => 'consultarlogs', 'rota' => 'rh.testedispositivo.consultarlogs', 'descricao' => 'Consultar logs do dispositivo'],
            ['nome' => 'systeminfo', 'rota' => 'rh.testedispositivo.systeminfo', 'descricao' => 'Consultar informacoes do sistema do dispositivo'],

            ['nome' => 'index', 'rota' => 'rh.vincularcolaboradores.index', 'descricao' => 'Listar vinculacoes de colaboradores'],
            ['nome' => 'vincular', 'rota' => 'rh.vincularcolaboradores.vincular', 'descricao' => 'Vincular colaborador a usuario do dispositivo'],
            ['nome' => 'desvincular', 'rota' => 'rh.vincularcolaboradores.desvincular', 'descricao' => 'Desvincular colaborador de usuario do dispositivo'],
            ['nome' => 'sincronizar', 'rota' => 'rh.vincularcolaboradores.sincronizar', 'descricao' => 'Sincronizar usuarios para vinculacao'],
            ['nome' => 'exportarcsv', 'rota' => 'rh.vincularcolaboradores.exportarcsv', 'descricao' => 'Exportar CSV de vinculacoes'],

            ['nome' => 'index', 'rota' => 'rh.registrosponto.index', 'descricao' => 'Listar registros de ponto'],
            ['nome' => 'detalhes', 'rota' => 'rh.registrosponto.detalhes', 'descricao' => 'Visualizar detalhes do registro de ponto'],

            ['nome' => 'index', 'rota' => 'rh.eventosacesso.index', 'descricao' => 'Listar eventos de acesso'],
            ['nome' => 'show', 'rota' => 'rh.eventosacesso.show', 'descricao' => 'Visualizar detalhes do evento de acesso'],

            ['nome' => 'index', 'rota' => 'rh.pontoremoto.index', 'descricao' => 'Acessar tela de ponto remoto'],
            ['nome' => 'entrada', 'rota' => 'rh.pontoremoto.entrada', 'descricao' => 'Registrar entrada remota'],
            ['nome' => 'saida', 'rota' => 'rh.pontoremoto.saida', 'descricao' => 'Registrar saida remota'],

            ['nome' => 'index', 'rota' => 'rh.aprovacoesponto.index', 'descricao' => 'Listar aprovacoes de ponto'],
            ['nome' => 'show', 'rota' => 'rh.aprovacoesponto.show', 'descricao' => 'Visualizar detalhes da aprovacao de ponto'],
            ['nome' => 'aprovar', 'rota' => 'rh.aprovacoesponto.aprovar', 'descricao' => 'Aprovar registro de ponto'],
            ['nome' => 'parcial', 'rota' => 'rh.aprovacoesponto.parcial', 'descricao' => 'Aprovar parcialmente registro de ponto'],
            ['nome' => 'reprovar', 'rota' => 'rh.aprovacoesponto.reprovar', 'descricao' => 'Reprovar registro de ponto'],

            ['nome' => 'index', 'rota' => 'rh.configuracoesponto.index', 'descricao' => 'Listar configuracoes de ponto'],
            ['nome' => 'update', 'rota' => 'rh.configuracoesponto.update', 'descricao' => 'Atualizar configuracoes de ponto'],

            ['nome' => 'index', 'rota' => 'rh.dispositivousuarios.index', 'descricao' => 'Listar usuarios do dispositivo'],
            ['nome' => 'create', 'rota' => 'rh.dispositivousuarios.create', 'descricao' => 'Cadastrar usuario no dispositivo'],
            ['nome' => 'edit', 'rota' => 'rh.dispositivousuarios.edit', 'descricao' => 'Editar usuario no dispositivo'],
            ['nome' => 'delete', 'rota' => 'rh.dispositivousuarios.delete', 'descricao' => 'Remover usuario do dispositivo'],
            ['nome' => 'sincronizar', 'rota' => 'rh.dispositivousuarios.sincronizar', 'descricao' => 'Sincronizar usuarios do dispositivo'],
            ['nome' => 'sincronizartodos', 'rota' => 'rh.dispositivousuarios.sincronizartodos', 'descricao' => 'Sincronizar colaboradores em todos os dispositivos'],
            ['nome' => 'exportarcsv', 'rota' => 'rh.dispositivousuarios.exportarcsv', 'descricao' => 'Exportar CSV de usuarios do dispositivo'],
        ];

        $permissoesIds = [];

        foreach ($permissoes as $permissaoData) {
            $permissao = Permissao::updateOrCreate(
                ['prm_rota' => $permissaoData['rota']],
                [
                    'prm_nome' => $permissaoData['nome'],
                    'prm_descricao' => $permissaoData['descricao'],
                ]
            );

            $permissoesIds[] = $permissao->prm_id;
        }

        $perfil->permissoes()->syncWithoutDetaching($permissoesIds);

        $usuarioAdministrador = Usuario::find(1);

        if ($usuarioAdministrador) {
            $perfil->usuarios()->syncWithoutDetaching([$usuarioAdministrador->usr_id]);
        }
    }
}
