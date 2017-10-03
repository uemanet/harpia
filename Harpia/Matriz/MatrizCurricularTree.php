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

        foreach ($this->root->getChilds() as $modulo) {
            foreach ($modulo->getChilds() as $disciplina) {
                $tree[$this->root->getName()][$modulo->getName()][$disciplina->getName()] = $disciplina->getData()['dis_id'];
            }
        }

        return $tree;
    }
}