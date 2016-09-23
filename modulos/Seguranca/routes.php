<?php

Route::get('/', '\Modulos\Seguranca\Http\Controllers\SelecionaModulosController@getIndex');

Route::get('login', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@showLoginForm')->name('auth.getlogin');
Route::post('login', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@postLogin')->name('auth.postlogin');
Route::get('logout', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@getLogout')->name('auth.getlogout');

Route::group(['prefix' => 'seguranca', 'middleware' => ['auth']], function () {

    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Seguranca\Http\Controllers\IndexController@getIndex')->name('seguranca.index.getindex');
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\IndexController@getIndex');
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\Auth\ProfileController@getIndex')->name('seguranca.profile.getindex');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\Auth\ProfileController@putEdit')->name('seguranca.profile.putedit');
        Route::post('/updatepassword', '\Modulos\Seguranca\Http\Controllers\Auth\ProfileController@postUpdatepassword')->name('seguranca.profile.postupdatepassword');
    });

    Route::group(['prefix' => 'modulos'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\ModulosController@getIndex')->name('seguranca.modulos.getindex');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\ModulosController@getCreate')->name('seguranca.modulos.getcreate');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\ModulosController@postCreate')->name('seguranca.modulos.postcreate');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\ModulosController@getEdit')->name('seguranca.modulos.getedit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\ModulosController@putEdit')->name('seguranca.modulos.putedit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\ModulosController@postDelete')->name('seguranca.modulos.postdelete');
    });

    Route::group(['prefix' => 'perfis'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\PerfisController@getIndex')->name('seguranca.perfis.getindex');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\PerfisController@getCreate')->name('seguranca.perfis.getcreate');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\PerfisController@postCreate')->name('seguranca.perfis.postcreate');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PerfisController@getEdit')->name('seguranca.perfis.getedit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PerfisController@putEdit')->name('seguranca.perfis.putedit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\PerfisController@postDelete')->name('seguranca.perfis.postdelete');
        Route::get('/atribuirpermissoes/{id}', '\Modulos\Seguranca\Http\Controllers\PerfisController@getAtribuirpermissoes')->name('seguranca.perfis.getatribuirpermissoes');
        Route::post('/atribuirpermissoes', '\Modulos\Seguranca\Http\Controllers\PerfisController@postAtribuirpermissoes')->name('seguranca.perfis.postatribuirpermissoes');
    });

    Route::group(['prefix' => 'categoriasrecursos'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@getIndex')->name('seguranca.categoriasrecursos.getindex');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@getCreate')->name('seguranca.categoriasrecursos.getcreate');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@postCreate')->name('seguranca.categoriasrecursos.postcreate');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@getEdit')->name('seguranca.categoriasrecursos.getedit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@putEdit')->name('seguranca.categoriasrecursos.putedit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@postDelete')->name('seguranca.categoriasrecursos.postdelete');
    });

    Route::group(['prefix' => 'recursos'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\RecursosController@getIndex')->name('seguranca.recursos.getindex');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\RecursosController@getCreate')->name('seguranca.recursos.getcreate');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\RecursosController@postCreate')->name('seguranca.recursos.postcreate');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\RecursosController@getEdit')->name('seguranca.recursos.getedit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\RecursosController@putEdit')->name('seguranca.recursos.putedit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\RecursosController@postDelete')->name('seguranca.recursos.postdelete');
    });

    Route::group(['prefix' => 'permissoes'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\PermissoesController@getIndex')->name('seguranca.permissoes.getindex');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\PermissoesController@getCreate')->name('seguranca.permissoes.getcreate');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\PermissoesController@postCreate')->name('seguranca.permissoes.postcreate');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PermissoesController@getEdit')->name('seguranca.permissoes.getedit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PermissoesController@putEdit')->name('seguranca.permissoes.putedit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\PermissoesController@postDelete')->name('seguranca.permissoes.postdelete');
    });

    Route::group(['prefix' => 'usuarios'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\UsuariosController@getIndex')->name('seguranca.usuarios.getindex');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\UsuariosController@getCreate')->name('seguranca.usuarios.getcreate');
    });

    Route::group(['prefix' => 'async', 'middleware' => ['auth']], function () {

        Route::group(['prefix' => 'categorias'], function () {
           Route::get('/findallbymodulo/{id}', '\Modulos\Seguranca\Http\Controllers\Async\CategoriasRecursos@getFindallbymodulo')->name('seguranca.async.categorias.findallbymodulo');
        });

        Route::group(['prefix' => 'recursos'], function () {
           Route::get('/findallbymodulo/{id}', '\Modulos\Seguranca\Http\Controllers\Async\Recursos@getFindallbymodulo')->name('seguranca.async.recursos.findallbymodulo');
        });

    });
});