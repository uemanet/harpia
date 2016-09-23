<?php

Route::get('/', '\Modulos\Seguranca\Http\Controllers\SelecionaModulosController@getIndex');

Route::get('login', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@showLoginForm');
Route::post('login', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@postLogin');
Route::get('logout', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@getLogout');

Route::group(['prefix' => 'seguranca', 'middleware' => ['auth']], function () {

    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Seguranca\Http\Controllers\IndexController@getIndex');
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\IndexController@getIndex');
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\Auth\ProfileController@getIndex');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\Auth\ProfileController@putEdit');
        Route::post('/updatepassword', '\Modulos\Seguranca\Http\Controllers\Auth\ProfileController@postUpdatepassword');
    });

    Route::group(['prefix' => 'modulos'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\ModulosController@getIndex');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\ModulosController@getCreate');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\ModulosController@postCreate');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\ModulosController@getEdit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\ModulosController@putEdit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\ModulosController@postDelete');
    });

    Route::group(['prefix' => 'perfis'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\PerfisController@getIndex');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\PerfisController@getCreate');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\PerfisController@postCreate');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PerfisController@getEdit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PerfisController@putEdit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\PerfisController@postDelete');
        Route::get('/atribuirpermissoes/{id}', '\Modulos\Seguranca\Http\Controllers\PerfisController@getAtribuirpermissoes');
        Route::post('/atribuirpermissoes', '\Modulos\Seguranca\Http\Controllers\PerfisController@postAtribuirpermissoes');
    });

    Route::group(['prefix' => 'categoriasrecursos'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@getIndex');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@getCreate');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@postCreate');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@getEdit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@putEdit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@postDelete');
    });

    Route::group(['prefix' => 'recursos'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\RecursosController@getIndex');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\RecursosController@getCreate');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\RecursosController@postCreate');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\RecursosController@getEdit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\RecursosController@putEdit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\RecursosController@postDelete');
    });

    Route::group(['prefix' => 'permissoes'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\PermissoesController@getIndex');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\PermissoesController@getCreate');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\PermissoesController@postCreate');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PermissoesController@getEdit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PermissoesController@putEdit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\PermissoesController@postDelete');
    });

    Route::group(['prefix' => 'usuarios'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\UsuariosController@getIndex');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\UsuariosController@getCreate');
    });

    Route::group(['prefix' => 'async', 'middleware' => ['auth']], function () {

        Route::group(['prefix' => 'categorias'], function () {
           Route::get('/findallbymodulo/{id}', '\Modulos\Seguranca\Http\Controllers\Async\CategoriasRecursos@getFindallbymodulo');
        });

        Route::group(['prefix' => 'recursos'], function () {
           Route::get('/findallbymodulo/{id}', '\Modulos\Seguranca\Http\Controllers\Async\Recursos@getFindallbymodulo');
        });

    });
});