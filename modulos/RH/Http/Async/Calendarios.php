<?php

namespace Modulos\RH\Http\Async;

use Illuminate\Http\JsonResponse;

use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Http\Requests\CalendarioRequest;
use Modulos\RH\Repositories\CalendarioRepository;
use Illuminate\Http\Request;


class Calendarios extends BaseController
{
    protected $calendarioRepository;

    public function __construct(CalendarioRepository $calendario)
    {
        $this->calendarioRepository = $calendario;
    }

    public function index()
    {

        $calendarios = $this->calendarioRepository->all();

        return new JsonResponse($calendarios, JsonResponse::HTTP_OK);

    }

    public function postCreate(CalendarioRequest $request)
    {

        $data = $request->all();


        if ($data['cld_id']) {

            $calendario = $this->calendarioRepository->find($data['cld_id']);
            $requestData = $request->only($this->calendarioRepository->getFillableModelFields());
            $this->calendarioRepository->update($requestData, $calendario->cld_id, 'cld_id');

            return new JsonResponse($calendario, JsonResponse::HTTP_CREATED);

        }


        $calendario = $this->calendarioRepository->create($request->all());

        return new JsonResponse($calendario, JsonResponse::HTTP_CREATED);

    }

    public function getEdit($id)
    {

        $calendario = $this->calendarioRepository->find($id);

        return new JsonResponse($calendario, JsonResponse::HTTP_OK);

    }

    public function putEdit($id, CalendarioRequest $request)
    {

        $calendario = $this->calendarioRepository->find($id);

        $requestData = $request->only($this->calendarioRepository->getFillableModelFields());

        $this->calendarioRepository->update($requestData, $calendario->cld_id, 'cld_id');

        return new JsonResponse($calendario, JsonResponse::HTTP_OK);

    }

    public function postDelete(Request $request)
    {
        $calendarioId = $request->get('id');

        $this->calendarioRepository->delete($calendarioId);

        return new JsonResponse([], JsonResponse::HTTP_OK);

    }

}
