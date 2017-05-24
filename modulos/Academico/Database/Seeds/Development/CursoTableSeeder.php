<?php

namespace Modulos\Academico\Database\Seeds\Development;

use Illuminate\Database\Seeder;
use Modulos\Academico\Models\ConfiguracaoCurso;
use Modulos\Academico\Models\Curso;
use Faker\Factory;

class CursoTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();
        $arrCursosIds = [];

        $curso = new Curso();
        $curso->crs_dep_id = 1;
        $curso->crs_nvc_id = 1;
        $curso->crs_prf_diretor = 1;
        $curso->crs_nome = 'Ciência da Computação';
        $curso->crs_sigla = 'CC';
        $curso->crs_descricao = 'Curso Superior em Ciência da Computação';
        $curso->crs_resolucao = 'MEC 45/2014';
        $curso->crs_autorizacao = 'MEC 15/2014';
        $curso->crs_data_autorizacao = $faker->date('d/m/Y');
        $curso->crs_eixo = 'Computação';
        $curso->crs_habilitacao = 'Bacharel';
        $curso->save();
        $arrCursosIds[] = $curso->crs_id;

        $curso = new Curso();
        $curso->crs_dep_id = 1;
        $curso->crs_nvc_id = 1;
        $curso->crs_prf_diretor = 2;
        $curso->crs_nome = 'Engenharia da Computação';
        $curso->crs_sigla = 'ENGCOMP';
        $curso->crs_descricao = 'Curso Superior em Engenharia da Computação';
        $curso->crs_resolucao = 'MEC 68/2011';
        $curso->crs_autorizacao = 'MEC 11/2014';
        $curso->crs_data_autorizacao = $faker->date('d/m/Y');
        $curso->crs_eixo = 'Computação';
        $curso->crs_habilitacao = 'Bacharel';
        $curso->save();
        $arrCursosIds[] = $curso->crs_id;

        $curso = new Curso();
        $curso->crs_dep_id = 1;
        $curso->crs_nvc_id = 1;
        $curso->crs_prf_diretor = 3;
        $curso->crs_nome = 'Sistemas de Informação';
        $curso->crs_sigla = 'SI';
        $curso->crs_descricao = 'Curso Superior em Sistemas de Informação';
        $curso->crs_resolucao = 'MEC 17/2008';
        $curso->crs_autorizacao = 'MEC 11/2009';
        $curso->crs_data_autorizacao = $faker->date('d/m/Y');
        $curso->crs_eixo = 'Computação';
        $curso->crs_habilitacao = 'Bacharel';
        $curso->save();
        $arrCursosIds[] = $curso->crs_id;

        $curso = new Curso();
        $curso->crs_dep_id = 1;
        $curso->crs_nvc_id = 1;
        $curso->crs_prf_diretor = 4;
        $curso->crs_nome = 'Engenharia de Software';
        $curso->crs_sigla = 'ENGSOFT';
        $curso->crs_descricao = 'Curso Superior em Engenharia de Software';
        $curso->crs_resolucao = 'MEC 15/2010';
        $curso->crs_autorizacao = 'MEC 95/2012';
        $curso->crs_data_autorizacao = $faker->date('d/m/Y');
        $curso->crs_eixo = 'Computação';
        $curso->crs_habilitacao = 'Bacharel';
        $curso->save();
        $arrCursosIds[] = $curso->crs_id;

        // Cursos Técnicos
        $curso = new Curso();
        $curso->crs_dep_id = 1;
        $curso->crs_nvc_id = 2;  // Nivel Tecnico
        $curso->crs_prf_diretor = 5;
        $curso->crs_nome = 'Técnico em Informática';
        $curso->crs_sigla = 'TI';
        $curso->crs_descricao = 'Curso Técnico com habilitação em Informática';
        $curso->crs_resolucao = 'MEC 20/2010';
        $curso->crs_autorizacao = 'MEC 90/2012';
        $curso->crs_data_autorizacao = $faker->date('d/m/Y');
        $curso->crs_eixo = 'Computação';
        $curso->crs_habilitacao = 'Técnico';
        $curso->save();
        $arrCursosIds[] = $curso->crs_id;

        $configuracoes = [
            'media_min_aprovacao' => '7.0',
            'media_min_final' => '5.0',
            'media_min_aprovacao_final' => '7.0',
            'modo_recuperacao' => 'substituir_media_final',
            'conceitos_aprovacao' => json_encode(["Bom", "Muito Bom", "Excelente"])
        ];

        $func = function ($id) use ($configuracoes) {
            foreach ($configuracoes as $nome => $valor) {
                ConfiguracaoCurso::create([
                    'cfc_crs_id' => $id,
                    'cfc_nome' => $nome,
                    'cfc_valor' => $valor
                ]);
            }
            return true;
        };

        array_map($func, $arrCursosIds);
    }
}
