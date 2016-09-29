<?php

namespace Modulos\Academico\Http\Controllers;

use App\Http\Controllers\Controller;

/**
 * Class IndexController.
 */
class indexController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        return view('Academico::index.index');
    }
}
