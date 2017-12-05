<?php

/** Factories Modulo Geral */
$factory->define(Modulos\Geral\Models\Pessoa::class, function (Faker\Generator $faker) {
    return [
        'pes_nome' => $faker->name,
        'pes_sexo' => $faker->randomElement(['M', 'F']),
        'pes_email' => $faker->email,
        'pes_telefone' => $faker->phoneNumber,
        'pes_nascimento' => $faker->date('d/m/Y'),
        'pes_mae' => $faker->name,
        'pes_pai' => $faker->name,
        'pes_estado_civil' => $faker->randomElement(['solteiro', 'casado', 'divorciado', 'viuvo(a)', 'uniao_estavel']),
        'pes_naturalidade' => $faker->city,
        'pes_nacionalidade' => $faker->country,
        'pes_raca' => $faker->randomElement(['branco', 'negro', 'amarelo']),
        'pes_necessidade_especial' => $faker->randomElement(['s', 'n']),
        'pes_estrangeiro' => $faker->boolean,
        'pes_endereco' => $faker->streetName,
        'pes_numero' => '1',
        'pes_complemento' => null,
        'pes_cep' => $faker->postcode,
        'pes_cidade' => $faker->city,
        'pes_bairro' => $faker->state,
        'pes_estado' => 'SP'
    ];
});

$factory->define(Modulos\Geral\Models\TipoDocumento::class, function (Faker\Generator $faker) {
    return [
        'tpd_nome' => $faker->word
    ];
});

$factory->define(Modulos\Geral\Models\Documento::class, function (Faker\Generator $faker) {
    $anexo = factory(Modulos\Geral\Models\Anexo::class)->create();

    return [
        'doc_pes_id' => factory(Modulos\Geral\Models\Pessoa::class)->create()->pes_id,
        'doc_tpd_id' => 2,
        'doc_data_expedicao' => $faker->date('d/m/Y'),
        'doc_conteudo' => $faker->creditCardNumber,
        'doc_orgao' => null,
        'doc_observacao' => null,
        'doc_anx_documento' => $anexo->anx_id
    ];
});

$factory->define(Modulos\Geral\Models\Anexo::class, function (Faker\Generator $faker) {
    return [
        'anx_nome' => $faker->word,
        'anx_mime' => $faker->mimeType,
        'anx_extensao' => $faker->word,
        'anx_localizacao' => $faker->sha1,
    ];
});

$factory->define(Modulos\Geral\Models\Configuracao::class, function (Faker\Generator $faker) {
    return [
        'cnf_mod_id' => random_int(1, 4),
        'cnf_nome' => $faker->name,
        'cnf_valor' => $faker->word
    ];
});

$factory->define(Modulos\Geral\Models\Titulacao::class, function (Faker\Generator $faker) {
    return [
        'tit_nome' => $faker->word,
        'tit_peso' => $faker->word,
        'tit_descricao' => $faker->sentence(3),
    ];
});

$factory->define(Modulos\Geral\Models\TitulacaoInformacao::class, function (Faker\Generator $faker) {
    return [
        'tin_pes_id' => factory(\Modulos\Geral\Models\Pessoa::class)->create()->pes_id,
        'tin_tit_id' => factory(\Modulos\Geral\Models\Titulacao::class)->create()->tit_id,
        'tin_titulo' => $faker->word,
        'tin_instituicao' => $faker->word,
        'tin_instituicao_sigla' => $faker->word,
        'tin_instituicao_sede' => $faker->word,
        'tin_anoinicio' => $faker->randomNumber(4),
        'tin_anofim' => $faker->randomNumber(4),
    ];
});

/** Factories Modulo Segurança */
$factory->define(Modulos\Seguranca\Models\Modulo::class, function (Faker\Generator $faker) {
    $rota = $faker->name;

    return [
        'mod_nome' => $rota,
        'mod_slug' => strtolower($rota),
        'mod_descricao' => $faker->sentence(3),
        'mod_icone' => 'fa fa-cog',
        'mod_classes' => 'bg-aqua'
    ];
});

$factory->define(Modulos\Seguranca\Models\Perfil::class, function (Faker\Generator $faker) {
    return [
        'prf_mod_id' => 1,
        'prf_nome' => $faker->name,
        'prf_descricao' => $faker->sentence(3)
    ];
});

