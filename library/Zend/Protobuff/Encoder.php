<?php
namespace Zend\Protobuff;

class Encoder
{
    public function encode(array $map, array $values)
    {
        $buffer = '';
        $number = 1;
        foreach ($map as $name=>$options) {
            if ($options[Protobuff::FIELD_REQUIRED] && !array_key_exists($name, $values)) {
                throw new \Exception('Field "' . $name . '" is required');
            }
            $value = $values[$name];
            $buffer .= $this->_encodeWireType($number, $options) . $this->_encodeValue($value, $options);
            $number++;
        }
        return $buffer;
    }

    protected function _encodeWireType($number, array $options)
    {
        return chr($number<<3|Protobuff::getWireTypeClass($options));
    }

    protected function _encodeValue($value, array $options)
    {
        switch (Protobuff::getWireTypeClass($options)) {
            case 0:
                return $this->_encodeVariant128($value);
            default:
                throw new \Exception('Can\'t encode class' .  Protobuff::getWireTypeClass($options));
        }
    }

    protected function _encodeVariant128($value)
    {
        if ($value<256) return chr($value) . chr(1);
    }
}

