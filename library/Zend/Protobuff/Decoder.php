<?php
namespace Zend\Protobuff;

class Decoder extends AbstractProtobuff
{
    public function decode(array $map, $buffer)
    {
        $decoded = array();
        foreach ($map as $name=>$options) {
            $this->_decodeWireType($buffer, $number, $options);
            $decoded[$name] = $this->_decodeValue($buffer, $options);
            $number++;
        }
        return $decoded;
    }

    protected function _decodeWireType(&$buffer, $number, array $options)
    {
        $encodedWireType = $buffer[0];
        $buffer = substr($buffer, 1);
    }

    protected function _decodeValue(&$buffer, array $options)
    {
        switch ($this->_getWireTypeClass($options)) {
            case 0:
                return $this->_decodeValue128($buffer, $options);
            default:
                throw new \Exception('Can\'t decode class ' . $this->_getWireTypeClass($options));
        }
    }

    protected function _decodeValue128(&$buffer, array $options)
    {
        $encodedValue = substr($buffer, 0, 2);
        $buffer = substr($buffer, 2);
        $bytes = array();
        for ($i=0;$i<strlen($encodedValue);$i++) {
            array_unshift($bytes, decbin(ord($encodedValue[$i])));
        }
        $bytes = array_map(function($value){ return str_repeat('0', 8-strlen($value)) . $value; }, $bytes);
        $bytes = array_map(function($value){ return substr($value, 1); }, $bytes);
        $bits = join('', $bytes);
        $bits = ltrim($bits, '0');
        $bits = str_repeat('0', strlen($bits)%8) . $bits;
        $value = 0;
        for ($i=0;$i<strlen($bits)/8;$i++) {
            $value += bindec(substr($bits, $i*8, 8));
        }
        return $value;
    }
}

