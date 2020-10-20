<?php
declare(strict_type=1);


namespace Validation\Annotations;


use Doctrine\Common\Annotations\Annotation;
use Validation\Interfaces\IValidator;

/**
 * Class LengthAnnotation
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Length extends Annotation implements IValidator
{
    public $message;
    public $min = 0;
    public $max = 256;

    function check($value)
    {
        $length = strlen($value);
        if ($length>$this->min && $length <= $this->max){
            return true;
        }

        return false;
    }

    function getMessage()
    {
        if (isset($this->message)){
            return $this->message;
        }

        return " length between ".$this->min." and ".$this->max;
    }
}
