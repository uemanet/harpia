<?php

Route::get('/', '\Modulos\Seguranca\Http\Controllers\SelecionaModulosController@getIndex');

Route::get('login', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@showLoginForm')->name('auth.login');
Route::post('login', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@postLogin')->name('auth.login');
Route::get('logout', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@getLogout')->name('auth.logout');

Route::group(['prefix' => 'seguranca', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Seguranca\Http\Controllers\IndexController@getIndex')->name('seguranca.index.index');
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\IndexController@getIndex');
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\Auth\ProfileController@getIndex')->name('seguranca.profile.index');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\Auth\ProfileController@putEdit')->name('seguranca.profile.edit');
        Route::post('/updatepassword', '\Modulos\Seguranca\Http\Controllers\Auth\ProfileController@postUpdatepassword')->name('seguranca.profile.updatepassword');
    });

    Route::group(['prefix' => 'modulos'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\ModulosController@getIndex')->name('seguranca.modulos.index');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\ModulosController@getCreate')->name('seguranca.modulos.create');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\ModulosController@postCreate')->name('seguranca.modulos.create');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\ModulosController@getEdit')->name('seguranca.modulos.edit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\ModulosController@putEdit')->name('seguranca.modulos.edit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\ModulosController@postDelete')->name('seguranca.modulos.delete');
    });

    Route::group(['prefix' => 'perfis'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\PerfisController@getIndex')->name('seguranca.perfis.index');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\PerfisController@getCreate')->name('seguranca.perfis.create');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\PerfisController@postCreate')->name('seguranca.perfis.create');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PerfisController@getEdit')->name('seguranca.perfis.edit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PerfisController@putEdit')->name('seguranca.perfis.edit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\PerfisController@postDelete')->name('seguranca.perfis.delete');
        Route::get('/atribuirpermissoes/{id}', '\Modulos\Seguranca\Http\Controllers\PerfisController@getAtribuirpermissoes')->name('seguranca.perfis.atribuirpermissoes');
        Route::post('/atribuirpermissoes', '\Modulos\Seguranca\Http\Controllers\PerfisController@postAtribuirpermissoes')->name('seguranca.perfis.atribuirpermissoes');
    });

    Route::group(['prefix' => 'categoriasrecursos'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@getIndex')->name('seguranca.categoriasrecursos.index');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@getCreate')->name('seguranca.categoriasrecursos.create');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@postCreate')->name('seguranca.categoriasrecursos.create');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@getEdit')->name('seguranca.categoriasrecursos.edit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@putEdit')->name('seguranca.categoriasrecursos.edit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@postDelete')->name('seguranca.categoriasrecursos.delete');
    });

    Route::group(['prefix' => 'recursos'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\RecursosController@getIndex')->name('seguranca.recursos.index');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\RecursosController@getCreate')->name('seguranca.recursos.create');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\RecursosController@postCreate')->name('seguranca.recursos.create');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\RecursosController@getEdit')->name('seguranca.recursos.edit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\RecursosController@putEdit')->name('seguranca.recursos.edit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\RecursosController@postDelete')->name('seguranca.recursos.delete');
    });

    Route::group(['prefix' => 'permissoes'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\PermissoesController@getIndex')->name('seguranca.permissoes.index');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\PermissoesController@getCreate')->name('seguranca.permissoes.create');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\PermissoesController@postCreate')->name('seguranca.permissoes.create');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PermissoesController@getEdit')->name('seguranca.permissoes.edit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PermissoesController@putEdit')->name('seguranca.permissoes.edit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\PermissoesController@postDelete')->name('seguranca.permissoes.delete');
    });

    Route::group(['prefix' => 'usuarios'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\UsuariosController@getIndex')->name('seguranca.usuarios.index');
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
        Route::group(['prefix' => 'categorias'], function () {
            Route::get('/findallbymodulo/{id}', '\Modulos\Seguranca\Http\Controllers\Async\CategoriasRecursos@getFindallbymodulo')->name('seguranca.async.categorias.findallbymodulo');
        });

        Route::group(['prefix' => 'recursos'], function () {
            Route::get('/findallbymodulo/{id}', '\Modulos\Seguranca\Http\Controllers\Async\Recursos@getFindallbymodulo')->name('seguranca.async.recursos.findallbymodulo');
        });

        Route::group(['prefix' => 'perfis'], function () {
            Route::get('/findallbymodulo/{id}', '\Modulos\Seguranca\Http\Controllers\Async\Perfis@getFindallbymodulo')->name('seguranca.async.perfis.findallbymodulo');
        });
    });
});
