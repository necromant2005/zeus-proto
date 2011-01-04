<?php
namespace Test;

use \Zend\Protobuff as Pb;

class EncoderTest extends \PHPUnit_Framework_TestCase
{
    protected $_protobuff = null;

    public function setUp()
    {
        $this->_protobuff = new Pb\Encoder;
    }

    public function testEncodeStart()
    {
        $this->assertEquals($this->_protobuff->encode(array(
            'a' => array(
                'required' => true,
                'type'     => Pb\AbstractProtobuff::TYPE_INT32,
            ),
        ), array('a'=>150)), file_get_contents(__DIR__ . '/_files/example/Test1.pb'));
    }

    public function testEncodeTwoBytes()
    {
        $this->assertEquals($this->_protobuff->encode(array(
            'node_id' => array(
                'required' => true,
                'type'     => Pb\AbstractProtobuff::TYPE_INT32,
            ),
            'version' => array(
                'required' => true,
                'type'     => Pb\AbstractProtobuff::TYPE_INT64,
            ),
        ), array('node_id'=>12, 'version'=>12345)), file_get_contents(__DIR__ . '/_files/example/ClockEntry.pb'));
    }
}

