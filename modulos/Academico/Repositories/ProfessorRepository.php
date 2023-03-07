<?php

namespace Modulos\Academico\Repositories;

use Illuminate\Support\Facades\DB;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Professor;
use Auth;

class ProfessorRepository extends BaseRepository
{
    public function __construct(Professor $professor)
    {
        $this->model = $professor;
    }

    /**
     * Retorna listas de pares com dados de tabelas
     * @param string $identifier
     * @param string $field
     * @param bool $all
     * @return \Illuminate\Support\Collection
     */
    public function lists($identifier, $field, $all = false)
    {
        $sql = DB::table('gra_pessoas')
            ->join('acd_professores', 'pes_id', '=', 'acd_professores.prf_pes_id');

        $user = Auth::user();
        if (!$user->isAdmin()){
            $sql = $sql->where('pes_itt_id', $user->pessoa->pes_itt_id);
        }

        if (!$all) {
            $sql = $sql->leftJoin('acd_centros', 'cen_prf_diretor', '=', 'prf_id')
                ->leftJoin('acd_cursos', 'crs_prf_diretor', '=', 'prf_id')
                ->whereNull('cen_prf_diretor')
                ->whereNull('crs_prf_diretor');
        }

        $sql = $sql->select($identifier, $field)->orderBy('pes_nome', 'asc');

        $entries = $sql->pluck($field, $identifier);

        return collect($entries);
    }


    public function paginate($sort = null, $search = null)
    {
        $result = $this->model->join('gra_pessoas', function ($join) {
            $join->on('prf_pes_id', '=', 'pes_id');
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

        $user = Auth::user();
        if (!$user->isAdmin()){
            $result = $result->where('pes_itt_id', $user->pessoa->pes_itt_id);
        }

        $result = $result->paginate(15);

        return $result;
    }

    public function search(array $options, array $select = null)
    {
        $query = $this->model->select('acd_professores.*', 'gra_pessoas.*', 'gra_documentos.*')
           ->join('gra_pessoas', function ($join) {
               $join->on('prf_pes_id', '=', 'pes_id');
           })->leftJoin('gra_documentos', function ($join) {
               $join->on('pes_id', '=', 'doc_pes_id')->where('doc_tpd_id', '=', 2, 'and', true);
           });

        foreach ($options as $op) {
            $query = $query->where($op[0], $op[1], $op[2]);
        }

        if (!is_null($select)) {
            $query = $query->select($select);
        }

        return $query->get();
    }
}
