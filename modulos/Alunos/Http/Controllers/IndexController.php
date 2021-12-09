<?php

namespace Modulos\Alunos\Http\Controllers;

use App\Http\Controllers\Controller;

/**
 * Class IndexController.
 */
class IndexController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        return view('Alunos::index.index');
    }
}
