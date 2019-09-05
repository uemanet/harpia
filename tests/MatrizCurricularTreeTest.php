<?php

use Harpia\Matriz\MatrizCurricularTree;

class MatrizCurricularTreeTest extends \Tests\ModulosTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    private function mockData()
    {
        // Mock da estrutura de matriz
        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
            'crs_nvc_id' => 2,
        ]);

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id
        ]);

        $modulosMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class, 2)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $disciplinas = factory(Modulos\Academico\Models\Disciplina::class, 6)->create([
            'dis_nvc_id' => $curso->crs_nvc_id
        ]);

        $modulosDisciplina = new \Illuminate\Support\Collection();
        foreach ($modulosMatriz as $key => $moduloMatriz) {
            for ($i = $key * 3; $i < 3 * ($key + 1); $i++) {
                $modulosDisciplina[] = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
                    'mdc_dis_id' => $disciplinas[$i]->dis_id,
                    'mdc_mdo_id' => $moduloMatriz->mdo_id,
                    'mdc_tipo_disciplina' => 'eletiva'
                ]);
            }
        }

        $matrizCurricular = \Modulos\Academico\Models\MatrizCurricular::find($matrizCurricular->mtc_id);

        // Estrutura esperada
        $disciplinasPrimeiroModulo = $modulosMatriz->first()->disciplinas->sortBy('dis_nome');

        $disciplinasPrimeiroModuloArray = [];

        $cargaHorariaPrimeiroModulo = 0;

        foreach ($disciplinasPrimeiroModulo as $disciplina) {
            $data = [];

            $data['id'] = $disciplina->dis_id;
            $data['nome'] = $disciplina->dis_nome;
            $data['carga_horaria'] = $disciplina->dis_carga_horaria;

            $cargaHorariaPrimeiroModulo += $disciplina->dis_carga_horaria;

            $disciplinasPrimeiroModuloArray[$disciplina->dis_nome] = $data;
        }

        $disciplinasSegundoModulo = $modulosMatriz->last()->disciplinas->sortBy('dis_nome');
        $disciplinasSegundoModuloArray = [];

        $cargaHorariaSegundoModulo = 0;

        foreach ($disciplinasSegundoModulo as $disciplina) {
            $data = [];

            $data['id'] = $disciplina->dis_id;
            $data['nome'] = $disciplina->dis_nome;
            $data['carga_horaria'] = $disciplina->dis_carga_horaria;

            $cargaHorariaSegundoModulo += $disciplina->dis_carga_horaria;

            $disciplinasSegundoModuloArray[$disciplina->dis_nome] = $data;
        }

        $matriz = [
            $modulosMatriz->first()->mdo_nome => [
                'disciplinas' => $disciplinasPrimeiroModuloArray,
                'carga_horaria' => $cargaHorariaPrimeiroModulo,
                'descricao' => $modulosMatriz->first()->mdo_descricao
            ],
            $modulosMatriz->last()->mdo_nome => [
                'disciplinas' => $disciplinasSegundoModuloArray,
                'carga_horaria' => $cargaHorariaSegundoModulo,
                'descricao' => $modulosMatriz->last()->mdo_descricao
            ],
            'carga_horaria_matriz' => $cargaHorariaPrimeiroModulo + $cargaHorariaSegundoModulo
        ];

        $expected[$matrizCurricular->mtc_titulo] = $matriz;

        return [$matrizCurricular, $expected];
    }

    public function testToArray()
    {
        list($matrizCurricular, $expected) = $this->mockData();

        $matrizTree = new MatrizCurricularTree($matrizCurricular);
        $result = $matrizTree->toArray();

        $result = array_pop($result);
        $expected = array_pop($expected);

        foreach ($expected as $key => $item) {
            if (is_array($item)) {
                $this->assertTrue(array_key_exists($key, $result));
                $this->assertEquals($item, $result[$key]);
            }
        }

        $this->assertEquals($expected['carga_horaria_matriz'], $result['carga_horaria_matriz']);
    }
}
