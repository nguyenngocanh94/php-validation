<?php
declare(strict_type=1);


namespace Validation\Advice;


use ReflectionClass;
use ReflectionProperty;
use Validation\BaseRequest;
use Validation\Common\StringUtils;
use Validation\Configuration;
use Validation\Exceptions\ValidationException;
use Validation\Http\Request;
use Validation\Type\Parser;
use Validation\Type\StrongType;
use Validation\Type\Type;
use Validation\Type\TypeReader;

class ValidationAdvice
{

    /**
     * @param BaseRequest $instance
     * @throws ValidationException
     * @throws \JsonMapper_Exception
     * @throws \ReflectionException
     * @throws \Validation\Exceptions\ValidatedClassNeedNonConstructorException
     */
    public function advice(BaseRequest $instance){
        $validateStack = [];
        $annotationContainers = $this->getAnnotationContainers($instance);
        $request = Request::init();
        foreach ($annotationContainers as $annotationContainer){
            $fieldName = $annotationContainer->fieldName;
            $strongType = $annotationContainer->strongType;
            // convention must be camel <=> snake
            $valueOfProperty = $request->get(StringUtils::camelToSnake($fieldName));
            // assign value
            if (Configuration::$greaterPHP74Version){
                // auto convert to strong type.
                if ($strongType->isBuildIn()){
                    // build in is string, int, float, double, array
                    $valueOfProperty = StrongType::make($strongType, $valueOfProperty);
                }
            }else{
                // auto convert to strong type.
                $valueOfProperty = StrongType::make($strongType, $valueOfProperty);
            }
            try {
                $annotationContainer->validate($fieldName, $valueOfProperty);
            }catch (ValidationException $exception){
                $validateStack[$fieldName][] = $exception->getMessage();
            }
            if (count($validateStack[$fieldName])>0){
                if ($strongType->isCustomClass()){
                    $instance->$fieldName = Configuration::$jsonMapper->map($valueOfProperty, $strongType->getInstanceOfType());
                }elseif ($strongType->getType()=='array'){

                    $instance->$fieldName = Configuration::$jsonMapper->mapArray($valueOfProperty, [], );
                }
                else{
                    $instance->$fieldName = $valueOfProperty;
                }
            }
        }
        if (count($validateStack) > 0){
            throw new ValidationException(json_encode($validateStack));
        }
    }

    /**
     * @param $instance BaseRequest|string
     * @return AnnotationContainer[]
     * @throws \ReflectionException
     */
    public function getAnnotationContainers($instance){
        $reflect = new ReflectionClass($instance);
        $properties = $reflect->getProperties();
        $className = get_class($instance);
        $instance->fieldList = [];
        $validationContainers = array();
        foreach ($properties as $property) {
            if ($property->class == $className){
                $propertyName = $property->getName();
                $annotations = AnnotationReader::fromProperty($className, $propertyName);
                if (Configuration::$greaterPHP74Version){
                    // auto convert to strong type.
                    $type = $property->getType();
                    if ($type == 'array'){

                    }
                    $strongType = new Type($type->getName(), $type->isBuiltin(), $type->allowsNull());
                }else{
                    $strongType = AnnotationReader::readStrongType($className, $propertyName);
                }
                $annotationContainer = [];
                if ($strongType->isCustomClass()){
                    $annotationContainer = $this->getAnnotationContainers($strongType->getType());
                }

                $validationContainer = new AnnotationContainer($propertyName, $annotations, $strongType, $annotationContainer);
                $validationContainers[] = $validationContainer;
                $instance->fieldList[] = $propertyName;
            }
        }

        return $validationContainers;
    }


    /**
     * @param ReflectionProperty $property
     * @return mixed
     */
    private function getDetailClassWithArrayCase(ReflectionProperty $property){
        $parser = new TypeReader();
        [$type, ] = $parser->readPropertyType($property);

        return $type;
    }
}
