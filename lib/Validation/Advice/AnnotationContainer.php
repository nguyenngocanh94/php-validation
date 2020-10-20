<?php
declare(strict_type=1);


namespace Validation\Advice;


use Doctrine\Common\Annotations\Annotation;
use Validation\Exceptions\ValidationException;
use Validation\Type\Type;

/**
 * Class AnnotationContainer of a field
 * @package Validation\Advice
 */
class AnnotationContainer
{
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

    public function __construct(string $fieldName, array $annotations, Type $strongType, $annotationContainers = [])
    {
        $this->fieldName = $fieldName;
        $this->annotations = $annotations;
        $this->strongType = $strongType;
        $this->annotationContainers = $annotationContainers;
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
}
