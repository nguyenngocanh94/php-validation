<?php
declare(strict_type=1);


namespace Validation\Annotations;


use Doctrine\Common\Annotations\Annotation;
use Validation\Interfaces\IValidator;

/**
 * Class NotNullAnnotation
 * @Annotation
 * @Target({"PROPERTY"})
 */
class NotNull extends Annotation implements IValidator
{
    public $message = " is not null";

    public function check($value){
        return $value!=null || $value != '';
    }

    public function getMessage(){
        return $this->message;
    }
}