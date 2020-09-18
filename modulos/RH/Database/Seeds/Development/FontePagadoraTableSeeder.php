<?php
namespace Modulos\RH\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\RH\Models\FontePagadora;
use Modulos\RH\Models\Funcao;

class FontePagadoraTableSeeder extends Seeder
{
    public function run()
    {

        $fonte_pagadora = new FontePagadora();
        $fonte_pagadora->fpg_razao_social = 'Rebeca e Tiago Corretores Associados ME';
        $fonte_pagadora->fpg_nome_fantasia = 'RT Associados';
        $fonte_pagadora->fpg_cnpj = '82035500000123';
        $fonte_pagadora->fpg_cep = '06065220';
        $fonte_pagadora->fpg_endereco = 'Rua Santo Roverco';
        $fonte_pagadora->fpg_bairro = 'Jaguaribe';
        $fonte_pagadora->fpg_numero = '13';
        $fonte_pagadora->fpg_complemento = 'sdsadsad';
        $fonte_pagadora->fpg_cidade = 'Jaguaribe';
        $fonte_pagadora->fpg_email = 'suporte@rebecaetiagocorretoresassociadosme.com.br';
        $fonte_pagadora->fpg_uf = 'MA';
        $fonte_pagadora->fpg_telefone = '1129700236';
        $fonte_pagadora->fpg_celular = '11983916477';
        $fonte_pagadora->fpg_observacao = 'Teste';

        $fonte_pagadora->save();




    }
}
