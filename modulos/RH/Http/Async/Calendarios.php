<?php

namespace Modulos\RH\Http\Async;

use Illuminate\Http\JsonResponse;

use Modulos\Core\Http\Controller\BaseController;
use Modulos\RH\Http\Requests\CalendarioRequest;
use Modulos\RH\Repositories\CalendarioRepository;
use Illuminate\Http\Request;
use Modulos\RH\Repositories\HoraTrabalhadaRepository;
use Modulos\RH\Repositories\PeriodoLaboralRepository;


class Calendarios extends BaseController
{
    protected $calendarioRepository;

    public function __construct(CalendarioRepository $calendario,
                                HoraTrabalhadaRepository $horaTrabalhadaRepository,
                                PeriodoLaboralRepository $periodoLaboralRepository)
    {
        $this->calendarioRepository = $calendario;
        $this->horaTrabalhadaRepository = $horaTrabalhadaRepository;
        $this->periodoLaboralRepository = $periodoLaboralRepository;
    }

    public function index()
    {
        $calendarios = $this->calendarioRepository->all();
        return new JsonResponse($calendarios, JsonResponse::HTTP_OK);
    }

    public function postCreate(CalendarioRequest $request)
    {
        $cldId = $request->input('cld_id');
        $requestData = $request->only($this->calendarioRepository->getFillableModelFields());

        if ($cldId) {

            $calendario = $this->calendarioRepository->find($cldId);

            if (!$calendario) {
                return new JsonResponse(['message' => 'Evento nao encontrado.'], JsonResponse::HTTP_NOT_FOUND);
            }

            $dataAntiga = $calendario->cld_data;
            $this->calendarioRepository->update($requestData, $calendario->cld_id, 'cld_id');

            $calendario->refresh();

            $this->sincronizarPeriodosAfetados([$dataAntiga, $calendario->cld_data]);

            return new JsonResponse($calendario, JsonResponse::HTTP_CREATED);

        }

        $calendario = $this->calendarioRepository->create($requestData);

        $this->sincronizarPeriodosAfetados([$calendario->cld_data]);

        return new JsonResponse($calendario, JsonResponse::HTTP_CREATED);

    }

    public function getEdit($id)
    {

        $calendario = $this->calendarioRepository->find($id);

        if (!$calendario) {
            return new JsonResponse(['message' => 'Evento nao encontrado.'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($calendario, JsonResponse::HTTP_OK);

    }

    public function putEdit($id, CalendarioRequest $request)
    {

        $calendario = $this->calendarioRepository->find($id);

        if (!$calendario) {
            return new JsonResponse(['message' => 'Evento nao encontrado.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $dataAntiga = $calendario->cld_data;
        $requestData = $request->only($this->calendarioRepository->getFillableModelFields());

        $this->calendarioRepository->update($requestData, $calendario->cld_id, 'cld_id');
        $calendario->refresh();

        $this->sincronizarPeriodosAfetados([$dataAntiga, $calendario->cld_data]);

        return new JsonResponse($calendario, JsonResponse::HTTP_OK);

    }

    public function postDelete(Request $request)
    {
        $calendarioId = $request->get('id');

        $calendario = $this->calendarioRepository->find($calendarioId);

        if (!$calendario) {
            return new JsonResponse(['message' => 'Evento nao encontrado.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $dataEvento = $calendario->cld_data;

        $this->calendarioRepository->delete($calendarioId);

        $this->sincronizarPeriodosAfetados([$dataEvento]);

        return new JsonResponse([], JsonResponse::HTTP_OK);

    }

    private function sincronizarPeriodosAfetados(array $datas): void
    {
        $periodosQueDevemSerSincronizados = collect($datas)
            ->filter()
            ->unique()
            ->flatMap(function ($data) {
                return $this->periodoLaboralRepository->buscaPeriodosLaboraisEntreDatas($data, $data);
            })
            ->unique('pel_id');

        foreach ($periodosQueDevemSerSincronizados as $periodo) {
            $this->horaTrabalhadaRepository->sincronizarHorasTrabalhadas($periodo);
        }
    }

}
