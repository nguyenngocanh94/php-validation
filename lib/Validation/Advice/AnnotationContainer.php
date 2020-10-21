<?php
declare(strict_type=1);


namespace Validation\Advice;


use Doctrine\Common\Annotations\Annotation;
use ReflectionProperty;
use Validation\Exceptions\ValidationException;
use Validation\Type\Type;
use Validation\Type\TypeReader;

/**
 * Class AnnotationContainer of a field
 * @package Validation\Advice
 */
class AnnotationContainer
{
    public ReflectionProperty $property;

    public string $fieldName;
    /**
     * @var Annotation[]
     */
    public array $annotations;

    public Type $strongType;

    /**
     * @var AnnotationContainer[]
     */
    public array $annotationContainers;

    /**
     * AnnotationContainer constructor.
     * @param string $fieldName
     * @param array $annotations
     * @param Type $strongType
     * @param ReflectionProperty $property
     * @throws \ReflectionException
     */
    public function __construct(string $fieldName, array $annotations, Type $strongType, ReflectionProperty $property)
    {
        $this->fieldName = $fieldName;
        $this->annotations = $annotations;
        $this->strongType = $strongType;
        $this->property = $property;
        $this->getChildAnnotationContainer();
    }

    /**
     * validate the field name
     * @param string $fieldName
     * @param $valueOfProperty
     * @throws ValidationException
     */
    public function validate(string $fieldName, $valueOfProperty){
        foreach ($this->annotations as $annotation){
            // validate
            if (!$annotation->check($valueOfProperty)){
                $message = $fieldName.' '.$annotation->getMessage();
                throw new ValidationException($message);
            }
        }
        foreach ($this->annotationContainers as $annotationContainer){
            $annotationContainer->validate($annotationContainer->fieldName, $valueOfProperty[$annotationContainer->fieldName]);
        }
    }


    /**
     * @throws \ReflectionException
     */
    private function getChildAnnotationContainer(){
        $this->annotationContainers = ValidationAdvice::getAnnotationContainers($this->getFullNameSpaceType());
    }

    /**
     * @return mixed
     */
    public function getFullNameSpaceType(){
        $parser = new TypeReader();
        [$type, ] = $parser->readPropertyType($this->property);

        return $type;
    }

}
