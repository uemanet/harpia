<?php

Route::group(['prefix' => 'seguranca'], function () {
    Route::controllers([
        'index'   => '\Modulos\Seguranca\Http\Controllers\IndexController',
        // 'modulos' => 'Security\ModulosController',
        // 'categoriasrecursos' => 'Security\CategoriasRecursosController',
        // 'recursos' => 'Security\RecursosController',
        // 'permissoes' => 'Security\PermissoesController',
        // 'perfis' => 'Security\PerfisController',
        // 'usuarios' => 'Security\UsuariosController',
        // 'perfisusuarios' => 'Security\PerfisUsuariosController',
        // 'perfispermissoes' => 'Security\PerfisPermissoesController',
    ]);
});
