<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// --------------------------------------------------------------Módulo RH---------------------------------------------------
Breadcrumbs::for('rh.index.index', function (BreadcrumbTrail $trail) {
    $trail->push('Recursos Humanos', route('rh.index.index'));
});

// ÁREAS DE CONHECIMENTO
Breadcrumbs::for('rh.areasconhecimentos.index', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.index.index');
    $trail->push('Áreas de Conhecimento', route('rh.areasconhecimentos.index'));
});

Breadcrumbs::for('rh.areasconhecimentos.create', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.areasconhecimentos.index');
    $trail->push('Criar Área de Conhecimento');
});

Breadcrumbs::for('rh.areasconhecimentos.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.areasconhecimentos.index');
    $trail->push('Editar Área de Conhecimento');
});

// BANCOS
Breadcrumbs::for('rh.bancos.index', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.index.index');
    $trail->push('Bancos', route('rh.bancos.index'));
});

Breadcrumbs::for('rh.bancos.create', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.bancos.index');
    $trail->push('Criar Banco');
});

Breadcrumbs::for('rh.bancos.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.bancos.index');
    $trail->push('Editar Banco');
});

// VÍNCULOS
Breadcrumbs::for('rh.vinculos.index', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.index.index');
    $trail->push('Vínculos', route('rh.vinculos.index'));
});

Breadcrumbs::for('rh.vinculos.create', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.vinculos.index');
    $trail->push('Criar Vínculo');
});

Breadcrumbs::for('rh.vinculos.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.vinculos.index');
    $trail->push('Editar Vínculo');
});

// PERÍODOS LABORAIS
Breadcrumbs::for('rh.periodoslaborais.index', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.index.index');
    $trail->push('Períodos Laborais', route('rh.periodoslaborais.index'));
});

Breadcrumbs::for('rh.periodoslaborais.create', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.periodoslaborais.index');
    $trail->push('Criar Período Laboral');
});

Breadcrumbs::for('rh.periodoslaborais.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.periodoslaborais.index');
    $trail->push('Editar Período Laboral');
});

// COLABORADORES
Breadcrumbs::for('rh.colaboradores.index', function (BreadcrumbTrail $trail) {
    $filtros = session('filtros_colaboradores', []);
    $trail->parent('rh.index.index');
    $trail->push('Colaboradores', route('rh.colaboradores.index', $filtros));
});

Breadcrumbs::for('rh.colaboradores.create', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.colaboradores.index');
    $trail->push('Criar Colaborador');
});

Breadcrumbs::for('rh.colaboradores.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.colaboradores.index');
    $trail->push('Editar Colaborador');
});

Breadcrumbs::for('rh.colaboradores.status', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.colaboradores.index');
    $trail->push('Status do Colaborador');
});

Breadcrumbs::for('rh.colaboradores.matricula', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.colaboradores.index');
    $trail->push('Matrícula do Colaborador');
});

Breadcrumbs::for('rh.colaboradores.matricula.create', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.colaboradores.index');
    $trail->push('Criar Matrícula');
});

Breadcrumbs::for('rh.colaboradores.movimentacaosetor.index', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.colaboradores.index');
    $trail->push('Movimentação de Setor');
});

Breadcrumbs::for('rh.colaboradores.movimentacaosetor.funcao.create', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.colaboradores.movimentacaosetor.index');
    $trail->push('Adicionar Função');
});

Breadcrumbs::for('rh.colaboradores.movimentacaosetor.funcao.delete', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.colaboradores.movimentacaosetor.index');
    $trail->push('Remover Função');
});

Breadcrumbs::for('rh.colaboradores.movimentacaosetor.funcao.remove', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.colaboradores.movimentacaosetor.index');
    $trail->push('Remover Função');
});

Breadcrumbs::for('rh.colaboradores.show', function (BreadcrumbTrail $trail, $colaboradorId) {
    $trail->parent('rh.colaboradores.index');
    $trail->push('Visualizar Colaborador', route('rh.colaboradores.show', ['id' => $colaboradorId]));
});


Breadcrumbs::for('rh.colaboradores.horastrabalhadas', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.colaboradores.index');
    $trail->push('Horas Trabalhadas');
});

Breadcrumbs::for('rh.horastrabalhadas.horastrabalhadasdiariasporperiodolaboral', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.horastrabalhadas.index');
    $trail->push('Horas Trabalhadas Diárias por Período Laboral');
});

// FUNÇÕES
Breadcrumbs::for('rh.funcoes.index', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.index.index');
    $trail->push('Funções', route('rh.funcoes.index'));
});

Breadcrumbs::for('rh.funcoes.create', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.funcoes.index');
    $trail->push('Criar Função');
});

Breadcrumbs::for('rh.funcoes.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.funcoes.index');
    $trail->push('Editar Função');
});

// SETORES
Breadcrumbs::for('rh.setores.index', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.index.index');
    $trail->push('Setores', route('rh.setores.index'));
});

Breadcrumbs::for('rh.setores.create', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.setores.index');
    $trail->push('Criar Setor');
});

Breadcrumbs::for('rh.setores.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.setores.index');
    $trail->push('Editar Setor');
});

