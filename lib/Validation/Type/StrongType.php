<?php
declare(strict_type=1);


namespace Validation\Type;

use ReflectionProperty;
use Validation\Configuration;

/**
 * Class StrongType
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
    const Mixed = 'mixed';

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

    /**
     * get the strong type of a property
     * @param ReflectionProperty $property
     * @return Type
     */
    public static function getStrongType(ReflectionProperty $property) : Type{
        if (Configuration::$greaterPHP74Version){
            // auto convert to strong type.
            $type = $property->getType();
            if ($type=='array'){
                $typeReader = new TypeReader();
                $fullNameType = $typeReader->getFullNameSpaceType($property);
                if ($fullNameType==null){
                    return new Type(self::Mixed, false, true, true);
                }
                return new Type($fullNameType,false, true, true);
            }
            return new Type($type->getName(), $type->isBuiltin(), false, $type->allowsNull());
        }
        $typeReader = new TypeReader();
        [$type, $isBuildIn, $isArray] = $typeReader->readPropertyType($property);
        return new Type($type,$isBuildIn, $isArray, true);
    }
}
