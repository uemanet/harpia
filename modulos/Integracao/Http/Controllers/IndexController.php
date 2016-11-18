<?php

namespace Modulos\Integracao\Http\Controllers;

use App\Http\Controllers\Controller;
use Configuracao;
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
        return Configuracao::remove('min_temp_click');
    }
}
