<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use DB;

class IndexController extends Controller
{
    protected $auth;

    /**
     * Cria uma nova instancia do controller.
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->middleware('auth');
    }

    /**
     * Exibe a tela de boas vindas para o usuario.
     *
     * @return Response
     */
    public function index()
    {
        return view('welcome');
    }
}
