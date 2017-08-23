<?php

namespace Harpia\Event;

use Modulos\Integracao\Models\Sincronizacao;
use Harpia\Event\Contracts\SincronizacaoFactoryInterface;

abstract class SincronizacaoFactory extends SincronizacaoEvent implements SincronizacaoFactoryInterface
{
    const EVENTS = [
        // Turma
        'Turma' => [
            'local_integracao_create_course' => \Modulos\Integracao\Events\TurmaMapeadaEvent::class,
            'local_integracao_update_course' => \Modulos\Academico\Events\UpdateTurmaEvent::class,
            'local_integracao_delete_course' => \Modulos\Integracao\Events\TurmaRemovidaEvent::class,
        ],

        // Grupo

        'Grupo' => [
            'local_integracao_create_group' => \Modulos\Academico\Events\CreateGrupoEvent::class,
            'local_integracao_update_group' => \Modulos\Academico\Events\UpdateGrupoEvent::class,
            'local_integracao_delete_group' => \Modulos\Academico\Events\DeleteGrupoEvent::class,
        ],

        // OfertaDisciplina
        'OfertaDisciplina' => [
            'local_integracao_create_discipline' => \Modulos\Academico\Events\CreateOfertaDisciplinaEvent::class,
            'local_integracao_delete_discipline' => \Modulos\Academico\Events\DeleteOfertaDisciplinaEvent::class,
            'local_integracao_change_teacher' => \Modulos\Academico\Events\UpdateProfessorDisciplinaEvent::class,
        ],

        // Matricula

        'Matricula' => [
            'local_integracao_enrol_student' => \Modulos\Academico\Events\CreateMatriculaTurmaEvent::class,
            'local_integracao_change_role_student_course' => \Modulos\Academico\Events\UpdateSituacaoMatriculaEvent::class,
            'local_integracao_change_student_group' => \Modulos\Academico\Events\UpdateGrupoAlunoEvent::class,
            'local_integracao_unenrol_student_group' => \Modulos\Academico\Events\DeleteGrupoAlunoEvent::class,
        ],

        // MatriculaOfertaDisciplina
        'MatriculaOfertaDisciplina' => [
            'local_integracao_enrol_student_discipline' => \Modulos\Academico\Events\CreateMatriculaDisciplinaEvent::class,
        ],

        // TutorGrupo
        'TutorGrupo' => [
            'local_integracao_enrol_tutor' => \Modulos\Academico\Events\CreateVinculoTutorEvent::class,
            'local_integracao_unenrol_tutor_group' => \Modulos\Academico\Events\DeleteVinculoTutorEvent::class,
        ],

        // Pessoa
        'Pessoa' => [
            'local_integracao_update_user' => \Modulos\Geral\Events\UpdatePessoaEvent::class
        ],
    ];

    /**
     * Cria um evento de sincronizacao com base em seu registro na tabela de sincronizacao
     * @param Sincronizacao $sincronizacao
     * @throws \Exception
     */
    final public static function factorySincronizacao(Sincronizacao $sincronizacao)
    {
        $entry = $sincronizacao;

        $event = self::makeEvent($sincronizacao);
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
     * Recria o evento correspodente para a classe passada
     * @param Sincronizacao $sincronizacao
     */
    final private static function makeEvent(Sincronizacao $sincronizacao)
    {
        $dependencies = self::getDependencies($sincronizacao);
    }

    /**
     * Resolve as dependencias para um evento especifico
     * @param Sincronizacao $sincronizacao
     * @return array
     */
    final private static function getDependencies(Sincronizacao $sincronizacao)
    {
        $endpoint = self::getEventEndpoint($sincronizacao->sym_table, $sincronizacao->sym_action);

        return [
            'entry' => self::getEventEntry($endpoint, $sincronizacao->sym_table_id),
            'extra' => $sincronizacao->sym_extra,
        ];
    }

    /**
     * Recupera o
     * @param $endpoint
     * @param $id
     * @return \Modulos\Core\Model\BaseModel
     * @throws \Exception
     */
    final public static function getEventEntry($endpoint, $id)
    {
        if (in_array($endpoint, self::EVENTS['Turma'])) {
            return \Modulos\Academico\Models\Turma::find($id);
        }

        if (in_array($endpoint, self::EVENTS['Grupo'])) {
            return \Modulos\Academico\Models\Grupo::find($id);
        }

        if (in_array($endpoint, self::EVENTS['OfertaDisciplina'])) {
            return \Modulos\Academico\Models\OfertaDisciplina::find($id);
        }

        if (in_array($endpoint, self::EVENTS['Matricula'])) {
            return \Modulos\Academico\Models\Matricula::find($id);
        }

        if (in_array($endpoint, self::EVENTS['MatriculaOfertaDisciplina'])) {
            return \Modulos\Academico\Models\MatriculaOfertaDisciplina::find($id);
        }

        if (in_array($endpoint, self::EVENTS['TutorGrupo'])) {
            return \Modulos\Academico\Models\TutorGrupo::find($id);
        }

        if (in_array($endpoint, self::EVENTS['Pessoa'])) {
            return \Modulos\Geral\Models\Pessoa::find($id);
        }

        throw new \Exception("Endpoint não corresponde a nenhum evento mapeado");
    }
}
