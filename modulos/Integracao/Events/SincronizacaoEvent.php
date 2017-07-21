<?php

namespace Modulos\Integracao\Events;

use Harpia\Event\Event;
use Modulos\Integracao\Models\Sincronizacao;

/**
 * Class SincronizacaoEvent
 *
 * Deve ser disparado para refazer uma unica sincronizacao
 *
 * @package Modulos\Integracao\Events
 */
class SincronizacaoEvent extends Event
{
    public function __construct(Sincronizacao $entry, $action = 'SINCRONIZACAO_MANUAL', $extra = null)
    {
        parent::__construct($entry, $action, $extra);
    }

    /**
     * Retorna a funcao a ser chamada no Moodle para migrar esta sincronizacao
     *
     * @return string
     */
    public function getMoodleFunction()
    {
        $operacoesBasicas = [
            'CREATE',
            'UPDATE',
            'DELETE'
        ];

        // Endpoints de turma
        if ($this->entry->sym_table == 'acd_turmas' && in_array($this->entry->sym_action, $operacoesBasicas)) {
            return 'local_integracao_'.strtolower($this->entry->sym_action).'_course';
        }

        // Endpoints de grupos
        if ($this->entry->sym_table == 'acd_grupos' && in_array($this->entry->sym_action, $operacoesBasicas)) {
            return 'local_integracao_'.strtolower($this->entry->sym_action).'_group';
        }

        // Endpoints de Ofertas de disciplina
        if ($this->entry->sym_table == 'acd_ofertas_disciplinas' && in_array($this->entry->sym_action, $operacoesBasicas)) {
            return 'local_integracao_'.strtolower($this->entry->sym_action).'_discipline';
        }

        // Matricula de aluno em curso
        if ($this->entry->sym_table == 'acd_matriculas' && $this->entry->sym_action == 'CREATE') {
            return 'local_integracao_enrol_student';
        }

        // Matricula de aluno em oferta de disciplina
        if ($this->entry->sym_table == 'acd_matriculas_ofertas_disciplinas' && $this->entry->sym_action == 'CREATE') {
            return 'local_integracao_enrol_student_discipline';
        }

        // Atualizacao de dados de usuario
        if ($this->entry->sym_table == 'gra_pessoas' && $this->entry->sym_action == 'UPDATE') {
            return 'local_integracao_update_user';
        }

        // Atualizar grupo do aluno
        if ($this->entry->sym_table == 'acd_matriculas' && $this->entry->sym_action == 'UPDATE_GRUPO_ALUNO') {
            return 'local_integracao_change_student_group';
        }

        // Remover aluno de grupo
        if ($this->entry->sym_table == 'acd_matriculas' && $this->entry->sym_action == 'DELETE_GRUPO_ALUNO') {
            return 'local_integracao_unenrol_student_group';
        }

        // Vincular tutor ao grupo
        if ($this->entry->sym_table == 'acd_tutores_grupos' && $this->entry->sym_action == 'CREATE') {
            return 'local_integracao_enrol_tutor';
        }

        // Desvincular tutor do grupo
        if ($this->entry->sym_table == 'acd_tutores_grupos' && $this->entry->sym_action == 'DELETE') {
            return 'local_integracao_unenrol_tutor_group';
        }

        return "";
    }
}
