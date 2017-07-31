<?php

namespace Harpia\Event;

use Modulos\Core\Model\BaseModel;

/**
 * Eventos de sincronizacao com o Moodle
 * @package Harpia\Event
 */
abstract class SyncEvent extends Event
{
    /**
     * Array com os endpoints do plugin de integração ( [tabela][acao] => endpoint )
     * @var array
     */
    protected $endpoints = [
        'acd_turmas' => [
            'CREATE' => 'local_integration_create_course',
            'UPDATE' => 'local_integration_update_course',
            'DELETE' => 'local_integration_delete_course',
        ],
        'acd_grupos' => [
            'CREATE' => 'local_integration_create_group',
            'UPDATE' => 'local_integration_update_group',
            'DELETE' => 'local_integration_delete_group',
        ],
        'acd_ofertas_disciplinas' => [
            'CREATE' => 'local_integration_create_discipline',
            'UPDATE' => 'local_integration_update_discipline',
            'DELETE' => 'local_integration_delete_discipline',
        ],
        'acd_matriculas' => [
            'CREATE' => 'local_integracao_enrol_student',
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

    public function __construct(BaseModel $entry, $action, $extra = null)
    {
        parent::__construct($entry, $action, $extra);
    }

    /**
     * Retorna o endpoint correspondente ao evento de sincronizacao
     * @return string
     */
    public function getEndpoint()
    {
        if (isset($this->endpoints[$this->entry->getTable()][$this->action])) {
            return $this->endpoints[$this->entry->getTable()][$this->action];
        }

        return "";
    }
}