$factory->define(Modulos\Seguranca\Models\Permissao::class, function (Faker\Generator $faker) {
    return [
        'prm_nome' => $faker->name,
        'prm_rota' => $faker->name,
        'prm_descricao' => $faker->sentence(3)
    ];
});

$factory->define(Modulos\Seguranca\Models\Usuario::class, function (Faker\Generator $faker) {
    return [
        'usr_pes_id' => factory(Modulos\Geral\Models\Pessoa::class)->create()->pes_id,
        'usr_usuario' => $faker->email,
        'usr_senha' => $faker->password,
        'usr_ativo' => 1
    ];
});

$factory->define(Modulos\Seguranca\Models\MenuItem::class, function (Faker\Generator $faker) {
    $modulo = factory(Modulos\Seguranca\Models\Modulo::class)->create();

    return [
        'mit_mod_id' => $modulo->mod_id,
        'mit_item_pai' => null,
        'mit_nome' => $faker->sentence(1),
        'mit_icone' => 'fa fa-cog',
        'mit_visivel' => 1,
        'mit_rota' => $faker->sentence(1),
        'mit_descricao' => $faker->sentence(1),
        'mit_ordem' => 1
    ];
});

$factory->define(Modulos\Seguranca\Models\Auditoria::class, function (Faker\Generator $faker) {
    $user = factory(Modulos\Seguranca\Models\Usuario::class)->create();

    return [
        'log_usr_id' => $user->usr_id,
        'log_action' => $faker->sentence(1),
        'log_table' => $faker->sentence(1),
        'log_table_id' => $faker->randomNumber(2),
        'log_object' => $faker->sentence(1)
    ];
});

/** Factories Modulo Academico */
$factory->define(Modulos\Academico\Models\Departamento::class, function (Faker\Generator $faker) {
    return [
        'dep_cen_id' => factory(Modulos\Academico\Models\Centro::class)->create()->cen_id,
        'dep_prf_diretor' => factory(Modulos\Academico\Models\Professor::class)->create()->prf_id,
        'dep_nome' => $faker->word
    ];
});

$factory->define(Modulos\Academico\Models\Centro::class, function (Faker\Generator $faker) {
    return [
        'cen_prf_diretor' => factory(Modulos\Academico\Models\Professor::class)->create()->prf_id,
        'cen_nome' => $faker->word,
        'cen_sigla' => $faker->word,
    ];
});

$factory->define(Modulos\Academico\Models\Professor::class, function (Faker\Generator $faker) {
    return [
        'prf_pes_id' => factory(Modulos\Geral\Models\Pessoa::class)->create()->pes_id
    ];
});

$factory->define(Modulos\Academico\Models\PeriodoLetivo::class, function (Faker\Generator $faker) {
    return [
        'per_nome' => $faker->word,
        'per_inicio' => $faker->date('d/m/Y'),
        'per_fim' => $faker->date('d/m/Y'),
    ];
});

$factory->define(Modulos\Academico\Models\Polo::class, function (Faker\Generator $faker) {
    return [
        'pol_nome' => $faker->city
    ];
});

$factory->define(Modulos\Academico\Models\Curso::class, function (Faker\Generator $faker) {
    return [
        'crs_cen_id' => factory(Modulos\Academico\Models\Centro::class)->create()->cen_id,
        'crs_nvc_id' => 2,
        'crs_prf_diretor' => factory(Modulos\Academico\Models\Professor::class)->create()->prf_id,
        'crs_nome' => $faker->name,
        'crs_sigla' => $faker->name,
        'crs_descricao' => $faker->sentence(3),
        'crs_resolucao' => $faker->sentence(3),
        'crs_autorizacao' => $faker->sentence(3),
        'crs_data_autorizacao' => $faker->date('d/m/Y'),
        'crs_eixo' => $faker->word,
        'crs_habilitacao' => $faker->word
    ];
});

$factory->define(Modulos\Academico\Models\Modalidade::class, function (Faker\Generator $faker) {
    return [
        'mdl_nome' => 'distancia'
    ];
});

