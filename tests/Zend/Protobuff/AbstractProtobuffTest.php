<?php
namespace Test;

use \Zend\Protobuff as Pb;

require __DIR__ . '/_files/AbstractProtobuffMock.php';

class AbstactProtobuffTest extends \PHPUnit_Framework_TestCase
{
    protected $_protobuff = null;

    public function setUp()
    {
        $this->_protobuff = new AbstractProtobuff\AbstractProtobuffMock;
    }

    public function testWireType()
    {
        $this->assertEquals($this->_protobuff->_getWireTypeClass(array(
            'required' => true,
            'type'     => Pb\AbstractProtobuff::TYPE_INT32,
        )), Pb\AbstractProtobuff::CLASS_VARIANT);
    }
}

