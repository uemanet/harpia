<?php

namespace Modulos\RH\Services;

use Modulos\RH\Models\DispositivoAcesso;
use Modulos\RH\Repositories\ColaboradorRepository;

class ExportacaoUsuariosDispositivoService
{
    public function __construct(
        private ColaboradorRepository $colaboradorRepository
    ) {
    }

    public function gerarCsv(?DispositivoAcesso $dispositivo = null): string
    {
        $colaboradores = $this->colaboradorRepository->listarAtivosParaExportacao($dispositivo?->dis_id);

        $usuarios = [];

        foreach ($colaboradores as $colaborador) {
            $usuarios[] = [
                'registration' => (string) $colaborador->col_id,
                'name' => (string) $colaborador->pes_nome,
            ];
        }

        return $this->montarCsv($usuarios);
    }

    public function nomeArquivo(?DispositivoAcesso $dispositivo = null): string
    {
        $sufixo = $dispositivo ? '-dispositivo-' . $dispositivo->dis_id : '';

        return 'usuarios-controlid' . $sufixo . '-' . date('Ymd-His') . '.csv';
    }

    public function montarCsv(array $usuarios): string
    {
        $handle = fopen('php://temp', 'w+');

        $linhas = [
            ['cid_metadata'],
            [],
            ['_visitors', 'Visitantes', '3'],
            ['column_name', 'default_value', 'type', 'constraint', 'unique', 'name', 'foreign_key_object', 'foreign_key_field'],
            ['id', '', 'BIGINT', '5', '0', '', '', ''],
            ['user_id', '', 'BIGINT', '6', '0', '', 'users', 'id'],
            [],
            ['c_users', 'Usuarios', '1'],
            ['column_name', 'default_value', 'type', 'constraint', 'unique', 'name', 'foreign_key_object', 'foreign_key_field'],
            ['id', '', 'BIGINT', '5', '0', '', '', ''],
            ['user_id', '', 'BIGINT', '6', '0', '', 'users', 'id'],
            ['cpf', '', 'TEXT', '0', '0', 'CPF', '', ''],
            [],
            ['c_visits', 'Visitas', '2'],
            ['column_name', 'default_value', 'type', 'constraint', 'unique', 'name', 'foreign_key_object', 'foreign_key_field'],
            ['id', '', 'BIGINT', '5', '0', '', '', ''],
            ['visit_id', '', 'BIGINT', '6', '0', '', 'visits', 'id'],
            [],
            ['cid_data'],
            [],
            ['user_types'],
            ['id', 'custom_table_id', 'require_visitor'],
            ['1', '3', '1'],
            [],
            ['users'],
            ['id', 'registration', 'name', 'password', 'panic_password', 'salt', 'panic_salt', 'expires', 'user_type_id', 'begin_time', 'end_time', 'image_timestamp', 'last_access'],
        ];

        foreach ($linhas as $linha) {
            fputcsv($handle, $linha);
        }

        foreach ($usuarios as $usuario) {
            fputcsv($handle, [
                '',
                $usuario['registration'],
                $usuario['name'],
                '',
                '',
                '',
                '',
                '',
                '1',
                '0',
                '0',
                '',
                '',
            ]);
        }

        $blocosVazios = [
            [],
            ['templates'],
            ['id', 'finger_position', 'finger_type', 'template', 'user_id'],
            [],
            ['face_templates'],
            ['id', 'face_type', 'template', 'user_id', 'length'],
            [],
            ['cards'],
            ['id', 'value', 'user_id', 'secret'],
            [],
            ['user_roles'],
            ['user_id', 'role'],
            [],
            ['pins'],
            ['id', 'pin_type', 'value', 'user_id'],
            [],
            ['qrcodes'],
            ['id', 'value', 'user_id'],
            [],
            ['visits'],
            ['id', 'visitor_id', 'host_id', 'begin_time', 'end_time', 'finished'],
            [],
            ['_visitors'],
            ['id', 'user_id'],
            [],
            ['c_users'],
            ['id', 'user_id', 'cpf'],
            [],
            ['c_visits'],
            ['id', 'visit_id'],
        ];

        foreach ($blocosVazios as $linha) {
            fputcsv($handle, $linha);
        }

        rewind($handle);

        $conteudo = stream_get_contents($handle) ?: '';
        fclose($handle);

        return $conteudo;
    }
}
