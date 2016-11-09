<?php

namespace Modulos\Integracao\Http\Controllers;

use App\Http\Controllers\Controller;
use Setting;

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
        return view('Integracao::index.index');
    }
}
