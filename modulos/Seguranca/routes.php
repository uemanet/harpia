<?php

Route::get('login', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@getLogin');
Route::post('login', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@postLogin');
Route::get('logout', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@getLogout');


Route::group(['prefix' => 'seguranca'], function () {
    Route::controllers([
        'index'   => '\Modulos\Seguranca\Http\Controllers\IndexController',
        // 'modulos' => '\Modulos\Seguranca\Http\Controllers\ModulosController',
        // 'categoriasrecursos' => 'Security\CategoriasRecursosController',
        // 'recursos' => 'Security\RecursosController',
        // 'permissoes' => 'Security\PermissoesController',
        // 'perfis' => 'Security\PerfisController',
        // 'usuarios' => 'Security\UsuariosController',
        // 'perfisusuarios' => 'Security\PerfisUsuariosController',
        // 'perfispermissoes' => 'Security\PerfisPermissoesController',
    ]);
});

Route::group(['prefix' => '/'], function () {
    Route::controllers([
        'index' => '\Modulos\Seguranca\Http\Controllers\EscolherModulosController',
    ]);
});