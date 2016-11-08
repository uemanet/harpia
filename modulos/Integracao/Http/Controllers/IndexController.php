<?php

namespace Modulos\Integracao\Http\Controllers;

use App\Http\Controllers\Controller;
use Modulos\Geral\Repositories\ConfiguracaoRepository;
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
        Setting::set('med_temp_click', 5260, 3);
        return Setting::getAll();
    }
}
