<?php
declare(strict_type=1);


namespace Validation\Advice;


use Doctrine\Common\Annotations\AnnotationReader as DocReader;
use Validation\Type\StrongType;
use Validation\Type\Type;

class AnnotationReader
{
    /**
     * @param $class
     * @return array
     * @throws \ReflectionException
     */
    public static function fromClass($class)
    {
        $reader = new DocReader;
        $reflector = new \ReflectionClass($class);
        return $reader->getClassAnnotations($reflector);
    }

    /**
     * @param $class
     * @param $property
     * @return array
     * @throws \ReflectionException
     */
    public static function fromProperty($class, $property)
    {
        $reader = new DocReader;
        $reflector = new \ReflectionProperty($class, $property);
        return $reader->getPropertyAnnotations($reflector);
    }

    /**
     * @param $class
     * @param $method
     * @return array
     * @throws \ReflectionException
     */
    public static function fromMethod($class, $method)
    {
        $reader = new DocReader;
        $reflector = new \ReflectionMethod($class, $method);
        return $reader->getMethodAnnotations($reflector);
    }

    /**
     * @param $class
     * @param $property
     * @deprecated only for PHP VERSION less than 7.4
     * @return Type
     * @throws \ReflectionException
     */
    public static function readStrongType($class, $property){
        $refProperty = new \ReflectionProperty($class, $property);
        if (preg_match('/@var\s+([^\s]+)/', $refProperty->getDocComment(), $matches)) {
            list(, $type) = $matches;
            if ($type=="int"){
                return new Type(StrongType::Int, true);
            }
            elseif($type=="double"){
                return new Type(StrongType::Double, true);
            }
            elseif($type=="float"){
                return new Type(StrongType::Float, true);
            }
            elseif($type=="\DateTime"){
                return new Type(StrongType::DateTime, true);
            }
            elseif($type == "string"){
                return new Type(StrongType::String, true);
            }
            elseif($type == "array"){
                return new Type(StrongType::Array, true);
            }
        }

        return new Type($type|'mixed');
    }
}