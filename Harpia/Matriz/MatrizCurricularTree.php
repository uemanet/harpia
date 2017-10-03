<?php
declare(strict_types=1);

namespace Harpia\Matriz;

use Harpia\Tree\Node;
use Harpia\Tree\Tree;
use Modulos\Academico\Models\MatrizCurricular;

class MatrizCurricularTree extends Tree
{
    protected $matrizCurricular;

    public function __construct(MatrizCurricular $matrizCurricular)
    {
        parent::__construct();
        $this->addValue(new Node($matrizCurricular->mtc_titulo, $matrizCurricular, false));
        $this->buildMatrizChildren();
    }

    private function buildMatrizChildren()
    {
        $modulos = $this->root->getData()->modulos;

        foreach ($modulos as $modulo) {
            $this->addTree(new ModuloTree($modulo));
        }
    }

    public function toArray() : array
    {
        $tree = [$this->root->getName() => []];

        $cargaHorariaMatriz = 0;
        foreach ($this->root->getChilds() as $modulo) {

            $cargaHorariaModulo = 0;
            foreach ($modulo->getChilds() as $disciplina) {
                $cargaHorariaModulo += $disciplina->getData()['dis_carga_horaria'];

                $tree[$this->root->getName()][$modulo->getName()]['disciplinas'][$disciplina->getName()] = [
                    'id' => $disciplina->getData()['dis_id'],
                    'carga_horaria' => $disciplina->getData()['dis_carga_horaria']
                ];

                $tree[$this->root->getName()][$modulo->getName()]['carga_horaria'] = $cargaHorariaModulo;
            }

            $cargaHorariaMatriz += $cargaHorariaModulo;
        }

        $tree[$this->root->getName()]['carga_horaria_matriz'] = $cargaHorariaMatriz;
        return $tree;
    }
}