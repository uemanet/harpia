<?php

namespace Harpia\Moodle;

use Modulos\Academico\Models\Turma;
use Modulos\Integracao\Repositories\AmbienteServicoRepository;
use Modulos\Integracao\Repositories\AmbienteTurmaRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;

class Moodle
{
    private $ambienteServicoRepository;
    private $ambienteTurmaRepository;
    private $ambienteVirtualRepository;

    public function __construct(AmbienteServicoRepository $ambienteServicoRepository,
                                AmbienteTurmaRepository $ambienteTurmaRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        $this->ambienteServicoRepository = $ambienteServicoRepository;
        $this->ambienteTurmaRepository = $ambienteTurmaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }


    public function migrarTurma(Turma $turma, array $options)
    {
        $function = 'local_integracao_create_course';
        $action   = 'INSERT';
        $token    = ''; // Get Token for

        $data = [];
        $parameters = [];

        $data['course']['trm_id'] = $turma->trm_id;
        $data['course']['category'] = 1;
        $data['course']['shortname'] = "";
        $data['course']['fullname'] = '';
        $data['course']['summaryformats'] = 1;
        $data['course']['format'] = 'topics';
        $data['course']['numsections'] = 0;
    }


    private function getAmbiente($turma)
    {
    }
}
