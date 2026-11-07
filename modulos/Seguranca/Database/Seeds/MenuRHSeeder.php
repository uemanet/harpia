<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;

use Modulos\Seguranca\Models\MenuItem;
use Modulos\Seguranca\Models\Modulo;
use Modulos\Seguranca\Models\Perfil;
use Modulos\Seguranca\Models\Permissao;
use Modulos\Seguranca\Models\Usuario;

class MenuRHSeeder extends Seeder
{
    public function run()
    {
        $modulo = Modulo::where('mod_slug', 'rh')->first();

        if (!$modulo) {
            return;
        }

        // ─── Categoria: Cadastros ────────────────────────────────────
        $cadastros = MenuItem::updateOrCreate(
            [
                'mit_mod_id' => $modulo->mod_id,
                'mit_nome' => 'Cadastros',
                'mit_item_pai' => null,
            ],
            [
                'mit_icone' => 'fa fa-plus',
                'mit_ordem' => 1,
            ]
        );

        $itensCadastros = [
            ['mit_nome' => 'Áreas de Conhecimento', 'mit_rota' => 'rh.areasconhecimentos.index', 'mit_icone' => 'fa fa-tachometer', 'mit_ordem' => 1],
            ['mit_nome' => 'Bancos', 'mit_rota' => 'rh.bancos.index', 'mit_icone' => 'fa fa-bank', 'mit_ordem' => 2],
            ['mit_nome' => 'Vínculos', 'mit_rota' => 'rh.vinculos.index', 'mit_icone' => 'fa fa-link', 'mit_ordem' => 3],
            ['mit_nome' => 'Funções', 'mit_rota' => 'rh.funcoes.index', 'mit_icone' => 'fa fa-user', 'mit_ordem' => 4],
            ['mit_nome' => 'Setores', 'mit_rota' => 'rh.setores.index', 'mit_icone' => 'fa fa-users', 'mit_ordem' => 5],
            ['mit_nome' => 'Períodos Laborais', 'mit_rota' => 'rh.periodoslaborais.index', 'mit_icone' => 'fa fa-calendar', 'mit_ordem' => 6],
            ['mit_nome' => 'Colaboradores', 'mit_rota' => 'rh.colaboradores.index', 'mit_icone' => 'fa fa-user', 'mit_ordem' => 7],
            ['mit_nome' => 'Fontes Pagadoras', 'mit_rota' => 'rh.fontespagadoras.index', 'mit_icone' => 'fa fa-money', 'mit_ordem' => 8],
            ['mit_nome' => 'Calendários', 'mit_rota' => 'rh.calendarios.index', 'mit_icone' => 'fa fa-calendar', 'mit_ordem' => 9],
            ['mit_nome' => 'Horas Trabalhadas', 'mit_rota' => 'rh.horastrabalhadas.index', 'mit_icone' => 'fa fa-clock-o', 'mit_ordem' => 10],
        ];

        foreach ($itensCadastros as $item) {
            MenuItem::updateOrCreate(
                [
                    'mit_mod_id' => $modulo->mod_id,
                    'mit_rota' => $item['mit_rota'],
                ],
                [
                    'mit_nome' => $item['mit_nome'],
                    'mit_item_pai' => $cadastros->mit_id,
                    'mit_icone' => $item['mit_icone'],
                    'mit_ordem' => $item['mit_ordem'],
                ]
            );
        }

        // ─── Categoria: Controle de Acesso ───────────────────────────
        $controleAcesso = MenuItem::updateOrCreate(
            [
                'mit_mod_id' => $modulo->mod_id,
                'mit_nome' => 'Controle de Acesso',
                'mit_item_pai' => null,
            ],
            [
                'mit_icone' => 'fa fa-id-card',
                'mit_ordem' => 2,
            ]
        );

        $itensControleAcesso = [
            ['mit_nome' => 'Dispositivos de Acesso', 'mit_rota' => 'rh.dispositivosacesso.index', 'mit_icone' => 'fa fa-server', 'mit_ordem' => 1],
            ['mit_nome' => 'Usuários do Dispositivo', 'mit_rota' => 'rh.dispositivousuarios.index', 'mit_icone' => 'fa fa-user-circle', 'mit_ordem' => 2],
            ['mit_nome' => 'Vincular Colaboradores', 'mit_rota' => 'rh.vincularcolaboradores.index', 'mit_icone' => 'fa fa-link', 'mit_ordem' => 3],
            ['mit_nome' => 'Registro de Horas', 'mit_rota' => 'rh.registrosponto.index', 'mit_icone' => 'fa fa-list-alt', 'mit_ordem' => 4],
            ['mit_nome' => 'Eventos de Acesso', 'mit_rota' => 'rh.eventosacesso.index', 'mit_icone' => 'fa fa-history', 'mit_ordem' => 5],
            ['mit_nome' => 'Registro de Horas Remoto', 'mit_rota' => 'rh.pontoremoto.index', 'mit_icone' => 'fa fa-home', 'mit_ordem' => 6],
            ['mit_nome' => 'Aprovação de Horas', 'mit_rota' => 'rh.aprovacoesponto.index', 'mit_icone' => 'fa fa-check-circle', 'mit_ordem' => 7],
            ['mit_nome' => 'Configuração de Registro', 'mit_rota' => 'rh.configuracoesponto.index', 'mit_icone' => 'fa fa-cog', 'mit_ordem' => 8],
            ['mit_nome' => 'Teste de Dispositivo', 'mit_rota' => 'rh.testedispositivo.index', 'mit_icone' => 'fa fa-flask', 'mit_ordem' => 9],
        ];

        foreach ($itensControleAcesso as $item) {
            MenuItem::updateOrCreate(
                [
                    'mit_mod_id' => $modulo->mod_id,
                    'mit_rota' => $item['mit_rota'],
                ],
                [
                    'mit_nome' => $item['mit_nome'],
                    'mit_item_pai' => $controleAcesso->mit_id,
                    'mit_icone' => $item['mit_icone'],
                    'mit_ordem' => $item['mit_ordem'],
                ]
            );
        }

        // ─── Categoria: Recursos Humanos (Dashboard) ─────────────────
        $dashboard = MenuItem::updateOrCreate(
            [
                'mit_mod_id' => $modulo->mod_id,
                'mit_nome' => 'Recursos Humanos',
                'mit_item_pai' => null,
            ],
            [
                'mit_icone' => 'fa fa-file-text',
                'mit_ordem' => 0,
            ]
        );

        MenuItem::updateOrCreate(
            [
                'mit_mod_id' => $modulo->mod_id,
                'mit_rota' => 'rh.index.index',
            ],
            [
                'mit_nome' => 'Dashboard',
                'mit_item_pai' => $dashboard->mit_id,
                'mit_icone' => 'fa fa-tachometer',
                'mit_ordem' => 1,
            ]
        );
    }
}
