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
                'type'     => Pb\Protobuff::TYPE_INT32,
                'default'  => 1,
            ),
        ), array('a'=>150)), file_get_contents(__DIR__ . '/_files/example/Test1.pb'));
    }
}

