


<?php

Route::get('/', '\Modulos\Seguranca\Http\Controllers\SelecionaModulosController@getIndex');

Route::get('login', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@showLoginForm')->name('auth.getLogin');
Route::post('login', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@postLogin')->name('auth.postLogin');
Route::get('logout', '\Modulos\Seguranca\Http\Controllers\Auth\AuthController@getLogout')->name('auth.getLogout');

Route::group(['prefix' => 'seguranca', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'index'], function () {
        Route::get('/', '\Modulos\Seguranca\Http\Controllers\IndexController@getIndex')->name('seguranca.index.index');
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\IndexController@getIndex');
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\Auth\ProfileController@getIndex')->name('seguranca.profile.index');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\Auth\ProfileController@putEdit')->name('seguranca.profile.putEdit');
        Route::post('/updatepassword', '\Modulos\Seguranca\Http\Controllers\Auth\ProfileController@postUpdatepassword')->name('seguranca.profile.postUpdatepassword');
    });

    Route::group(['prefix' => 'modulos'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\ModulosController@getIndex')->name('seguranca.modulos.index');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\ModulosController@getCreate')->name('seguranca.modulos.getCreate');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\ModulosController@postCreate')->name('seguranca.modulos.postCreate');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\ModulosController@getEdit')->name('seguranca.modulos.getEdit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\ModulosController@putEdit')->name('seguranca.modulos.putEdit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\ModulosController@postDelete')->name('seguranca.modulos.delete');
    });

    Route::group(['prefix' => 'perfis'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\PerfisController@getIndex')->name('seguranca.perfis.index');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\PerfisController@getCreate')->name('seguranca.perfis.getCreate');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\PerfisController@postCreate')->name('seguranca.perfis.postCreate');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PerfisController@getEdit')->name('seguranca.perfis.getEdit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PerfisController@putEdit')->name('seguranca.perfis.putEdit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\PerfisController@postDelete')->name('seguranca.perfis.delete');
        Route::get('/atribuirpermissoes/{id}', '\Modulos\Seguranca\Http\Controllers\PerfisController@getAtribuirpermissoes')->name('seguranca.perfis.getAtribuirpermissoes');
        Route::post('/atribuirpermissoes', '\Modulos\Seguranca\Http\Controllers\PerfisController@postAtribuirpermissoes')->name('seguranca.perfis.postAtribuirpermissoes');
    });

    Route::group(['prefix' => 'categoriasrecursos'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@getIndex')->name('seguranca.categoriasrecursos.index');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@getCreate')->name('seguranca.categoriasrecursos.getCreate');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@postCreate')->name('seguranca.categoriasrecursos.postCreate');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@getEdit')->name('seguranca.categoriasrecursos.getEdit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@putEdit')->name('seguranca.categoriasrecursos.putEdit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\CategoriasRecursosController@postDelete')->name('seguranca.categoriasrecursos.delete');
    });

    Route::group(['prefix' => 'recursos'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\RecursosController@getIndex')->name('seguranca.recursos.index');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\RecursosController@getCreate')->name('seguranca.recursos.getCreate');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\RecursosController@postCreate')->name('seguranca.recursos.postCreate');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\RecursosController@getEdit')->name('seguranca.recursos.getEdit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\RecursosController@putEdit')->name('seguranca.recursos.putEdit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\RecursosController@postDelete')->name('seguranca.recursos.delete');
    });

    Route::group(['prefix' => 'permissoes'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\PermissoesController@getIndex')->name('seguranca.permissoes.index');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\PermissoesController@getCreate')->name('seguranca.permissoes.getCreate');
        Route::post('/create', '\Modulos\Seguranca\Http\Controllers\PermissoesController@postCreate')->name('seguranca.permissoes.postCreate');
        Route::get('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PermissoesController@getEdit')->name('seguranca.permissoes.getEdit');
        Route::put('/edit/{id}', '\Modulos\Seguranca\Http\Controllers\PermissoesController@putEdit')->name('seguranca.permissoes.putEdit');
        Route::post('/delete', '\Modulos\Seguranca\Http\Controllers\PermissoesController@postDelete')->name('seguranca.permissoes.delete');
    });

    Route::group(['prefix' => 'usuarios'], function () {
        Route::get('/index', '\Modulos\Seguranca\Http\Controllers\UsuariosController@getIndex')->name('seguranca.usuarios.index');
        Route::get('/create', '\Modulos\Seguranca\Http\Controllers\UsuariosController@getCreate')->name('seguranca.usuarios.getCreate');
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