$factory->define(Modulos\Academico\Models\OfertaCurso::class, function (Faker\Generator $faker) {
    $curso = factory(Modulos\Academico\Models\Curso::class)->create();
    $modalidade = factory(Modulos\Academico\Models\Modalidade::class)->create();

    return [
        'ofc_crs_id' => $curso->crs_id,
        'ofc_mtc_id' => factory(Modulos\Academico\Models\MatrizCurricular::class)->create(['mtc_crs_id' => $curso->crs_id])->mtc_id,
        'ofc_mdl_id' => $modalidade->mdl_id,
        'ofc_ano' => $faker->year
    ];
});

$factory->define(Modulos\Academico\Models\MatrizCurricular::class, function (Faker\Generator $faker) {
    return [
        'mtc_crs_id' => factory(Modulos\Academico\Models\Curso::class)->create()->crs_id,
        'mtc_anx_projeto_pedagogico' => factory(Modulos\Geral\Models\Anexo::class)->create()->anx_id,
        'mtc_titulo' => ucfirst($faker->word),
        'mtc_descricao' => $faker->words(5, true),
        'mtc_data' => $faker->date('d/m/Y'),
        'mtc_creditos' => $faker->randomNumber(3),
        'mtc_horas' => $faker->randomNumber(4),
        'mtc_horas_praticas' => $faker->randomNumber(4)
    ];
});

$factory->define(Modulos\Academico\Models\Grupo::class, function (Faker\Generator $faker) {
    return [
        'grp_trm_id' => factory(Modulos\Academico\Models\Turma::class)->create()->trm_id,
        'grp_pol_id' => factory(Modulos\Academico\Models\Polo::class)->create()->pol_id,
        'grp_nome' => $faker->name
    ];
});

$factory->define(Modulos\Academico\Models\Turma::class, function (Faker\Generator $faker) {
    return [
        'trm_ofc_id' => factory(Modulos\Academico\Models\OfertaCurso::class)->create()->ofc_id,
        'trm_per_id' => factory(Modulos\Academico\Models\PeriodoLetivo::class)->create()->per_id,
        'trm_nome' => $faker->sentence(3),
        'trm_integrada' => 0,
        'trm_qtd_vagas' => 50
    ];
});

$factory->define(Modulos\Academico\Models\ModuloMatriz::class, function (Faker\Generator $faker) {
    return [
        'mdo_mtc_id' => factory(Modulos\Academico\Models\MatrizCurricular::class)->create()->mtc_id,
        'mdo_nome' => $faker->name,
        'mdo_descricao' => $faker->sentence(3),
        'mdo_qualificacao' => $faker->sentence(3)
    ];
});

$factory->define(Modulos\Academico\Models\Aluno::class, function (Faker\Generator $faker) {
    return [
        'alu_pes_id' => factory(Modulos\Geral\Models\Pessoa::class)->create()->pes_id
    ];
});

$factory->define(Modulos\Academico\Models\Tutor::class, function (Faker\Generator $faker) {
    return [
        'tut_pes_id' => factory(Modulos\Geral\Models\Pessoa::class)->create()->pes_id
    ];
});

$factory->define(Modulos\Academico\Models\TutorGrupo::class, function (Faker\Generator $faker) {
    $tutor = factory(Modulos\Academico\Models\Tutor::class)->create();
    $grupo = factory(Modulos\Academico\Models\Grupo::class)->create();

    return [
        'ttg_tut_id' => $tutor->tut_id,
        'ttg_grp_id' => $grupo->grp_id,
        'ttg_tipo_tutoria' => 'presencial',
        'ttg_data_inicio' => '10/11/2010',
        'ttg_data_fim' => null
    ];
});

$factory->define(Modulos\Academico\Models\NivelCurso::class, function (Faker\Generator $faker) {
    return [
        'nvc_nome' => $faker->sentence(3)
    ];
});

$factory->define(Modulos\Academico\Models\Disciplina::class, function (Faker\Generator $faker) {
    $nivel = factory(Modulos\Academico\Models\NivelCurso::class)->create();

    return [
        'dis_nvc_id' => $nivel->nvc_id,
        'dis_nome' => $faker->sentence(3),
        'dis_carga_horaria' => $faker->randomNumber(2),
        'dis_bibliografia' => $faker->text(),
        'dis_creditos' => $faker->randomNumber(2),
        'dis_ementa' => $faker->text()
    ];
});

