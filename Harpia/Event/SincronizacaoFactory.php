<?php

namespace Harpia\Event;

use Modulos\Academico\Models\Grupo;
use Modulos\Academico\Models\Matricula;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;
use Modulos\Academico\Models\OfertaDisciplina;
use Modulos\Academico\Models\Turma;
use Modulos\Academico\Models\TutorGrupo;
use Modulos\Geral\Models\Pessoa;
use Modulos\Integracao\Models\Sincronizacao;
use Harpia\Event\Contracts\SincronizacaoFactoryInterface;

abstract class SincronizacaoFactory extends SincronizacaoEvent implements SincronizacaoFactoryInterface
{
    protected const EVENTS = [
        'Turma' => [
            'local_integracao_create_course' => \Modulos\Integracao\Events\TurmaMapeadaEvent::class,
            'local_integracao_update_course' => \Modulos\Academico\Events\UpdateTurmaEvent::class,
            'local_integracao_delete_course' => \Modulos\Integracao\Events\TurmaRemovidaEvent::class,
        ],

        'Grupo' => [
            'local_integracao_create_group' => \Modulos\Academico\Events\CreateGrupoEvent::class,
            'local_integracao_update_group' => \Modulos\Academico\Events\UpdateGrupoEvent::class,
            'local_integracao_delete_group' => \Modulos\Academico\Events\DeleteGrupoEvent::class,
        ],

        'OfertaDisciplina' => [
            'local_integracao_create_discipline' => \Modulos\Academico\Events\CreateOfertaDisciplinaEvent::class,
            'local_integracao_delete_discipline' => \Modulos\Academico\Events\DeleteOfertaDisciplinaEvent::class,
            'local_integracao_change_teacher' => \Modulos\Academico\Events\UpdateProfessorDisciplinaEvent::class,
        ],

        'Matricula' => [
            'local_integracao_enrol_student' => \Modulos\Academico\Events\CreateMatriculaTurmaEvent::class,
            'local_integracao_unenrol_student' => \Modulos\Academico\Events\DeleteMatriculaTurmaEvent::class,
            'local_integracao_change_role_student_course' => \Modulos\Academico\Events\UpdateSituacaoMatriculaEvent::class,
            'local_integracao_change_student_group' => \Modulos\Academico\Events\UpdateGrupoAlunoEvent::class,
            'local_integracao_unenrol_student_group' => \Modulos\Academico\Events\DeleteGrupoAlunoEvent::class,
        ],

        'MatriculaOfertaDisciplina' => [
            'local_integracao_enrol_student_discipline' => \Modulos\Academico\Events\CreateMatriculaDisciplinaEvent::class,
            'local_integracao_unenrol_student_discipline' => \Modulos\Academico\Events\DeleteMatriculaDisciplinaEvent::class
        ],

        'TutorGrupo' => [
            'local_integracao_enrol_tutor' => \Modulos\Academico\Events\CreateVinculoTutorEvent::class,
            'local_integracao_unenrol_tutor_group' => \Modulos\Academico\Events\DeleteVinculoTutorEvent::class,
        ],

        'Pessoa' => [
            'local_integracao_update_user' => \Modulos\Geral\Events\UpdatePessoaEvent::class
        ],
    ];

    /**
     * Make an event fired previously
     *
     * @param Sincronizacao $sincronizacao
     * @return SincronizacaoEvent
     * @throws \Exception
     */
    final public static function factory(Sincronizacao $sincronizacao)
    {
        $event = self::makeEvent($sincronizacao);

        // O evento equivale a uma nova tentativa de migracao
        $event->setAttemptAsNew();

        return $event;
    }

    /**
     * Recria o evento correspodente para a classe passada
     * @throws \Exception
     * @param Sincronizacao $sincronizacao
     * @return \Harpia\Event\SincronizacaoEvent
     */
    final private static function makeEvent(Sincronizacao $sincronizacao)
    {
        $endpoint = self::getEventEndpoint($sincronizacao->sym_table, $sincronizacao->sym_action);

        $dependencies = self::getDependencies($sincronizacao);
        $eventClass = self::getEventClass($endpoint);

        return new $eventClass($dependencies['entry'], $dependencies['extra']);
    }

