<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Seguranca\Models\Usuario;
use Modulos\Core\Repository\BaseRepository;

class UsuarioRepository extends BaseRepository
{
    public function __construct(Usuario $usuario)
    {
        parent::__construct($usuario);
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model->leftJoin('gra_pessoas', function ($join) {
            $join->on('pes_id', '=', 'usr_pes_id');
        })->leftJoin('gra_documentos', function ($join) {
            $join->on('pes_id', '=', 'doc_pes_id')->where('doc_tpd_id', '=', 2, 'and', true);
        });

        if (!empty($search)) {
            foreach ($search as $value) {
                if ($value['field'] == 'pes_cpf') {
                    $result = $result->where('doc_conteudo', '=', $value['term']);
                    continue;
                }

                switch ($value['type']) {
                    case 'like':
                        $result = $result->where($value['field'], $value['type'], "%{$value['term']}%");
                        break;
                    default:
                        $result = $result->where($value['field'], $value['type'], $value['term']);
                }
            }
        }

        if (!empty($sort)) {
            $result = $result->orderBy($sort['field'], $sort['sort']);
        }

        $result = $result->paginate(15);

        return $result;
    }

    public function create(array $data)
    {
        $dados = [
            'usr_usuario' => $data['usr_usuario'],
            'usr_senha' => bcrypt($data['usr_senha']),
            'usr_ativo' => $data['usr_ativo'],
            'usr_pes_id' => $data['usr_pes_id']
        ];

        return $this->model->create($dados);
    }

    public function sincronizarPerfis($usuarioId, array $perfis)
    {
        return $this->model->find($usuarioId)->perfis()->sync($perfis);
    }
}
