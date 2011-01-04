<?php
namespace Test\AbstractProtobuff;

class AbstractProtobuffMock extends \Zend\Protobuff\AbstractProtobuff
{
    public function _getWireTypeClass(array $options)
    {
        return parent::_getWireTypeClass($options);
    }
}

