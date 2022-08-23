<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\Justificativa;

class JustificativaRepository extends BaseRepository
{
    public function __construct(Justificativa $justificativa)
    {
        $this->model = $justificativa;
    }


    /**
     * PaginateRequest
     * @param array|null $requestParameters
     * @return mixed
     */
    public function paginateRequestByHoraTrabalhada($horaTrabalhadaId, array $requestParameters = null)
    {
        if (!empty($requestParameters['field']) && !empty($requestParameters['sort'])) {
            $sort = [
                'field' => $requestParameters['field'],
                'sort' => $requestParameters['sort']
            ];

            return $this->model->where('jus_htr_id', '=', $horaTrabalhadaId)
                ->orderBy($sort['field'], $sort['sort'])
                ->paginate(15);
        }

        return $this->model->where('jus_htr_id', '=', $horaTrabalhadaId)->paginate(15);
    }
}