$factory->define(Modulos\Academico\Models\ModuloDisciplina::class, function (Faker\Generator $faker) {
    $curso = factory(Modulos\Academico\Models\Curso::class)->create();

    $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
        'mtc_crs_id' => $curso->crs_id
    ]);

    $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
        'mdo_mtc_id' => $matrizCurricular->mtc_id
    ]);

    $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create([
        'dis_nvc_id' => $curso->crs_nvc_id
    ]);

    return [
        'mdc_dis_id' => $disciplina->dis_id,
        'mdc_mdo_id' => $moduloMatriz->mdo_id,
        'mdc_tipo_disciplina' => $faker->randomElement(['obrigatoria', 'eletiva', 'optativa'])
    ];
});

$factory->define(Modulos\Academico\Models\OfertaDisciplina::class, function (Faker\Generator $faker) {
    $curso = factory(Modulos\Academico\Models\Curso::class)->create();

    $ofertaCurso = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
        'ofc_crs_id' => $curso->crs_id
    ]);

    $turma = factory(Modulos\Academico\Models\Turma::class)->create([
        'trm_ofc_id' => $ofertaCurso->ofc_id
    ]);

    $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
        'mdo_mtc_id' => $ofertaCurso->ofc_mtc_id
    ]);

    $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create([
        'dis_nvc_id' => $curso->crs_nvc_id
    ]);

    $moduloDisciplina = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
        'mdc_dis_id' => $disciplina->dis_id,
        'mdc_mdo_id' => $moduloMatriz->mdo_id
    ]);

    return [
        'ofd_mdc_id' => $moduloDisciplina->mdc_id,
        'ofd_trm_id' => $turma->trm_id,
        'ofd_per_id' => $turma->trm_per_id,
        'ofd_prf_id' => factory(Modulos\Academico\Models\Professor::class)->create()->prf_id,
        'ofd_tipo_avaliacao' => $faker->randomElement(['numerica', 'conceitual']),
        'ofd_qtd_vagas' => 500
    ];
});

$factory->define(Modulos\Academico\Models\Matricula::class, function () {
    $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create();
    $turma = factory(Modulos\Academico\Models\Turma::class)->create(['trm_ofc_id' => $oferta->ofc_id]);
    $polo = factory(Modulos\Academico\Models\Polo::class)->create();
    $oferta->polos()->attach($polo->pol_id);
    $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
        'grp_trm_id' => $turma->trm_id,
        'grp_pol_id' => $polo->pol_id
    ]);

    return [
        'mat_alu_id' => factory(Modulos\Academico\Models\Aluno::class)->create()->alu_id,
        'mat_trm_id' => $turma->trm_id,
        'mat_pol_id' => $polo->pol_id,
        'mat_grp_id' => $grupo->grp_id,
        'mat_situacao' => 'cursando',
        'mat_modo_entrada' => 'vestibular',
        'mat_data_conclusao' => '15/11/2015'
    ];
});

$factory->define(Modulos\Academico\Models\MatriculaOfertaDisciplina::class, function () {
    $curso = factory(Modulos\Academico\Models\Curso::class)->create();

    $ofertaCurso = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
        'ofc_crs_id' => $curso->crs_id
    ]);

    $turma = factory(Modulos\Academico\Models\Turma::class)->create([
        'trm_ofc_id' => $ofertaCurso->ofc_id
    ]);

    $polo = factory(Modulos\Academico\Models\Polo::class)->create();
    $ofertaCurso->polos()->attach($polo->pol_id);
    $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
        'grp_trm_id' => $turma->trm_id,
        'grp_pol_id' => $polo->pol_id
    ]);

    $matricula = factory(Modulos\Academico\Models\Matricula::class)->create([
        'mat_trm_id' => $turma->trm_id,
        'mat_pol_id' => $polo->pol_id,
        'mat_grp_id' => $grupo->grp_id
    ]);

    $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
        'mdo_mtc_id' => $ofertaCurso->ofc_mtc_id
    ]);

    $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create([
        'dis_nvc_id' => $curso->crs_nvc_id
    ]);

    $moduloDisciplina = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
        'mdc_dis_id' => $disciplina->dis_id,
        'mdc_mdo_id' => $moduloMatriz->mdo_id
    ]);

    $ofertaDisciplina = factory(Modulos\Academico\Models\OfertaDisciplina::class)->create([
        'ofd_mdc_id' => $moduloDisciplina->mdc_id,
        'ofd_trm_id' => $turma->trm_id,
        'ofd_per_id' => $turma->trm_per_id
    ]);

    return [
        'mof_mat_id' => $matricula->mat_id,
        'mof_ofd_id' => $ofertaDisciplina->ofd_id,
        'mof_tipo_matricula' => 'matriculacomum',
        'mof_situacao_matricula' => 'cursando'
    ];
});

