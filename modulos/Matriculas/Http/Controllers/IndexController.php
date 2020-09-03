<?php

namespace Modulos\Matriculas\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:matriculas-alunos');
    }
    public function getIndex()
    {

        return 'OK';
    }
}