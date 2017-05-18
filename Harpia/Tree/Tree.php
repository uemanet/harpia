<?php

namespace Harpia\Tree;

class Tree
{
    protected $root;

    protected $nodes;


    public function __construct()
    {
        $this->nodes = 0;
        $this->root = null;
    }

    /**
     * @param \Harpia\Tree\Node $leaf
     * @throws \ErrorException
     */
    public function addLeaf(Node $leaf)
    {
        if ($this->root == null) {
            throw new \ErrorException("Cannot add leaf node as root node of tree");
        }

        $leaf->setAsLeaf();
        $this->root->addChild($leaf);
        $this->nodes++;
    }

    /**
     * @param \Harpia\Tree\Node $node
     */
    public function addValue(Node $node)
    {
        if ($this->root == null) {
            $this->root = $node;
            $this->nodes++;
            return;
        }

        $this->root->addChild($node);
        $this->nodes++;
    }


    /**
     * @param Tree $tree
     */
    public function addTree(Tree $tree)
    {
        if ($tree->getRoot()) {
            $root = $tree->getRoot();
            $this->addValue($root);
            $this->nodes += $tree->getNodes() - 1;
            return;
        }

        $this->root = $tree->getRoot();
        $this->nodes = $tree->getNodes();
    }

    /**
     * @return null
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @return int
     */
    public function getNodes()
    {
        return $this->nodes;
    }
}
