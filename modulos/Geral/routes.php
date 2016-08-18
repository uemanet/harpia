<?php

Route::group(['prefix' => 'geral', 'middleware' => ['auth']], function () {
    Route::controllers([
        'index' => '\Modulos\Geral\Http\Controllers\IndexController',
    ]);
});
