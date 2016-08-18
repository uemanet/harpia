<?php

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

// Modulo GERAL
//$factory->define(Modulos\Geral\Models\Pessoa::class, function (Faker\Generator $faker) {
//    return [
//        'pes_nome' => $faker->name,
//        'pes_sexo' => $faker->randomElement(['M', 'F']),
//        'pes_email' => $faker->email,
//        'pes_telefone' => $faker->phoneNumber,
//        'pes_nascimento' => $faker->date(),
//        'pes_mae' => $faker->name,
//        'pes_pai' => $faker->name,
//        'pes_estado_civil' => $faker->randomElement(['solteiro', 'casado', 'viuvo', 'separado']),
//        'pes_naturalidade' => $faker->city,
//        'pes_nacionalidade' => $faker->country,
//        'pes_raca' => $faker->randomElement(['branco', 'negro', 'amarelo']),
//        'pes_necessidade_especial' => $faker->randomElement(['sim', 'nao']),
//        'pes_estrangeiro' => $faker->boolean
//    ];
//});


// Modulo ACADEMICO
$factory->define(Modulos\Academico\Models\Departamento::class, function(Faker\Generator $faker){
   return [
       'dep_cen_id' => 1,
       'dep_prf_diretor' => 1,
       'dep_nome' => $faker->word
   ];
});

$factory->define(Modulos\Academico\Models\Centro::class, function(Faker\Generator $faker){
    return [
        'cen_prf_diretor' => 1,
        'cen_nome' => $faker->word,
        'cen_sigla' => $faker->word,
    ];
});

$factory->define(Modulos\Academico\Models\Professor::class, function(Faker\Generator $faker){
    return [
        'dep_prf_diretor' => 1,
        'prf_matricula' => $faker->bankAccountNumber,
    ];
});