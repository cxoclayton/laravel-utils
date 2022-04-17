<?php

namespace rccjr\utils\Database\Mappers\JsonSchema;

use Carbon\Carbon;

class Types
{

    const DB_UNSIGNEDINTEGER = "integer";
    const DB_STRING = "string";
    const DB_INTEGER = "integer";
    const DB_TEXT =  "string";
    const DB_DATETIME = '#/$defs/datetime';
    const DB_DATE = '#/$defs/date';
    const DB_DECIMAL = "number";
    const DB_BOOLEAN = "boolean";

    static function get_type($type) {
        return constant('self::'.strtoupper($type));
    }
    static function format($type, $value) {
        $f = 'format_'.strtolower($type);
        return self::$f($value);
    }

    static function format_integer($value) {
        return (int)$value;
    }
    static function format_date($value) {
        try {
            return (new Carbon($value))->toIso8601ZuluString();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    static function format_string($value)
    {
        return (string)$value;
    }

    static function format_text($value) {
        return (string)$value;
    }

    static function format_datetime($value) {
        try {
            return (new Carbon($value))->toIso8601ZuluString();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    static function format_decimal($value) {
        return (float)$value;
    }

    static function format_boolean($value) {
        return $value?true:false;
    }
}
