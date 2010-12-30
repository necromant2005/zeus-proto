<?php
namespace Zend\Protobuff;

class Decoder
{
    private $_wireTypeClass = array(
        0 => array(
            Protobuff::INT32, Protobuff::INT64
        ),
    );

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

    protected function _getWireTypeClass(array $options)
    {
        $type = $options[Protobuff::FIELD_TYPE];
        foreach ($this->_wireTypeClass as $class=>$types) {
            if (in_array($type, $types)) {
                return $type;
            }
        }
        throw new \Exception('Unsupport wire type "' . $type . '"');
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
                throw new \Exception();
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

