<?php
declare(strict_type=1);

namespace Validation\Advice;


use Doctrine\Common\Annotations\Annotation;
use ReflectionProperty;
use Validation\Exceptions\ValidatedClassNeedNonConstructorException;
use Validation\Exceptions\ValidationException;
use Validation\Type\Type;

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
        if ($strongType->isCustomClass()){
            $this->annotationContainers = ValidationAdvice::getAnnotationContainers($this->strongType->getType());
        }
    }

    /**
     * @param object $instance
     * @param string $fieldName
     * @param $valueOfProperty
     * @throws ValidationException
     * @throws \ReflectionException
     * @throws ValidatedClassNeedNonConstructorException
     */
    public function validate(object $instance, string $fieldName, $valueOfProperty){
        if(isset($this->annotationContainers)){
            // use stack and put $fieldname to stacktrace
            array_push($stackTrace, $fieldName);
            // get field instance
            $fieldInstance = $this->strongType->getInstanceOfType();
            if ($this->strongType->isArray()){
                // get field instance
                $fieldArrayInstance = [];
                foreach ($valueOfProperty as $item){
                    // loop annotation container inside.
                    foreach ($this->annotationContainers as $annotationContainer){
                        $childFieldName = $annotationContainer->fieldName;
                        $annotationContainer->validate($fieldInstance, $childFieldName, $item[$childFieldName]);
                    }
                    $fieldArrayInstance[] = $fieldInstance;
                }
                $instance->$fieldName = $fieldArrayInstance;
            }else{
                try {
                    foreach ($this->annotationContainers as $annotationContainer){
                        $childFieldName = $annotationContainer->fieldName;
                        $annotationContainer->validate($fieldInstance, $childFieldName, $valueOfProperty[$childFieldName]);
                        $fieldInstance->$childFieldName = $valueOfProperty[$childFieldName];
                    }
                    $instance->$fieldName = $fieldInstance;
                }catch (ValidationException $exception){
                    throw new ValidationException();
                }

            }
        }else{
            foreach ($this->annotations as $annotation){
                // validate
                if (!$annotation->check($valueOfProperty)){
                    $message = $fieldName.' '.$annotation->getMessage();
                    throw new ValidationException($message);
                }
                $instance->$fieldName = $valueOfProperty;
            }
        }
    }
}
