<?php
namespace Zend\Protobuff;

class Protobuff
{
    const INT32  = 'int32';
    const INT64  = 'int64';
    const UINT32 = 'uint32';
    const UINT64 = 'uint64';
    const SINT32 = 'sint32';
    const SINT64 = 'sint64';
    const BOOL   = 'bool';
    const ENUM   = 'enum';

    const STRING = 'string';

    const FIELD_REQUIRED = 'required';
    const FIELD_TYPE     = 'type';

    const TYPE_VARIANT = 0;
    const TYPE_FIXED64 = 1;
    const TYPE_LENGTH_DELIMITED = 2;
    const TYPE_START_GROUP = 3;
    const TYPE_END_GROUP = 4;
    const TYPE_FIXED32 = 5;
/*
0	Varint	int32, int64, uint32, uint64, sint32, sint64, bool, enum
1	64-bit	fixed64, sfixed64, double
2	Length-delimited	string, bytes, embedded messages, packed repeated fields
3	Start group	groups (deprecated)
4	End group	groups (deprecated)
5	32-bit	fixed32, sfixed32, float
*/

    private static $_wireTypeClass = array(
        self::TYPE_VARIANT => array(
            self::INT32, self::INT64, self::UINT32, self::UINT64, self::SINT32, self::SINT64, self::BOOL, self::ENUM,
        ),
    );

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

    public static function getWireTypeClass(array $options)
    {
        $type = $options[self::FIELD_TYPE];
        foreach (self::$_wireTypeClass as $class=>$types) {
            if (in_array($type, $types)) {
                return $class;
            }
        }
        throw new \Exception('Unsupport wire type "' . $type . '"');
    }
}

