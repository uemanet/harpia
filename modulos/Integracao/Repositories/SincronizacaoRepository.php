<?php
namespace Modulos\Integracao\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Models\Sincronizacao;
use DB;

class SincronizacaoRepository extends BaseRepository
{
    protected $tabelasSincronizacao = [
        'acd_turmas', 'acd_matriculas', ''
    ];
    public function __construct(Sincronizacao $sincronizacao)
    {
        $this->model = $sincronizacao;
    }

    public function updateSyncMoodle(array $data)
    {
        $keysSearch = [
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

        if ($registros->count()) {
            foreach ($registros as $obj) {
                $obj->fill($data)->save();
            }

            return $registros->count();
        }

        return 0;
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

    /**
     * Verifica se dado registro foi excluido do Moodle
     * @param $table
     * @param $tableId
     * @return bool
     */
    public static function excludedFromMoodle($table, $tableId)
    {
        $result = DB::table('int_sync_moodle')
            ->where('sym_table', '=', $table)
            ->where('sym_table_id', '=', $tableId)
            ->where('sym_action', '=', 'DELETE')
            ->where('sym_status', '=', 2)
            ->first();
        if ($result) {
            return true;
        }
        return false;
    }

    public function migrar($id)
    {
        $sincronizacao = $this->find($id);

        if (!$sincronizacao) {
            return null;
        }
    }
}
