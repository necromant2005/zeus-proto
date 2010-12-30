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
        $decoder = new Decoder();
        return $decoder->decode($map, $buffer);
    }

    public function encode($map, $values)
    {
        $encoder = new Encoder();
        return $encoder->encode($map, $values);
    }
}

