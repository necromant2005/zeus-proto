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

    public function t_estDecodeStart()
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

    public function t_estEncodeStart()
    {
        $this->assertEquals($this->_protobuff->encode(array(
            'a' => array(
                'required' => true,
                'type'     => ProtocolBuffers::INT32,
                'default'  => 1,
            ),
        ), array('a'=>150)), file_get_contents(__DIR__ . '/_files/example/Test1.pb'));
    }

    public function t_estDecodeClockEntry()
    {
        $this->assertEquals($this->_protobuff->decode(array(
            'node_id' => array(
                'required' => true,
                'type'     => ProtocolBuffers::INT32
            ),
            'version' => array(
                'required' => true,
                'type'     => ProtocolBuffers::INT64
            )
        ), file_get_contents(__DIR__ . '/_files/example/ClockEntry.pb')), array(
            'node_id' => 12,
            'version' => 12345,
        ));
    }

    public function testEncodeClockEntry()
    {

    }
}

