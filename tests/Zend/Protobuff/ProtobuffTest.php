<?php
namespace Test;

use \Zend\Protobuff\Protobuff as ProtocolBuffers;

class ProtobuffTest extends \PHPUnit_Framework_TestCase
{
    protected $_protobuff = null;

    public function setUp()
    {
        $this->_protobuff = new ProtocolBuffers;
    }

    public function testDecodeStart()
    {
        $this->assertEquals($this->_protobuff->decode(array(
            'a' => array(
                'required' => true,
                'type'     => ProtocolBuffers::INT32,
                'default'  => 1,
            ),
        ), file_get_contents(__DIR__ . '/_files/example/Test1.pb')), array(
            'a' => 150,
        ));
    }

    public function testEncodeStart()
    {
        $this->assertEquals($this->_protobuff->encode(array(
            'a' => array(
                'required' => true,
                'type'     => ProtocolBuffers::INT32,
                'default'  => 1,
            ),
        ), array('a'=>150)), file_get_contents(__DIR__ . '/_files/example/Test1.pb'));
    }

    public function testGetWireTypeClass()
    {
        $this->assertEquals(ProtocolBuffers::getWireTypeClass(array(
            ProtocolBuffers::FIELD_TYPE=>ProtocolBuffers::INT32
        )), ProtocolBuffers::TYPE_VARIANT);
    }
}

