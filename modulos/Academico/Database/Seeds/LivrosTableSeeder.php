<?php

namespace Modulos\Academico\Database\Seeds;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\Livro;

class LivrosTableSeeder extends Seeder
{
    public function run()
    {
        $livro = new Livro();
        $livro->liv_numero = 1;
        $livro->liv_tipo_livro = 'CERTIFICADO';
        $livro->save();

        $livro = new Livro();
        $livro->liv_numero = 2;
        $livro->liv_tipo_livro = 'DIPLOMA';
        $livro->save();
    }
}
