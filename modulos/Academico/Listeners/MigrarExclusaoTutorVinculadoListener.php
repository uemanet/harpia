<?php

namespace Modulos\Academico\Listeners;

use Modulos\Academico\Events\DeleteTutorVinculadoEvent;
use Modulos\Academico\Repositories\GrupoRepository;
use Modulos\Academico\Repositories\TutorGrupoRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class MigrarExclusaoTutorVinculadoListener
{
    protected $sincronizacaoRepository;
    protected $ambienteVirtualRepository;
    protected $tutorGrupoRepository;
    protected $tutorRepository;
    protected $grupoRepository;
    protected $pessoaRepository;

    public function __construct(SincronizacaoRepository $sincronizacaoRepository,
                                AmbienteVirtualRepository $ambienteVirtualRepository,
                                TutorGrupoRepository $tutorGrupoRepository,
                                GrupoRepository $grupoRepository,
                                PessoaRepository $pessoaRepository)
    {
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->tutorGrupoRepository = $tutorGrupoRepository;
        $this->grupoRepository = $grupoRepository;
        $this->pessoaRepository = $pessoaRepository;
    }

    public function handle(DeleteTutorVinculadoEvent $event)
    {
        $tutoresMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'acd_tutores_grupos',
            'sym_status' => 1,
            'sym_action' => "DELETE"
        ]);

        if ($tutoresMigrar->count()) {
            foreach ($tutoresMigrar as $item) {
            }
        }
    }
}
