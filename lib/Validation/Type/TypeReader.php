<?php
declare(strict_type=1);


namespace Validation\Type;


use ReflectionClass;
use ReflectionProperty;
use Validation\Common\StringUtils;

class TypeReader
{
    private Parser $parser;

    public const PRIMITIVE_TYPES = [
        'bool' => 'bool',
        'boolean' => 'bool',
        'string' => 'string',
        'int' => 'int',
        'integer' => 'int',
        'float' => 'float',
        'double' => 'float',
        'array' => 'array',
        'object' => 'object',
        'callable' => 'callable',
        'resource' => 'resource',
        'mixed' => 'mixed',
        'iterable' => 'iterable',
    ];


    public function __construct()
    {
        $this->parser = new Parser();
    }

    /**
     * @param ReflectionProperty $property
     * @return array
     */
    public function readPropertyType(ReflectionProperty $property): array
    {
        $isArray = false;
        // Get the content of the @var annotation
        $docs = $property->getDocComment();
        if (!$docs) {
            return array(null, $isArray);
        }
        if (preg_match('/@var\s+([^\s]+)/', $docs, $matches)) {
            [, $type] = $matches;
        } else {
            return array(null, $isArray);
        }

        // return primitive, because we dont need it.
        if (isset(self::PRIMITIVE_TYPES[$type])) {
            return array(self::PRIMITIVE_TYPES[$type], $isArray);
        }

        // this must be an array
        if (StringUtils::endWith($type, '[]')) {
            $isArray = true;
        }
        // If the class name is not fully qualified (i.e. doesn't start with a \)
        if ($type[0] !== '\\') {
            $class = $property->getDeclaringClass();
            return array($this->tryGetFullNameSpace($type, $class), $isArray);
        }

        $type = is_string($type) ? ltrim($type, '\\') : null;

        return array($type, $isArray);
    }


    /**
     * get full name space.
     * @param string $type
     * @param ReflectionClass $class
     * @return string|null
     */
    private function tryGetFullNameSpace(string $type, ReflectionClass $class): ?string
    {
        $fileName = $class->getFileName();
        [,$namespace,$useStatements] = $this->parser->extractPhpClasses($fileName);
        foreach ($useStatements as $useStatement){
            $items = explode('\\', $useStatement);
            $last = $items[count($items)-1];
            if ($last == $type || strtolower($last) == strtolower($type)){
                return $useStatement;
            }
        }

        return $namespace.'\\'.$type;
    }
}