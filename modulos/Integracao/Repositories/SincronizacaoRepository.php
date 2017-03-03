<?php

namespace Modulos\Integracao\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Models\Sincronizacao;
use DB;

class SincronizacaoRepository extends BaseRepository
{
    public function __construct(Sincronizacao $sincronizacao)
    {
        $this->model = $sincronizacao;
    }

    public function update(array $data, $id = null, $attribute = "id")
    {
        return $this->model
            ->where('sym_table', '=', $data['sym_table'])
            ->where('sym_table_id', '=', $data['sym_table_id'])
            ->where('sym_action', '=', $data['sym_action'])
            ->update($data);
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
}
