<?php

Route::get('/', '\Modulos\Seguranca\Http\Controllers\SelecionaModulosController@getIndex')->name('index');

Route::get('login', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@showLoginForm')->name('auth.login');
Route::post('login', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@postLogin')->name('auth.login');
Route::get('logout', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@getLogout')->name('auth.logout');

Route::group(['prefix' => 'seguranca', 'middleware' => ['auth']], function () {
    Route::get('/', '\Modulos\Seguranca\Http\Controllers\IndexController@getIndex')->name('seguranca.index.index');

    Route::group(['prefix' => 'perfis'], function () {
        Route::get('/', '\Modulos\Seguranca\Http\Controllers\PerfisController@getIndex')->name('seguranca.perfis.index');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\PerfisController@getCreate')->name('seguranca.perfis.create');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\PerfisController@postCreate')->name('seguranca.perfis.create');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PerfisController@getEdit')->name('seguranca.perfis.edit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PerfisController@putEdit')->name('seguranca.perfis.edit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\PerfisController@postDelete')->name('seguranca.perfis.delete');
        Route::get('/atribuirpermissoes/{id}', '\Modulos\Seguranca\Http\Controllers\PerfisController@getAtribuirpermissoes')->name('seguranca.perfis.atribuirpermissoes');
        Route::post('/atribuirpermissoes', '\Modulos\Seguranca\Http\Controllers\PerfisController@postAtribuirpermissoes')->name('seguranca.perfis.atribuirpermissoes');
    });

    Route::group(['prefix' => 'usuarios'], function () {
        Route::get('/', '\Modulos\Seguranca\Http\Controllers\UsuariosController@getIndex')->name('seguranca.usuarios.index');
        Route::get('/create/{id?}', '\Modulos\Seguranca\Http\Controllers\UsuariosController@getCreate')->name('seguranca.usuarios.create')->middleware('verificapessoa');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\UsuariosController@postCreate')->name('seguranca.usuarios.create');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\UsuariosController@getEdit')->name('seguranca.usuarios.edit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\UsuariosController@putEdit')->name('seguranca.usuarios.edit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\UsuariosController@postDelete')->name('seguranca.usuarios.delete');
        Route::get('/atribuirperfil/{id}', '\Modulos\Seguranca\Http\Controllers\UsuariosController@getAtribuirperfil')->name('seguranca.usuarios.atribuirperfil');
        Route::post('/atribuirperfil/{id}', '\Modulos\Seguranca\Http\Controllers\UsuariosController@postAtribuirperfil')->name('seguranca.usuarios.atribuirperfil');
        Route::post('/deletarperfil/{id}', '\Modulos\Seguranca\Http\Controllers\UsuariosController@postDeletarperfil')->name('seguranca.usuarios.deletarperfil');
    });

    Route::group(['prefix' => 'async', 'middleware' => ['auth']], function () {
        Route::group(['prefix' => 'perfis'], function () {
            Route::get('/findallbymodulo/{id}', '\Modulos\Seguranca\Http\Controllers\Async\Perfis@getFindallbymodulo')->name('seguranca.async.perfis.findallbymodulo');
        });
    });
});
