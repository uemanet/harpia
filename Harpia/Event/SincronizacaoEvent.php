<?php

namespace Harpia\Event;

use Modulos\Core\Model\BaseModel;

/**
 * Eventos de sincronizacao com o Moodle
 * @package Harpia\Event
 */
abstract class SincronizacaoEvent extends Event
{
    /**
     * Array com os endpoints do plugin de integração ( [tabela][acao] => endpoint )
     * @var array
     */
    protected const ENDPOINTS = [
        'acd_turmas' => [
            'CREATE' => 'local_integracao_create_course',
            'UPDATE' => 'local_integracao_update_course',
            'DELETE' => 'local_integracao_delete_course',
        ],
        'acd_grupos' => [
            'CREATE' => 'local_integracao_create_group',
            'UPDATE' => 'local_integracao_update_group',
            'DELETE' => 'local_integracao_delete_group',
        ],
        'acd_ofertas_disciplinas' => [
            'CREATE' => 'local_integracao_create_discipline',
            'DELETE' => 'local_integracao_delete_discipline',
            'UPDATE_PROFESSOR_OFERTA_DISCIPLINA' => 'local_integracao_change_teacher',
        ],
        'acd_matriculas_ofertas_disciplinas' => [
            'CREATE' => 'local_integracao_enrol_student_discipline',
            'DELETE' => 'local_integracao_unenrol_student_discipline'
        ],
        'acd_matriculas' => [
            'CREATE' => 'local_integracao_enrol_student',
            'DELETE' => 'local_integracao_unenrol_student',
            'UPDATE_SITUACAO_MATRICULA' => 'local_integracao_change_role_student_course',
            'UPDATE_GRUPO_ALUNO' => 'local_integracao_change_student_group',
            'DELETE_GRUPO_ALUNO' => 'local_integracao_unenrol_student_group',
        ],
        'acd_tutores_grupos' => [
            'CREATE' => 'local_integracao_enrol_tutor',
            'DELETE' => 'local_integracao_unenrol_tutor_group'
        ],
        'gra_pessoas' => [
            'UPDATE' => 'local_integracao_update_user'
        ]
    ];

    /**
     * Array com os endpoints do plugin de integração ( [tabela][acao] => endpoint )
     * @var array
     */
    protected const ENDPOINTS_V2 = [
        'acd_turmas' => [
            'CREATE' => 'local_integracao_v2_create_course',
            'DELETE' => 'local_integracao_v2_delete_course'
        ],
        'acd_ofertas_disciplinas' => [
            'CREATE' => 'local_integracao_v2_create_discipline',
            'DELETE' => 'local_integracao_v2_delete_discipline',
            'UPDATE_PROFESSOR_OFERTA_DISCIPLINA' => 'local_integracao_v2_change_teacher',
        ],
        'acd_grupos' => [
            'CREATE' => 'local_integracao_v2_create_group',
            'UPDATE' => 'local_integracao_v2_update_group',
            'DELETE' => 'local_integracao_v2_delete_group',
        ],
        'acd_tutores_grupos' => [
            'CREATE' => 'local_integracao_v2_enrol_tutor',
            'DELETE' => 'local_integracao_v2_unenrol_tutor_group'
        ],
        'acd_matriculas' => [
            'CREATE' => 'local_integracao_v2_enrol_student',
            'DELETE' => 'local_integracao_v2_unenrol_student',
            'UPDATE_GRUPO_ALUNO' => 'local_integracao_v2_change_student_group',
            'DELETE_GRUPO_ALUNO' => 'local_integracao_v2_unenrol_student_group',
        ],
        'acd_matriculas_ofertas_disciplinas' => [
            'CREATE' => 'local_integracao_v2_enrol_student_discipline',
            'DELETE' => 'local_integracao_v2_unenrol_student_discipline'
        ],
        'gra_pessoas' => [
            'UPDATE' => 'local_integracao_v2_update_user'
        ]
    ];

    protected $firstAttempt = true;

    public function __construct(BaseModel $entry, $action, $extra = null, $version = 'v1')
    {
        parent::__construct($entry, $action, $extra, $version);
    }

    /**
     * Retorna o endpoint correspondente ao evento de sincronizacao
     * @return string
     */
    public function getEndpoint()
    {
        if (isset(self::ENDPOINTS[$this->entry->getTable()][$this->action])) {
            return self::ENDPOINTS[$this->entry->getTable()][$this->action];
        }

        return "";
    }

    /**
     * Retorna o endpoint correspondente ao evento de sincronizacao na versão 2 da API
     * @return string
     */
    public function getEndpointV2()
    {
        if (isset(self::ENDPOINTS_V2[$this->entry->getTable()][$this->action])) {
            return self::ENDPOINTS_V2[$this->entry->getTable()][$this->action];
        }

        return "";
    }

    /**
     * Primeira tentativa de migracao ?
     * @return bool
     */
    final public function isFirstAttempt()
    {
        return $this->firstAttempt;
    }

    /**
     * Configura o evento como uma nova tentativa de sincronizacao
     */
    final public function setAttemptAsNew()
    {
        $this->firstAttempt = false;
    }
}
