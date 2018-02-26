<?php

use Harpia\Tree\Node;

class NodeTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructor()
    {
        $node = new Node();

        $this->assertEquals('', $node->getName());
        $this->assertNull($node->getData());
        $this->assertTrue($node->isLeaf());
    }

    public function testAddChild()
    {
        $node = new Node('', null, false); // Non-leaf

        $this->assertFalse($node->hasChildren());

        $node->addChild(new Node('', 30));
        $node->addChild(new Node('', 31));

        $this->assertTrue($node->hasChildren());
        $this->assertEquals(2, $node->howManyChildren());

        $expected = [
            30,
            31
        ];

        $childs = $node->getChilds();

        $actual = [
            $childs[0]->getData(),
            $childs[1]->getData()
        ];

        sort($actual, SORT_NUMERIC);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \ErrorException
     */
    public function testAddChildThrowsErrorException()
    {
        $node = new Node(); // Is a leaf
        $toAdd = new Node(); // Also a leaf
        $node->addChild($toAdd);
    }

    public function testFatherGetterAndSetter()
    {
        $node = new Node('', "I am your father !", false); // Non-leaf

        $toAdd = new Node('', 30);
        $node->addChild($toAdd);

        $father = $toAdd->getFather();

        $this->assertEquals("I am your father !", $father->getData());
    }

    public function testNameGetterAndSetter()
    {
        $node = new Node();

        $this->assertEquals('', $node->getName());

        $node->setName('NodeName');

        $this->assertEquals('NodeName', $node->getName());
    }

    public function testSetAsLeaf()
    {
        $node = new Node('', null, false); // Non-leaf

        $this->assertFalse($node->isLeaf());

        $node->setAsLeaf();

        $this->assertTrue($node->isLeaf());
    }

    public function testDataGetterAndSetter()
    {
        $node = new Node(); // Non-leaf

        $this->assertNull($node->getData());

        $node->setData(['data' => 'test']);

        $this->assertNotNull($node->getData());
        $this->assertTrue(is_array($node->getData()));
    }
}
