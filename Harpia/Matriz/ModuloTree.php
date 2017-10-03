<?php

namespace Harpia\Matriz;

use Harpia\Tree\Node;
use Harpia\Tree\Tree;
use Illuminate\Support\Collection;
use Modulos\Academico\Models\ModuloMatriz;

class ModuloTree extends Tree
{
    public function __construct(ModuloMatriz $moduloMatriz)
    {
        parent::__construct();
        $this->addValue(new Node($moduloMatriz->mdo_nome, $moduloMatriz, false));
        $this->buildModulosChildren();
    }

    private function buildModulosChildren()
    {
        $disciplinas = new Collection($this->root->getData()->disciplinas->toArray());

        # Ordena por ordem alfabetica
        $disciplinas = $disciplinas->sortBy('dis_nome');

        foreach ($disciplinas as $disciplina){
            $this->root->addChild(new Node($disciplina['dis_nome'], $disciplina));
            $this->nodes++;
        }
    }
}