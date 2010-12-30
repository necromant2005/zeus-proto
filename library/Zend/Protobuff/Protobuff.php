<?php
namespace Zend\Protobuff;

class Protobuff
{
    /* fields */
    const FIELD_REQUIRED = 'required';
    const FIELD_TYPE     = 'type';

    /* types */
    const TYPE_INT32  = 'int32';
    const TYPE_INT64  = 'int64';
    const TYPE_UINT32 = 'uint32';
    const TYPE_UINT64 = 'uint64';
    const TYPE_SINT32 = 'sint32';
    const TYPE_SINT64 = 'sint64';
    const TYPE_BOOL   = 'bool';
    const TYPE_ENUM   = 'enum';

    const TYPE_FIXED64 = 'fixed64';
    const TYPE_SFIXED64 = 'sfixed64';
    const TYPE_DOUBLE = 'double';

    const TYPE_STRING = 'string';
    const TYPE_BYTES  = 'bytes';
    const TYPE_EMBEDDED_MESSAGE = 'embedded_messages';
    const TYPE_PACKED_REPEATED_FIELDS = 'packed_repeated_fields';

    const TYPE_FIXED32 = 'fixed32';
    const TYPE_SFIXED32 = 'sfixed32';
    const TYPE_FLOAT = 'float';

    /* classes */
    const CLASS_VARIANT = 0;
    const CLASS_FIXED64 = 1;
    const CLASS_LENGTH_DELIMITED = 2;
    const CLASS_FIXED32 = 5;

    private static $_wireTypeClass = array(
        self::CLASS_VARIANT => array(
            self::TYPE_INT32, self::TYPE_INT64,
            self::TYPE_UINT32, self::TYPE_UINT64,
            self::TYPE_SINT32, self::TYPE_SINT64,
            self::TYPE_BOOL, self::TYPE_ENUM,
        ),
        self::CLASS_FIXED64 => array(
            self::TYPE_FIXED64, self::TYPE_SFIXED64, self::TYPE_DOUBLE,
        ),
        self::CLASS_FIXED32 => array(
            self::TYPE_FIXED32, self::TYPE_SFIXED32, self::TYPE_FLOAT,
        ),
        self::CLASS_LENGTH_DELIMITED => array(
            self::TYPE_STRING, self::TYPE_BYTES,
            self::TYPE_EMBEDDED_MESSAGE, self::TYPE_PACKED_REPEATED_FIELDS,
        )
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

