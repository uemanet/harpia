<?php
declare(strict_types=1);

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
    public function addLeaf(Node $leaf): void
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
     * @throws \ErrorException
     */
    public function addValue(Node $node): void
    {
        if ($this->root == null && $node->isLeaf()) {
            throw new \ErrorException("Cannot add leaf node as root node of tree");
        }

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
     * @throws \ErrorException
     */
    public function addTree(Tree $tree): void
    {
        if ($tree->getRoot()) {
            $root = $tree->getRoot();
            $this->addValue($root);
            $this->nodes += $tree->getNodes() - 1;
        }
    }

    /**
     * @return null|Node
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @return mixed
     */
    public function getNodes()
    {
        return $this->nodes;
    }
}
