<?php

namespace Modulos\Academico\Listeners;

use Moodle;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Academico\Events\CreateMatriculaDisciplinaLoteEvent;

class CreateMatriculaDisciplinaLoteListener
{
    protected $alunoRepository;
    protected $ambienteVirtualRepository;

    public function __construct(
        AlunoRepository $alunoRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    )
    {
        $this->alunoRepository = $alunoRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(CreateMatriculaDisciplinaLoteEvent $event)
    {
        try {
            $param = [];

            // Reunir os dados para envio em lote
            foreach ($event->getItems() as $matriculaOfertaDisciplina) {

                if ($event->getVersion() != 'v1') {
                    return;
                }

                $enrol = [];

                $aluno = $this->alunoRepository->find($matriculaOfertaDisciplina->matriculaCurso->mat_alu_id);

                $enrol['mof_id'] = $matriculaOfertaDisciplina->mof_id;
                $enrol['pes_id'] = $aluno->alu_pes_id;
                $enrol['ofd_id'] = $matriculaOfertaDisciplina->mof_ofd_id;

                $param['data']['enrol'][] = $enrol;
                unset($enrol);
            }

            $idTurma = $event->getItems()->random()->matriculaCurso->mat_trm_id;
            $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($idTurma);

            if (!$ambiente) {
                return;
            }

            $ambServico = $ambiente->integracao();

            // url do ambiente
            $param['url'] = $ambiente->amb_url;
            $param['token'] = $ambServico->asr_token;
            $param['functionname'] = $event->getEndpoint();
            $param['action'] = 'CREATE';

            // TODO verificar formato de resposta do ambiente
            // Processar resposta
            $response = Moodle::send($param);

            $status = 3;

            if (array_key_exists('status', $response)) {
                if ($response['status'] == 'success') {
                    $status = 2;
                }
            }

            // Log individual de cada item
            foreach ($event->getItems() as $item) {
                event(new UpdateSincronizacaoEvent($item, $status, $response['message']));
            }
        } catch (ConnectException | ClientException | \Exception $exception) {

            if (env('app.debug')) {
                throw $exception;
            }

            foreach ($event->getItems() as $item) {
                event(new UpdateSincronizacaoEvent($item, 3, get_class($exception), $event->getAction()));
            }

        }
    }
}