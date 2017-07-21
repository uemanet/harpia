<?php

namespace Modulos\Geral\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\Configuracao;
use DB;

class ConfiguracaoRepository extends BaseRepository
{
    public function __construct(Configuracao $configuracao)
    {
        $this->model = $configuracao;
    }

    public function getByName($config)
    {
        $result = DB::table('gra_configuracoes')
            ->select('cnf_valor')
            ->where('cnf_nome', '=', $config)
            ->pluck('cnf_valor')
            ->toArray();

        if (!empty($result)) {
            return array_shift($result);
        }

        return null;
    }

    public function getAll()
    {
        $query = DB::table('gra_configuracoes')
            ->select('cnf_id', 'cnf_mod_id', 'cnf_nome', 'cnf_valor')
            ->get()->toArray();

        $result = array();

        /**
         * Conversacao explicita de um objeto Array para tipo array
         * @see \Illuminate\Support\Facades\DB
         */
        foreach ($query as $item) {
            $result[] = (array) $item;
        }

        return $result;
    }

    /**
     * @see \Modulos\Core\Repository\BaseRepository
     */
    public function update(array $data, $id = null, $attribute = 'cnf_nome')
    {
        $obj = $this->model->where($attribute, '=', $data['cnf_nome'])->first();

        if ($obj) {
            $obj->fill($data)->save();
            return 1;
        }

        return 0;
    }

    /**
     * Verifica se existe uma dada configuracao no banco
     * @param $config
     * @return bool
     */
    public function configExists($config)
    {
        if (!is_null($this->getByName($config))) {
            return true;
        }

        return false;
    }

    /**
     * @see \Modulos\Core\Repository\BaseRepository
     * @param $config
     * @return int
     */
    public function delete($config)
    {
        $id = DB::table('gra_configuracoes')
            ->where('cnf_nome', '=', $config)
            ->pluck('cnf_id')->toArray();

        if (!empty($id)) {
            return $this->model->destroy(array_shift($id));
        }

        return null;
    }
}