    /**
     * Retorna o endpoint correspondent ao evento
     * @param $table
     * @param $action
     * @return string
     */
    final private static function getEventEndpoint($table, $action)
    {
        if (isset(self::ENDPOINTS[$table][$action])) {
            return self::ENDPOINTS[$table][$action];
        }

        return "";
    }

    /**
     * Resolve as dependencias para um evento especifico
     * @throws \Exception
     * @param Sincronizacao $sincronizacao
     * @return array
     */
    final private static function getDependencies(Sincronizacao $sincronizacao)
    {
        $endpoint = self::getEventEndpoint($sincronizacao->sym_table, $sincronizacao->sym_action);
        $isDelete = self::isDeleteEvent($sincronizacao);

        return [
            'entry' => self::getEventEntry($endpoint, $sincronizacao->sym_table_id, $isDelete),
            'extra' => $sincronizacao->sym_extra,
        ];
    }

    /**
     * Verifica se o evento corresponde a uma exclusao
     *
     * @param Sincronizacao $sincronizacao
     * @return bool
     */
    final private static function isDeleteEvent(Sincronizacao $sincronizacao)
    {
        return (bool)(strtolower($sincronizacao->sym_action) == 'delete');
    }

    /**
     * Recupera o registro correspondente ao evento a ser fabricado
     * @param $endpoint
     * @param $id
     * @param $isDelete true if is an delete event
     * @return \Modulos\Core\Model\BaseModel
     * @throws \Exception
     */
    final public static function getEventEntry($endpoint, $id, $isDelete = false)
    {

        if (in_array($endpoint, array_keys(self::EVENTS['Turma']))) {
            return !$isDelete ? Turma::find($id) : self::mockDeletedEntry(Turma::class, 'trm_id', $id);
        }

        if (in_array($endpoint, array_keys(self::EVENTS['Grupo']))) {
            return !$isDelete ? Grupo::find($id) : self::mockDeletedEntry(Grupo::class, 'grp_id', $id);
        }

        if (in_array($endpoint, array_keys(self::EVENTS['OfertaDisciplina']))) {
            return !$isDelete ? OfertaDisciplina::find($id) : self::mockDeletedEntry(OfertaDisciplina::class, 'ofd_id', $id);
        }

        if (in_array($endpoint, array_keys(self::EVENTS['Matricula']))) {
            return !$isDelete ? Matricula::find($id) : self::mockDeletedEntry(Matricula::class, 'mat_id', $id);
        }

        if (in_array($endpoint, array_keys(self::EVENTS['MatriculaOfertaDisciplina']))) {
            return !$isDelete ? MatriculaOfertaDisciplina::find($id) : self::mockDeletedEntry(MatriculaOfertaDisciplina::class, 'mof_id', $id);
        }

        if (in_array($endpoint, array_keys(self::EVENTS['TutorGrupo']))) {
            return TutorGrupo::find($id);
        }

        if (in_array($endpoint, array_keys(self::EVENTS['Pessoa']))) {
            return Pessoa::find($id);
        }

        throw new \Exception("Endpoint não corresponde a nenhum evento mapeado");
    }

    /**
     * Mock deleted entries for fire failed delete sync events
     *
     * @param string $class
     * @param string $primaryKey
     * @param int $id
     * @return mixed
     */
    final private static function mockDeletedEntry(string $class, string $primaryKey, int $id)
    {
        $entry = new $class();
        $entry->$primaryKey = $id;
        return $entry;
    }

    /**
     * Retorna a classe correspondente ao evento
     * @param $endpoint
     * @throws \Exception
     * @return string
     */
    final private static function getEventClass($endpoint)
    {
        foreach (self::EVENTS as $eventGroup) {
            if (isset($eventGroup[$endpoint])) {
                return $eventGroup[$endpoint];
            }
        }

        throw new \Exception("Endpoint não corresponde a nenhum evento mapeado");
    }
}
