<?php
namespace Zend\Protobuff;

class Protobuff
{
    const INT32  = 'int32';
    const INT64  = 'int64';
    const STRING = 'string';

    const FIELD_REQUIRED = 'required';
    const FIELD_TYPE     = 'type';

    private $_types = array(
        8 => array(
            self::INT32
        ),
        16 => array(
            self::INT64
        ),
    );

    public function decode($map, $buffer)
    {
        $number = 1;
        $output = array();
        foreach ($map as $name=>$options) {
            $output[$name] = $this->_decode($buffer, $options, $number);
            $number++;
        }
        return $output;
    }

    protected function _decode(&$buffer, $options)
    {
        if (!$options[self::FIELD_REQUIRED]) return false;
        if ($this->_isValidType($buffer[0], $options, $number)) throw new Exception('Type missmatch');
        $_buffer = substr($buffer, 1);
        $bytes = $this->_toBytes($_buffer);
        $bytes = array_reverse($bytes);

        $bits = '';
        foreach ($bytes as $byte) {
            $bits .= substr($this->_toBits($byte), 1);
        }
        $bits = $this->_dropFirstZeros($bits);
        return $this->_bitsToNumber($bits);
    }

    protected function _isValidType($char, $options, $number)
    {
        $ord = ord($char);
        if (!in_array($ord, $this->_types)) return false;
        foreach ($this->types[$ord] as $type) {
            if ($type==$options[self::FIELD_TYPE]) return true;
        }
        return false;
    }

    protected function _toBytes($buffer)
    {
        $bytes = array();
        for ($i=0;$i<strlen($buffer);$i++) {
            $bytes[] = ord($buffer[$i]);
        }
        return $bytes;
    }

    protected function _toBits($number)
    {
        $bits = decbin($number);
        return str_repeat('0', 8-strlen($bits)) . $bits;
    }

    protected function _dropFirstZeros($bits)
    {
        return strstr($bits, '1');
    }



    protected function _bitsToNumber($bits)
    {
        $number = 0;
        for ($i=0;$i<strlen($bits)/8;$i++) {
            $number += bindec(substr($bits, $i*8, 8));
        }
        return $number;
    }
}

