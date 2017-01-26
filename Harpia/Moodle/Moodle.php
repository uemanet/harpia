<?php

namespace Harpia\Moodle;

use Modulos\Academico\Models\Turma;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Integracao\Repositories\AmbienteServicoRepository;
use Modulos\Integracao\Repositories\AmbienteTurmaRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Moodle
{
    private $ambienteServicoRepository;
    private $ambienteTurmaRepository;
    private $ambienteVirtualRepository;
    private $cursoRepository;
    private $turmaRepository;
    private $periodoLetivoRepository;
    private $cliente;

    public function __construct(AmbienteServicoRepository $ambienteServicoRepository,
                                AmbienteTurmaRepository $ambienteTurmaRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository,
                                TurmaRepository $turmaRepository,
                                CursoRepository $cursoRepository,
                                PeriodoLetivoRepository $periodoLetivoRepository)
    {
        $this->ambienteServicoRepository = $ambienteServicoRepository;
        $this->ambienteTurmaRepository = $ambienteTurmaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->turmaRepository = $turmaRepository;
        $this->cursoRepository = $cursoRepository;
        $this->periodoLetivoRepository = $periodoLetivoRepository;

        $this->cliente = new Client();
    }


    public function migrarTurma(Turma $turma)
    {
        $function = 'local_integracao_create_course';

        $ambiente = $this->getAmbiente($turma->trm_id);

        $data = [];
        $parameters = [];

        $data['course']['trm_id'] = $turma->trm_id;
        $data['course']['category'] = 1;
        $data['course']['shortname'] = $this->turmaShortName($turma); // CC_TURMA_A_2017.1
        $data['course']['fullname'] = $this->turmaFullName($turma); // Ciência da Computação - Turma A - 2017.1
        $data['course']['summaryformats'] = 1;
        $data['course']['format'] = 'topics';
        $data['course']['numsections'] = 0;

        $parameters['data'] = $data;

        $url = $this->getUrl($ambiente, $function);
        return $this->request($url, $parameters);
    }

    /**
     * @param Turma $turma
     * @return mixed|string
     */
    private function turmaShortName(Turma $turma)
    {
        $cursoId = $this->turmaRepository->getCurso($turma->trm_id);
        $curso = $this->cursoRepository->find($cursoId);
        $periodoLetivo = $this->periodoLetivoRepository->find($turma->trm_per_id);

        $shortName = $curso->crs_sigla .' ' . $turma->trm_nome . ' ' . $periodoLetivo->per_nome;
        $shortName = str_replace(' ', '_', $shortName);

        return $shortName;
    }

    /**
     * @param Turma $turma
     * @return string
     */
    private function turmaFullName(Turma $turma)
    {
        $cursoId = $this->turmaRepository->getCurso($turma->trm_id);
        $curso = $this->cursoRepository->find($cursoId);
        $periodoLetivo = $this->periodoLetivoRepository->find($turma->trm_per_id);

        $fullname = $curso->crs_nome .' - ' . $turma->trm_nome . ' - ' . $periodoLetivo->per_nome;

        return $fullname;
    }


    /**
     * Retorna os dados de um ambiente a partir da turma
     * @param $turmaId
     * @return mixed
     */
    private function getAmbiente($turmaId)
    {
        $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($turmaId);

        return array_pop($ambiente);
    }

    /**
     * Monta a URL apropriada para a requisicao ao Moodle
     * @param array $ambiente
     * @param $function
     * @return string
     */
    private function getUrl(array $ambiente, $function)
    {
        return $ambiente['url'] .
            '/webservice/rest/server.php?wstoken=' .
            $ambiente['token'] .
            '&wsfunction=' . $function . '&moodlewsrestformat=json';
    }

    /**
     * @param $url
     * @param $data
     * @param string $method
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    private function request($url, $data, $method = 'POST')
    {
        return $this->cliente->request($method, $url, $data);
    }
}
