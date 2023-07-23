<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Polo;
use Auth;

class PoloRepository extends BaseRepository
{
    public function __construct(Polo $polo)
    {
        $this->model = $polo;
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

        if (!empty($sort)) {
            $result = $result->orderBy($sort['field'], $sort['sort']);
        }

        $user = Auth::user();
        if (!$user->isAdmin()){
            $result = $result->where('pol_itt_id', $user->pessoa->pes_itt_id);
        }

        return $result->paginate(15);
    }

    public function lists($identifier, $field)
    {
        $result = $this->model;

        $user = Auth::user();
        if (!$user->isAdmin()){
            $result = $result->where('pol_itt_id', $user->pessoa->pes_itt_id);
        }


        return $result->pluck($field, $identifier);
    }
}
