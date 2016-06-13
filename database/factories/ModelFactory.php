<?php

/*
|--------------------------------------------------------------------------
| Models Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Models factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

//$factory->define(App\User::class, function (Faker\Generator $faker) {
//    return [
//        'name' => $faker->name,
//        'email' => $faker->safeEmail,
//        'password' => bcrypt(str_random(10)),
//        'remember_token' => str_random(10),
//    ];
//});

$factory->define(Modulos\Seguranca\Models\Modulo::class, function (Faker\Generator $faker) {
    return [
        'mod_nome' => $faker->name,
        'mod_rota' => strtolower($faker->name),
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
    return [
        'rcs_ctr_id' => 1,
        'rcs_nome' => $faker->name,
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