$factory->define(Modulos\Academico\Models\LancamentoTcc::class, function (Faker\Generator $faker) {
    $matriculaoferta = factory(Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create();
    $professor = factory(Modulos\Academico\Models\Professor::class)->create();
    $anexo = factory(Modulos\Geral\Models\Anexo::class)->create();

    return [
        'ltc_mof_id' => $matriculaoferta->mof_id,
        'ltc_prf_id' => $professor->prf_id,
        'ltc_anx_tcc' => $anexo->anx_id,
        'ltc_titulo' => $faker->sentence(3),
        'ltc_tipo' => 'artigo',
        'ltc_data_apresentacao' => '15/11/2015',
        'ltc_observacao' => $faker->sentence(3)
    ];
});

$factory->define(Modulos\Academico\Models\Diploma::class, function () {
    $curso = factory(Modulos\Academico\Models\Curso::class)->create();

    $ofertaCurso = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
        'ofc_crs_id' => $curso->crs_id
    ]);

    $turma = factory(Modulos\Academico\Models\Turma::class)->create([
        'trm_ofc_id' => $ofertaCurso->ofc_id
    ]);

    $polo = factory(Modulos\Academico\Models\Polo::class)->create();
    $ofertaCurso->polos()->attach($polo->pol_id);
    $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
        'grp_trm_id' => $turma->trm_id,
        'grp_pol_id' => $polo->pol_id
    ]);

    $matricula = factory(Modulos\Academico\Models\Matricula::class)->create([
        'mat_trm_id' => $turma->trm_id,
        'mat_pol_id' => $polo->pol_id,
        'mat_grp_id' => $grupo->grp_id
    ]);

    $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
        'mdo_mtc_id' => $ofertaCurso->ofc_mtc_id
    ]);

    $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create([
        'dis_nvc_id' => $curso->crs_nvc_id
    ]);

    $moduloDisciplina = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
        'mdc_dis_id' => $disciplina->dis_id,
        'mdc_mdo_id' => $moduloMatriz->mdo_id
    ]);

    $ofertaDisciplina = factory(Modulos\Academico\Models\OfertaDisciplina::class)->create([
        'ofd_mdc_id' => $moduloDisciplina->mdc_id,
        'ofd_trm_id' => $turma->trm_id,
        'ofd_per_id' => $turma->trm_per_id
    ]);

    return [
        'mof_mat_id' => $matricula->mat_id,
        'mof_ofd_id' => $ofertaDisciplina->ofd_id,
        'mof_tipo_matricula' => 'matriculacomum',
        'mof_status' => 'cursando'
    ];
});

$factory->define(Modulos\Academico\Models\Vinculo::class, function (Faker\Generator $faker) {
    $curso = factory(Modulos\Academico\Models\Curso::class)->create();

    return [
        'ucr_usr_id' => 1,
        'ucr_crs_id' => $curso->crs_id
    ];
});

$factory->define(Modulos\Academico\Models\Livro::class, function (Faker\Generator $faker) {
    return [
        'liv_numero' => 1,
        'liv_tipo_livro' => 'DIPLOMA'
    ];
});

$factory->define(Modulos\Academico\Models\Registro::class, function (Faker\Generator $faker) {
    $livro = factory(Modulos\Academico\Models\Livro::class)->create();
    $usuario = factory(Modulos\Seguranca\Models\Usuario::class)->create();
    return [
        'reg_liv_id' => $livro->liv_id,
        'reg_usr_id' => $usuario->usr_id,
        'reg_folha' => $faker->randomNumber(),
        'reg_registro' => $faker->randomNumber(),
        'reg_codigo_autenticidade' => $faker->randomNumber()
    ];
});

