<?php

namespace rccjr\utils\Database\Mappers\SiRms;

use Carbon\Carbon;

class Types
{
    const DB_UNSIGNEDINTEGER = "NUMBER";
    const DB_STRING = "STRING";
    const DB_INTEGER = "NUMBER";
    const DB_TEXT = "STRING";
    const DB_DATETIME = 'DATE';
    const DB_DATE = 'DATE';
    const DB_DECIMAL = "NUMBER";
    const DB_BOOLEAN = "BOOLEAN";

    static function get_type($type)
    {
        return constant('self::'.strtoupper($type));
    }

    static function format($type, $value)
    {
        $f = 'format_'.strtolower($type);
        return self::$f($value);
    }

    static function format_integer($value)
    {
        return (int)$value;
    }

    static function format_date($value)
    {
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

    static function format_text($value)
    {
        return (string)$value;
    }

    static function format_datetime($value)
    {
        try {
            return (new Carbon($value))->toIso8601ZuluString();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    static function format_decimal($value)
    {
        return (float)$value;
    }

    static function format_boolean($value)
    {
        return $value ? true : false;
    }


}
