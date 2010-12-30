<?php
namespace Zend\Protobuff;

class Encoder
{
    private $_wireTypeClass = array(
        0 => array(
            Protobuff::INT32, Protobuff::INT64
        ),
    );

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

    protected function _encodeWireType($number, array $options)
    {
        return chr($number<<3|$this->_getWireTypeClass($options));
    }

    protected function _encodeValue($value, array $options)
    {
        switch ($this->_getWireTypeClass($options)) {
            case 0:
                return $this->_encodeVariant128($value);
            default:
                throw new \Exception('Can\'t encode class' .  $this->_getWireTypeClass($options));
        }
    }

    protected function _encodeVariant128($value)
    {
        if ($value<256) return chr($value) . chr(1);
    }
}

