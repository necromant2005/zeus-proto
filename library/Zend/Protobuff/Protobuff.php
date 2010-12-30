<?php
namespace Zend\Protobuff;

class Protobuff
{
    const INT32  = 'int32';
    const INT64  = 'int64';
    const STRING = 'string';

    const FIELD_REQUIRED = 'required';
    const FIELD_TYPE     = 'type';

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

    public function encode($map, $values)
    {
        $number = 1;
        $output = '';
        foreach ($map as $name=>$options) {
            $output = $this->_charWireType($options, $number) . $this->_encode($values[$name], $options);
            $number++;
        }
        return $output;
    }

    protected function _decode(&$buffer, $options, $number)
    {
        if (!$options[self::FIELD_REQUIRED]) return false;
        if (!$this->_isValidType($buffer[0], $options, $number)) throw new \Exception('Type missmatch');
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

    protected function _encode($value, $options)
    {
        $bytes = $this->_numberToBytes($value);
        if (count($bytes)==1) {
            return chr(reset($bytes)) . chr(1);
        }
        $bytes = array_reverse($bytes);
        $bits = '';
        foreach ($bytes as $byte) {
            $bits .= substr($this->_toBits($byte), 1);
        }
        $bits = $this->_dropFirstZeros($bits);
        $this->_dump($this->_bitsToChars($bits));
        return $this->_bitsToChars($bits);
    }



    protected function _isValidType($char, $options, $number)
    {
        return $this->_charWireType($options, $number)==$char;
    }

    protected function _toBytes($buffer)
    {
        $bytes = array();
        for ($i=0;$i<strlen($buffer);$i++) {
            $bytes[] = ord($buffer[$i]);
        }
        return $bytes;
    }

    protected function _numberToBytes($number)
    {
        if ($number<256) return array($number);

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

    protected function _bitsToChars($bits)
    {
        $prefix = strlen($bits) % 8;
        $bits = str_repeat(0, $prefix) . $bits;
        $chars = '';
        for ($i=0;$i<strlen($bits)/8;$i++) {
            $chars .= chr(substr($bits, $i*8, 8));
        }
        return $chars;
    }

    protected function _charWireType($options, $number)
    {
        $type = $options[self::FIELD_TYPE];
        foreach ($this->_types as $i=>$list) {
            if (!in_array($type, $list)) continue;
            return chr($number<<3|$i);
        }
        throw new \Exception('Unknow type ' . $type);
    }

    protected function _dump($string)
    {
        for ($i=0;$i<strlen($string);$i++) {
            echo '\\' . ord($string[$i]);
        }
        echo PHP_EOL;
        for ($i=0;$i<strlen($string);$i++) {
            echo '\\' . dechex(ord($string[$i]));
        }
        echo PHP_EOL;
    }
}

