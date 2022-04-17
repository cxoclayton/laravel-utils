<?php

namespace rccjr\utils\Database\Mappers\Turtle;

use Carbon\Carbon;

class Types
{
    # 0 => "integer",1 => "string",10 => "text",13 => "datetime",50 => "date",64 => "decimal",66 => "boolean",
#xsd  # anyURI, base64Binary, boolean, date, dateTime, decimal, double, duration, float, hexBinary, gDay, gMonth, gMonthDay, gYear, gYearMonth, NOTATION, QName, string, and time
# xsd:integer, xsd:nonPositiveInteger, xsd:negativeInteger, xsd:long, xsd:int, xsd:short, xsd:byte, xsd:nonNegativeInteger, xsd:unsignedLong, xsd:unsignedInt, xsd:unsignedShort, xsd:unsignedByte, xsd:positiveInteger

const DB_UNSIGNEDINTEGER = "xsd:unsignedInt";
const DB_STRING = "xsd:string";
const DB_INTEGER = "xsd:int";
const DB_TEXT =  "xsd:string";
const DB_DATETIME = "xsd:dateTime";
const DB_DATE = "xsd:Date";
const DB_DECIMAL = "xsd:decimal";
const DB_BOOLEAN = "xsd:boolean";

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