// HORAS TRABALHADAS
Breadcrumbs::for('rh.horastrabalhadas.index', function (BreadcrumbTrail $trail) {
    $filtros = session('filtros_horas_trabalhadas', []);
    $trail->parent('rh.index.index');
    $trail->push('Horas Trabalhadas', route('rh.horastrabalhadas.index', $filtros));
});

Breadcrumbs::for('rh.horastrabalhadas.justificativas.index', function (BreadcrumbTrail $trail, $jus_htr_id) {
    $filtros = session('filtros_horas_trabalhadas', []);
    $trail->parent('rh.horastrabalhadas.index');
    $trail->push('Justificativas', route('rh.horastrabalhadas.justificativas.index', array_merge(['id' => $jus_htr_id], $filtros)));
});

Breadcrumbs::for('rh.horastrabalhadas.justificativas.show', function (BreadcrumbTrail $trail, $jus_htr_id) {
    $trail->parent('rh.horastrabalhadas.justificativas.index', $jus_htr_id);
    $trail->push('Visualizar Justificativa');
});

Breadcrumbs::for('rh.horastrabalhadas.justificativas.create', function (BreadcrumbTrail $trail, $jus_htr_id) {
    $filtros = session('filtros_horas_trabalhadas', []);
    $trail->parent('rh.horastrabalhadas.justificativas.index', $jus_htr_id);
    $trail->push('Criar Justificativa', route('rh.horastrabalhadas.justificativas.create', array_merge(['jus_htr_id' => $jus_htr_id], $filtros)));
});

Breadcrumbs::for('rh.horastrabalhadas.justificativas.edit', function (BreadcrumbTrail $trail, $jus_htr_id, $jus_id) {
    $filtros = session('filtros_horas_trabalhadas', []);
    $trail->parent('rh.horastrabalhadas.justificativas.index', $jus_htr_id);
    $trail->push('Editar Justificativa', route('rh.horastrabalhadas.justificativas.edit', array_merge(['jus_htr_id' => $jus_htr_id, 'jus_id' => $jus_id], $filtros)));
});

// FONTES PAGADORAS
Breadcrumbs::for('rh.fontespagadoras.index', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.index.index');
    $trail->push('Fontes Pagadoras', route('rh.fontespagadoras.index'));
});

Breadcrumbs::for('rh.fontespagadoras.show', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.fontespagadoras.index');
    $trail->push('Visualizar Fonte Pagadora');
});

Breadcrumbs::for('rh.fontespagadoras.create', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.fontespagadoras.index');
    $trail->push('Criar Fonte Pagadora');
});

Breadcrumbs::for('rh.fontespagadoras.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.fontespagadoras.index');
    $trail->push('Editar Fonte Pagadora');
});

// ATIVIDADES EXTRAS COLABORADORES
Breadcrumbs::for('rh.colaboradores.atividadesextrascolaboradores.create', function (BreadcrumbTrail $trail, $colaboradorId) {
    $trail->parent('rh.colaboradores.show', $colaboradorId);
    $trail->push('Criar Atividade Extra');
});


Breadcrumbs::for('rh.colaboradores.atividadesextrascolaboradores.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.colaboradores.index');
    $trail->push('Editar Atividade Extra');
});

// PERÍODOS AQUISITIVOS
Breadcrumbs::for('rh.colaboradores.periodosaquisitivos.create', function (BreadcrumbTrail $trail, $colaboradorId) {
    $trail->parent('rh.colaboradores.show', $colaboradorId);
    $trail->push('Criar Período Aquisitivo');
});

Breadcrumbs::for('rh.colaboradores.periodosaquisitivos.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.colaboradores.index');
    $trail->push('Editar Período Aquisitivo');
});

Breadcrumbs::for('rh.colaboradores.periodosaquisitivos.confirm', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.colaboradores.index');
    $trail->push('Confirmar Período Aquisitivo');
});

// CONTAS COLABORADORES
Breadcrumbs::for('rh.colaboradores.contascolaboradores.create', function (BreadcrumbTrail $trail, $colaboradorId) {
    $trail->parent('rh.colaboradores.show', $colaboradorId);
    $trail->push('Criar Conta de Colaborador');
});


Breadcrumbs::for('rh.colaboradores.contascolaboradores.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.colaboradores.index');
    $trail->push('Editar Conta de Colaborador');
});

// VÍNCULOS FONTES PAGADORAS
Breadcrumbs::for('rh.fontespagadoras.vinculosfontespagadoras.create', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.fontespagadoras.index');
    $trail->push('Criar Vínculo com Fonte Pagadora');
});

Breadcrumbs::for('rh.fontespagadoras.vinculosfontespagadoras.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.fontespagadoras.index');
    $trail->push('Editar Vínculo com Fonte Pagadora');
});

// SALÁRIOS COLABORADORES
Breadcrumbs::for('rh.colaboradores.salarioscolaboradores.create', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.colaboradores.index');
    $trail->push('Criar Salário de Colaborador');
});

Breadcrumbs::for('rh.colaboradores.salarioscolaboradores.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.colaboradores.index');
    $trail->push('Editar Salário de Colaborador');
});

// CALENDÁRIOS
Breadcrumbs::for('rh.calendarios.index', function (BreadcrumbTrail $trail) {
    $trail->parent('rh.index.index');
    $trail->push('Calendários', route('rh.calendarios.index'));
});
