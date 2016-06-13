<?php

Route::get('/', '\Modulos\Seguranca\Http\Controllers\SelecionaModulosController@getIndex');

Route::get('login', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@getLogin');
Route::post('login', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@postLogin');
Route::get('logout', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@getLogout');


Route::group(['prefix' => 'seguranca', 'middleware' => ['auth']], function () {
    Route::controllers([
        'index' => '\Modulos\Seguranca\Http\Controllers\IndexController',
        'profile' => '\Modulos\Seguranca\Http\Controllers\Auth\ProfileController',
        'modulos' => '\Modulos\Seguranca\Http\Controllers\ModulosController',
        'perfis' => '\Modulos\Seguranca\Http\Controllers\PerfisController',
        'categoriasrecursos' => '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController',
    ]);
});
