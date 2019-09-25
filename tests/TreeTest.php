<?php

use Harpia\Tree\Tree;
use Harpia\Tree\Node;

class TreeTest extends \PHPUnit\Framework\TestCase
{
    protected $tree;

    public function setUp(): void
    {
        $this->tree = new Tree();
    }

    public function testAddLeaf()
    {
        $this->tree->addValue(new Node('node', random_int(20, 100), false));
        $this->tree->addLeaf(new Node('node', 30));

        $root = $this->tree->getRoot();
        $children = $root->getChilds();

        $leafNode = array_pop($children);

        $this->assertTrue($leafNode->isLeaf());
        $this->assertEquals(30, $leafNode->getData());
    }

    public function testAddLeafThrowsErrorException()
    {
        // Try add an leaf child as root
        $this->expectException(\ErrorException::class);
        $this->tree->addLeaf(new Node('node', random_int(20, 100)));
    }

    public function testAddValueThrowsErrorException()
    {
        // Try add an leaf child as root
        $this->expectException(\ErrorException::class);
        $this->tree->addValue(new Node('node', random_int(20, 100)));
    }

    public function testAddValue()
    {
        $values[] = random_int(1, 10);
        $values[] = random_int(1, 10);
        $values[] = random_int(1, 10);

        sort($values, SORT_NUMERIC);

        foreach ($values as $value) {
            $this->tree->addValue(new Node('node', $value, false));
        }

        $root = $this->tree->getRoot();

        // Dados = Root + Childs
        $fromTree[] = $root->getData();
        foreach ($root->getChilds() as $child) {
            $fromTree[] = $child->getData();
        }

        sort($fromTree, SORT_NUMERIC);
        $this->assertEquals($values, $fromTree);
    }

    public function testAddTreeWithPreviousRoot()
    {
        $sndTree = new Tree();

        $sndTree->addValue(new Node('node', random_int(20, 100), false));
        $sndTree->addValue(new Node('node', random_int(20, 100), false));
        $sndTree->addValue(new Node('node', random_int(20, 100), false));
        $sndTree->addValue(new Node('node', random_int(20, 100), false));

        // The first node value
        $value = 10;
        $this->tree->addValue(new Node('node', $value, false));
        $this->tree->addValue(new Node('node', random_int(20, 100), false));
        $this->tree->addValue(new Node('node', random_int(20, 100), false));
        $this->tree->addValue(new Node('node', random_int(20, 100), true));

        $this->assertEquals(4, $sndTree->getNodes());
        $this->assertEquals(4, $this->tree->getNodes());

        $this->tree->addTree($sndTree);
        $this->assertEquals(8, $this->tree->getNodes());

        // Must keep previous root
        $root = $this->tree->getRoot();

        $this->assertTrue(is_int($root->getData()));
        $this->assertEquals($value, $root->getData());
    }

    public function testAddTreeWithoutPreviousRoot()
    {
        $sndTree = new Tree();

        $value = 10;

        $sndTree->addValue(new Node('node', $value, false));
        $sndTree->addValue(new Node('node', random_int(20, 100), false));
        $sndTree->addValue(new Node('node', random_int(20, 100), false));
        $sndTree->addValue(new Node('node', random_int(20, 100), false));

        $this->assertEquals(4, $sndTree->getNodes());
        $this->assertEquals(0, $this->tree->getNodes());

        $this->assertNull($this->tree->getRoot());

        $this->tree->addTree($sndTree);
        $this->assertFalse(is_null($this->tree->getRoot()));
        $this->assertEquals(4, $this->tree->getNodes());

        // Must keep the root of tree added
        $root = $this->tree->getRoot();

        $this->assertTrue(is_int($root->getData()));
        $this->assertEquals($value, $root->getData());
    }

    public function testGetRoot()
    {
        // Nenhum node
        $this->assertNull($this->tree->getRoot());

        $value = 10;

        // The first node
        $this->tree->addValue(new Node('node', $value, false));
        $this->tree->addValue(new Node('node', random_int(20, 100), false));
        $this->tree->addValue(new Node('node', random_int(20, 100), false));
        $this->tree->addValue(new Node('node', random_int(20, 100), false));

        $root = $this->tree->getRoot();

        $this->assertTrue(is_int($root->getData()));
        $this->assertEquals($value, $root->getData());
    }

    public function testGetNodes()
    {
        $this->assertEquals(0, $this->tree->getNodes());

        $this->tree->addValue(new Node('node', random_int(1, 100), false));
        $this->tree->addValue(new Node('node', random_int(1, 100), false));
        $this->tree->addValue(new Node('node', random_int(1, 100), false));
        $this->tree->addValue(new Node('node', random_int(1, 100), true));

        $this->assertEquals(4, $this->tree->getNodes());
    }
}
