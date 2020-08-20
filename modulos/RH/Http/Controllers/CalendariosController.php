<?php

namespace Modulos\RH\Http\Controllers;

use App\Http\Controllers\Controller;

/**
 * Class IndexController.
 */
class CalendariosController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        return view('RH::calendarios.index');
    }
}
