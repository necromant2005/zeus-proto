<?php
namespace Zend\Protobuff;

class Encoder extends AbstractProtobuff
{
    public function encode(array $map, array $values)
    {
        $buffer = '';
        $number = 1;
        foreach ($map as $name=>$options) {
            if ($options[AbstractProtobuff::FIELD_REQUIRED] && !array_key_exists($name, $values)) {
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
        return chr($number<<3|$this->_getWireTypeClass($options));
    }

    protected function _encodeValue($value, array $options)
    {
        switch ($this->_getWireTypeClass($options)) {
            case static::CLASS_VARIANT:
                return $this->_encodeVariant128($value);
            default:
                throw new \Exception('Can\'t encode class' .  $this->_getWireTypeClass($options));
        }
    }

    protected function _encodeVariant128($value)
    {
        if ($value<128) return chr($value);
        if ($value<256) return chr($value) . chr(1);

        $bits = decbin($value);
        $bits = str_repeat('0', 8-strlen($bits)%8) . $bits;

        $bytes = array();
        for ($i=0;$i<strlen($bits)/8;$i++) {
            $byte = substr($bits, $i*8, 8);
            if ($i==strlen($bits)/8-1) {
                $byte[0] = '1';
            }
            if ($i==0) {
                $byte = substr($byte, 1) . '0';
            }
            array_unshift($bytes, $byte);
        }
        $bytes = array_map(function($value){ return chr(bindec($value)); }, $bytes);
        return join('', $bytes);
    }
}

