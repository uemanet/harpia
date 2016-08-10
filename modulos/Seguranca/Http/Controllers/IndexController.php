<?php

namespace Modulos\Seguranca\Http\Controllers;

use App\Http\Controllers\Controller;

/**
 * Class IndexController.
 */
class IndexController extends Controller
{
    protected $actionButton = array();

    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        return view('Seguranca::index.index');
    }
}
