<?php
/**
 * Created by PhpStorm.
 * User: felipe
 * Date: 27/07/17
 * Time: 13:06
 */

namespace Modulos\Academico\Listeners;

use Harpia\Moodle\Moodle;
use Modulos\Academico\Events\AlterarProfessorOfertaDisciplinaEvent;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Integracao\Events\UpdateSincronizacaoEvent;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Integracao\Repositories\SincronizacaoRepository;

class MigrarAlteracaoProfessorOfertaDisciplinaListener
{
    protected $sincronizacaoRepository;
    protected $ofertaDisciplinaRepository;
    protected $ambienteVirtualRepository;

    public function __construct(
        SincronizacaoRepository $sincronizacaoRepository,
        OfertaDisciplinaRepository $ofertaDisciplinaRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->sincronizacaoRepository = $sincronizacaoRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function handle(AlterarProfessorOfertaDisciplinaEvent $event)
    {
        $registrosMigrar = $this->sincronizacaoRepository->findBy([
            'sym_table' => 'acd_ofertas_disciplinas',
            'sym_status' => 1,
            'sym_action' => $event->getAction()
        ]);

        if ($registrosMigrar->count()) {
            foreach ($registrosMigrar as $reg) {
                $ofertaDisciplina = $this->ofertaDisciplinaRepository->find($reg->sym_table_id);

                $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($ofertaDisciplina->ofd_trm_id);

                if ($ambiente) {
                    $param = [];

                    $param['url'] = $ambiente->url;
                    $param['token'] = $ambiente->token;
                    $param['functioname'] = 'local_integracao_change_teacher';
                    $param['action'] = $event->getAction();

                    $nome = explode(" ", $ofertaDisciplina->professor->pessoa->pes_nome);
                    $firstName = array_shift($nome);
                    $lastName = implode(" ", $nome);

                    $discipline = [
                        'ofd_id' => $ofertaDisciplina->ofd_id,
                        'teacher' => [
                            'pes_id' => $ofertaDisciplina->professor->prf_pes_id,
                            'firstname' => $firstName,
                            'lastname' => $lastName,
                            'email' => $ofertaDisciplina->professor->pessoa->pes_email,
                            'username' => $ofertaDisciplina->professor->pessoa->pes_email,
                            'password' => 'changeme',
                            'city' => $ofertaDisciplina->professor->pessoa->pes_cidade
                        ]
                    ];

                    $param['data']['discipline'] = $discipline;

                    try {
                        $moodle = new Moodle();
                        $retorno = $moodle->send($param);

                        $status = 3;

                        if (array_key_exists('status', $retorno)) {
                            if ($retorno['status'] == 'success') {
                                $status = 2;
                            }
                        }

                        event(new UpdateSincronizacaoEvent($ofertaDisciplina, $status, $retorno['message']));
                    } catch (\GuzzleHttp\Exception\ConnectException $e) {
                        event(new UpdateSincronizacaoEvent($ofertaDisciplina, 3, $e->getMessage()));
                    } catch (\GuzzleHttp\Exception\ClientException $e) {
                        event(new UpdateSincronizacaoEvent($ofertaDisciplina, 3, $e->getMessage()));
                    } catch (\Exception $e) {
                        event(new UpdateSincronizacaoEvent($ofertaDisciplina, 3, 'Erro ao tentar sincronizar. Tente novamente mais tarde.'));
                    }

                    return true;
                }
            }
        }
    }
}