$factory->define(Modulos\Academico\Models\Diploma::class, function (Faker\Generator $faker) {
    $registro = factory(Modulos\Academico\Models\Registro::class)->create();
    $matricula = factory(Modulos\Academico\Models\Matricula::class)->create();
    return [

        'dip_reg_id' => $registro->reg_id,
        'dip_mat_id' => $matricula->mat_id,
        'dip_processo' => $faker->sentence(3),
        'dip_codigo_autenticidade_externo' => $faker->sentence(3)
    ];
});


/** Factories Modulo Integracao */
$factory->define(Modulos\Integracao\Models\AmbienteVirtual::class, function (Faker\Generator $faker) {
    return [
        'amb_nome' => $faker->name,
        'amb_versao' => $faker->sentence(2),
        'amb_url' => $faker->sentence(3)
    ];
});

$factory->define(\Modulos\Integracao\Models\MapeamentoNota::class, function (Faker\Generator $faker) {
    $ofertaDisciplina = factory(\Modulos\Academico\Models\OfertaDisciplina::class)->create();

    return [
        'min_ofd_id' => $ofertaDisciplina->ofd_id,
        'min_id_nota1' => $faker->numberBetween(0, 10),
        'min_id_nota2' => $faker->numberBetween(0, 10),
        'min_id_nota3' => $faker->numberBetween(0, 10),
        'min_id_recuperacao' => $faker->numberBetween(0, 5),
        'min_id_conceito' => $faker->randomElement(['aprovado', 'aprovado_final', 'reprovado', 'reprovado_final']),
        'min_id_final' => $faker->numberBetween(0, 10)
    ];
});

$factory->define(Modulos\Integracao\Models\Servico::class, function (Faker\Generator $faker) {
    return [
        'ser_nome' => $faker->word,
        'ser_slug' => $faker->word
    ];
});

$factory->define(Modulos\Integracao\Models\AmbienteTurma::class, function () {
    $ambiente = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create();
    $turma = factory(Modulos\Academico\Models\Turma::class)->create();

    return [
        'atr_trm_id' => $turma->trm_id,
        'atr_amb_id' => $ambiente->amb_id
    ];
});

$factory->define(Modulos\Integracao\Models\AmbienteServico::class, function (Faker\Generator $faker) {
    $ambiente = factory(Modulos\Integracao\Models\AmbienteVirtual::class)->create();
    $servico = factory(Modulos\Integracao\Models\Servico::class)->create();

    return [
        'asr_amb_id' => $ambiente->amb_id,
        'asr_ser_id' => $servico->ser_id,
        'asr_token' => $faker->uuid
    ];
});

$factory->define(Modulos\Integracao\Models\Sincronizacao::class, function (Faker\Generator $faker) {
    $tablesActions = [
        'acd_turmas' => [
            'CREATE', 'UPDATE', 'DELETE'
        ],
        'acd_grupos' => [
            'CREATE', 'UPDATE', 'DELETE',
        ],
        'acd_ofertas_disciplinas' => [
            'CREATE', 'DELETE', 'UPDATE_PROFESSOR_OFERTA_DISCIPLINA',
        ],
        'acd_matriculas_ofertas_disciplinas' => [
            'CREATE'
        ],
        'acd_matriculas' => [
            'CREATE', 'UPDATE_SITUACAO_MATRICULA', 'UPDATE_GRUPO_ALUNO', 'DELETE_GRUPO_ALUNO'
        ],
        'acd_tutores_grupos' => [
            'CREATE', 'DELETE'
        ],
        'gra_pessoas' => [
            'UPDATE'
        ]
    ];

    $tabela = $faker->randomElement(array_keys($tablesActions));
    $acao = $faker->randomElement($tablesActions[$tabela]);

    return [
        'sym_table' => $tabela,
        'sym_table_id' => $faker->numberBetween(1, 200),
        'sym_action' => $acao,
        'sym_status' => $faker->randomElement([1, 2, 3]),
        'sym_mensagem' => $faker->words(3, true),
        'sym_data_envio' => $faker->dateTime->format("Y-m-d H:i:s"),
        'sym_extra' => $faker->word
    ];
});
