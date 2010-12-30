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
}

