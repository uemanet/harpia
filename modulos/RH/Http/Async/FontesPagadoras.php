<?php

namespace Modulos\RH\Http\Async;

use Illuminate\Http\JsonResponse;

use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Repositories\FontePagadoraRepository;

class FontesPagadoras extends BaseController
{
    protected $fontePagadoraRepository;

    public function __construct(FontePagadoraRepository $fonte_pagadora)
    {
        $this->fontePagadoraRepository = $fonte_pagadora;
    }

    public function getVinculosFontesPagadoras($fontePagadoraId)
    {

        $fonte_pagadora = $this->fontePagadoraRepository->find($fontePagadoraId);

        return new JsonResponse($fonte_pagadora->vinculos_fontes_pagadoras()->join('reh_vinculos', 'vin_id', 'vfp_vin_id')->get(), 200);

    }
}
