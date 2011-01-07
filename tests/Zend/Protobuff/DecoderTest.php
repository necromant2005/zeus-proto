<?php
namespace Test;

use \Zend\Protobuff as Pb;

class DecoderTest extends \PHPUnit_Framework_TestCase
{
    protected $_protobuff = null;

    public function setUp()
    {
        $this->_protobuff = new Pb\Decoder;
    }

    public function testDecodeStart()
    {
        $this->assertEquals($this->_protobuff->decode(array(
            'a' => array(
                'required' => true,
                'type'     => Pb\AbstractProtobuff::TYPE_INT32,
                'default'  => 1,
            ),
        ), file_get_contents(__DIR__ . '/_files/example/Test1.pb')), array(
            'a' => 150,
        ));
    }

    public function testDecode300()
    {
        $this->assertEquals($this->_protobuff->decode(array(
            'a' => array(
                'required' => true,
                'type'     => Pb\AbstractProtobuff::TYPE_INT32,
                'default'  => 1,
            ),
        ), file_get_contents(__DIR__ . '/_files/example/Test2.pb')), array(
            'a' => 300,
        ));
    }

    public function testTwoBytes()
    {
        $this->assertEquals($this->_protobuff->decode(array(
            'node_id' => array(
                'required' => true,
                'type'     => Pb\AbstractProtobuff::TYPE_INT32,
            ),
            'version' => array(
                'required' => true,
                'type'     => Pb\AbstractProtobuff::TYPE_INT64,
            ),
        ), file_get_contents(__DIR__ . '/_files/example/ClockEntry.pb')), array(
            'node_id'=>12, 'version'=>12345
        ));
    }
}

