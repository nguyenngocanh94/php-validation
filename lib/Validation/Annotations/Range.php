<?php
declare(strict_type=1);


namespace Validation\Annotations;



use Doctrine\Common\Annotations\Annotation;
use Validation\Interfaces\IValidator;

/**
 * Class RangeAnnotation
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Range extends Annotation implements IValidator
{
    public $message;
    /**
     * @var int
     */
    public $min;
    /**
     * @var int
     */
    public $max;

    function check($value)
    {
        if (isset($this->min) && isset($this->max)){
            return $this->min <= $value && $value <= $this->max;
        }
        elseif(isset($this->min)){
            return $this->min <= $value;
        }
        elseif(isset($this->max)){
            return $value <= $this->max;
        }

        return true;
    }

    function getMessage()
    {
        if (isset($this->message)){
            return $this->message;
        }

        return " value between ".$this->min." and ".$this->max;
    }
}
