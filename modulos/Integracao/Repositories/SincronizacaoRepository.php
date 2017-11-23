<?php

namespace Modulos\Integracao\Repositories;

use DB;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Models\Sincronizacao;

class SincronizacaoRepository extends BaseRepository
{
    public function __construct(Sincronizacao $sincronizacao)
    {
        parent::__construct($sincronizacao);
    }

    public function all()
    {
        $result = $this->model;
        return $result->orderBy('created_at', 'DESC')->get();
    }

    public function updateSyncMoodle(array $data)
    {
        $keysSearch = [
            'sym_id',
            'sym_table',
            'sym_table_id',
            'sym_action',
        ];

        $query = $this->model;

        foreach ($keysSearch as $key) {
            if (array_key_exists($key, $data)) {
                $query = $query->where($key, '=', $data[$key]);
            }
        }

        $registros = $query->get();

        /*
         * Atualiza o ultimo registro com as especificacoes passadas
         * Os demais permanecerao com os dados anteriores
         */
        $registro = $registros->pop();
        $registro->fill($data)->save();

        return $registro->sym_id;
    }

    public function findBy(array $options)
    {
        $query = $this->model;
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $query = $query->where($key, '=', $value);
            }
            return $query->get();
        }
        return $query->all();
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model;

        if (!empty($search)) {
            foreach ($search as $key => $value) {
                switch ($value['type']) {
                    case 'like':
                        $result = $result->where($value['field'], $value['type'], "%{$value['term']}%");
                        break;
                    default:
                        $result = $result->where($value['field'], $value['type'], $value['term']);
                }
            }
        }

        if (empty($sort)) {
            $result = $result->orderBy('created_at', 'DESC');
        }

        if (!empty($sort)) {
            $result = $result->orderBy($sort['field'], $sort['sort']);
        }

        return $result->paginate(15);
    }
}
