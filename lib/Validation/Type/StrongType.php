<?php
declare(strict_type=1);


namespace Validation\Type;

use Validation\Configuration;

/**
 * for PHP < 7.4
 * Class StrongType
 * @deprecated for version less than PHP 7.4
 * @package Validation\Type
 */
class StrongType
{
    const Int = "int";
    const Double = "double";
    const Float = "float";
    const String = "string";
    const DateTime = 'Datetime';
    const Array = 'array';

    public static function belong($type){
        return $type==self::Float||$type==self::String||$type==self::DateTime||$type==self::Double||$type==self::Int;
    }

    public static function isArray($type){
        return strpos($type, '[') !== false;
    }

    public static function isNumber($type){
        return $type==self::Float||$type==self::Double||$type==self::Int;
    }

    /**
     * make $value return true-ly type
     * if value is not fit to type
     * default value of type is return
     * 0 for number type or timestamp
     * @param $type
     * @param $value
     * @return false|float|int
     */
    public static function make(Type $type, $value){
        switch ($type->getType()){
            case self::Int:
                return (int)$value;
            case self::Double:
                return (double)$value;
            case self::Float:
                return (float)$value;
            case self::DateTime:
                return date_create_from_format(Configuration::$dateFormat, $value);
            default:
                return $value;
        }
    }
}
