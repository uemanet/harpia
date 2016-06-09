<?php

namespace Modulos\Seguranca\Http\Controllers;

use App\Http\Controllers\Controller;
use Harpia\Providers\ActionButton\TButton;

use Modulos\Seguranca\Models\Modulo;

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