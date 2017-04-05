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
        'pes_estado_civil' => $faker->randomElement(['solteiro', 'casado', 'viuvo', 'separado']),
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

$factory->define(Modulos\Geral\Models\Documento::class, function (Faker\Generator $faker) {
    return [
       'doc_pes_id' => factory(Modulos\Geral\Models\Pessoa::class)->create()->pes_id,
       'doc_tpd_id' => 2,
       'doc_data_expedicao' => $faker->date('d/m/Y'),
       'doc_conteudo' => $faker->creditCardNumber
   ];
});

$factory->define(Modulos\Geral\Models\Anexo::class, function (Faker\Generator $faker) {
    return [
        'anx_tax_id' => 1,
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
        'tin_pes_id' =>  1,
        'tin_tit_id' =>  1,
        'tin_titulo' =>  $faker->word,
        'tin_codigo_externo' =>  $faker->randomNumber(4),
        'tin_instituicao' =>  $faker->word,
        'tin_instituicao_sigla' =>  $faker->word,
        'tin_instituicao_sede' =>  $faker->word,
        'tin_anoinicio' =>  $faker->randomNumber(4),
        'tin_anofim' =>  $faker->randomNumber(4),
    ];
});

/** Factories Modulo Segurança */
$factory->define(Modulos\Seguranca\Models\Modulo::class, function (Faker\Generator $faker) {
    $rota = $faker->name;

    return [
        'mod_nome' => $rota,
        'mod_rota' => strtolower($rota),
        'mod_descricao' => $faker->sentence(3),
        'mod_icone' => 'fa fa-cog',
        'mod_ativo' => 1
    ];
});

$factory->define(Modulos\Seguranca\Models\Perfil::class, function (Faker\Generator $faker) {
    return [
        'prf_mod_id' => 1,
        'prf_nome' => $faker->name,
        'prf_descricao' => $faker->sentence(3)
    ];
});

$factory->define(Modulos\Seguranca\Models\CategoriaRecurso::class, function (Faker\Generator $faker) {
    return [
        'ctr_mod_id' => 1,
        'ctr_nome' => $faker->name,
        'ctr_descricao' => $faker->sentence(3),
        'ctr_icone' => 'fa fa-cog',
        'ctr_ordem' => 1,
        'ctr_ativo' => 1,
        'ctr_visivel' => 1
    ];
});

$factory->define(Modulos\Seguranca\Models\Recurso::class, function (Faker\Generator $faker) {
    $rota = $faker->name;

    return [
        'rcs_ctr_id' => 1,
        'rcs_nome' => $rota,
        'rcs_rota' => strtolower($rota),
        'rcs_descricao' => $faker->sentence(3),
        'rcs_icone' => 'fa fa-cog',
        'rcs_ativo' => 1,
        'rcs_ordem' => 1
    ];
});

$factory->define(Modulos\Seguranca\Models\Permissao::class, function (Faker\Generator $faker) {
    return [
        'prm_rcs_id' => 1,
        'prm_nome' => $faker->name,
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
        'crs_dep_id' => factory(Modulos\Academico\Models\Departamento::class)->create()->dep_id,
        'crs_nvc_id' => 1,
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

$factory->define(Modulos\Academico\Models\OfertaCurso::class, function (Faker\Generator $faker) {
    $curso = factory(Modulos\Academico\Models\Curso::class)->create();
    return [
        'ofc_crs_id' => $curso->crs_id,
        'ofc_mtc_id' => factory(Modulos\Academico\Models\MatrizCurricular::class)->create(['mtc_crs_id' => $curso->crs_id])->mtc_id,
        'ofc_mdl_id' => 1,
        'ofc_ano' => $faker->year
    ];
});

$factory->define(Modulos\Academico\Models\MatrizCurricular::class, function (Faker\Generator $faker) {
    return [
        'mtc_crs_id' => factory(Modulos\Academico\Models\Curso::class)->create()->crs_id,
        'mtc_anx_projeto_pedagogico' => $faker->randomNumber(2),
        'mtc_titulo' => $faker->word,
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

$factory->define(Modulos\Academico\Models\Disciplina::class, function (Faker\Generator $faker) {
    return [
       'dis_nvc_id' => $faker->randomElement([1, 2, 3, 4, 5]),
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
        'mdc_tipo_avaliacao' => $faker->randomElement(['numerica', 'conceitual']),
        'mdc_tipo_disciplina' => $faker->randomElement(['obrigatoria', 'eletiva', 'optativa', 'tcc'])
    ];
});

$factory->define(Modulos\Academico\Models\OfertaDisciplina::class, function () {
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
        'mat_modo_entrada' => 'vestibular'
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
        'liv_tipo_livro' => $faker->randomElements(['CERTIFICADO', 'DIPLOMA'])
    ];
});

$factory->define(Modulos\Academico\Models\Registro::class, function (Faker\Generator $faker) {
    $livro = factory(Modulos\Academico\Models\Livro::class)->create();
    $matricula = factory(Modulos\Academico\Models\Matricula::class)->create();

    return [
        'reg_liv_id' => $livro->liv_id,
        'reg_mat_id' => $matricula->mat_id,
        'reg_folha' => $faker->randomNumber(),
        'reg_registro' => $faker->randomNumber(),
        'reg_registro_externo' => $faker->randomLetter(),
        'reg_processo' => $faker->word,
        'reg_data_expedicao' => $faker->date(),
        'reg_observacao' => $faker->word,
        'reg_usuario' => $faker->name,
        'reg_data' => $faker->date(),
        'reg_id_interno' => $faker->randomNumber()
    ];
});

/** Factories Modulo Integracao */
$factory->define(Modulos\Integracao\Models\AmbienteVirtual::class, function (Faker\Generator $faker) {
    return [
        'amb_nome' => $faker->name,
        'amb_versao' => $faker->sentence(2),
        'amb_token' => $faker->sentence(3)
    ];
});
