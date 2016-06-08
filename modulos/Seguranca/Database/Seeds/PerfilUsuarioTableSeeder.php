<?php

namespace Modulos\Seguranca\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Seguranca\Models\Usuario;

class PerfilUsuarioTableSeeder extends Seeder {

    public function run()
    {
        $usuario = Usuario::find(1); // Usuario administrador
        $usuario->perfis()->attach(1); // Atribui o perfil administrador do modulo seguranca para o usuario
    }
}