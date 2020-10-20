<?php
declare(strict_type=1);


namespace Validation\Annotations;


use Doctrine\Common\Annotations\Annotation;
use Validation\Interfaces\IValidator;

/**
 * Class MaxAnnotation
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Max extends Annotation implements IValidator
{
    /**
     * @var int
     */
    public $max;

    function check($value)
    {
        return $value < $this->max;
    }

    function getMessage()
    {
        return " must be smaller than ".$this->max;
    }
}
