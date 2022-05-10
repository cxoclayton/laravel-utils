<?php

namespace rccjr\utils\Database\Mappers\JsonSchema;

use Carbon\Carbon;

class Types
{

    const DB_UNSIGNEDINTEGER = "integer";
    const DB_STRING = "string";
    const DB_INTEGER = "integer";
    const DB_TEXT = "string";
    const DB_DATETIME = '#/$defs/datetime-string';
    const DB_DATE = '#/$defs/date-string';
    const DB_DECIMAL = "number";
    const DB_BOOLEAN = "boolean";
    const DB_DB_BIGINT = "integer";
    
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

    static function getStandardDefs()
    {
        $n =  collect();
        $n->put('datetime-string', [
                "type" => "string",
                "format" => "date-time",
                'examples' => [
                    '2022-01-11T02:37:14Z',
                    '2022-01-11T02:38:13+00:00'
                ],
                '$comments' => [
                    'This should be an ISO 8601 String formatted for date time with time zone.'
                ]
            ]);
        $n->put('date-string', [
            "type" => "string",
            "format" => "date",
            'examples' => [
                '2022-01-11',
                '2022-01-11Z'
            ],
            '$comments' => [
                'This should be an ISO 8601 String formatted for date time with time zone.'
            ]
        ]);
        return $n;

    }
}
