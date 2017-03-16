<?php

namespace Modulos\Academico\Http\Controllers;

use App\Http\Controllers\Controller;

/**
 * Class IndexController.
 */
class RelatoriosController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        return view('Academico::relatorios.index');
    }
}
