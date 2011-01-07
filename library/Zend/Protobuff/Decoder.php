<?php
namespace Zend\Protobuff;

class Decoder extends AbstractProtobuff
{
    public function decode(array $map, $buffer)
    {
        $decoded = array();
        $number = 1;
        foreach ($map as $name=>$options) {
            if (!$this->_decodeWireType($buffer, $number, $options)) {
                throw new \Exception('Wrong protobuff "' . $number . '" :' . $buffer);
            }
            $buffer = substr($buffer, 1);
            $encodedValue = $this->_getEncodedValue($buffer, $number, $map);
            $decoded[$name] = $this->_decodeValue($encodedValue, $options);
            $number++;
        }
        return $decoded;
    }

    protected function _decodeWireType($buffer, $number, array $options)
    {
        return $buffer[0] = $this->_encodeWireType($number, $options);
    }

    protected function _decodeValue($encodedValue, array $options)
    {
        switch ($this->_getWireTypeClass($options)) {
            case static::CLASS_VARIANT:
                return $this->_decodeValue128($encodedValue, $options);
            default:
                throw new \Exception('Can\'t decode class ' . $this->_getWireTypeClass($options));
        }
    }

    protected function _getEncodedValue(&$buffer, $number, array $map)
    {
        if (count($map)==$number) {
            return $buffer;
        }

        $_map = array();
        $i = 1;
        foreach ($map as $name=>$options) {
            $options[static::FIELD_NAME] = $name;
            $_map[$i] = $options;
            $i++;
        }
        $nextEncodedWireType = $this->_encodeWireType($number+1, $_map[$number+1]);

        $encodedValue = '';
        for ($i=0;$i<strlen($buffer);$i++) {
            $char = $buffer[$i];
            if ($nextEncodedWireType==$char) {
                $buffer = substr($buffer, $i);
                return $encodedValue;
            }
            $encodedValue .= $char;
        }
        throw new \Exception('Cant get encoded value');
    }

    protected function _decodeValue128($encodedValue, array $options)
    {
        $bytes = array();
        for ($i=0;$i<strlen($encodedValue);$i++) {
            array_unshift($bytes, decbin(ord($encodedValue[$i])));
        }
        $bytes = array_map(function($value){ return str_repeat('0', 8-strlen($value)) . $value; }, $bytes);
        $bytes = array_map(function($value){ return substr($value, 1); }, $bytes);
        $bits = join('', $bytes);
        $bits = ltrim($bits, '0');
        return bindec($bits);
    }
}

