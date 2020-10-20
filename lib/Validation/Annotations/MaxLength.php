<?php
declare(strict_type=1);


namespace Validation\Annotations;


use Doctrine\Common\Annotations\Annotation;
use Validation\Interfaces\IValidator;

/**
 * Class MaxLengthAnnotation
 * @Annotation
 * @Target({"PROPERTY"})
 */
class MaxLength extends Annotation implements IValidator
{
    /**
     * @var int
     */
    public $max;

    function check($value)
    {
        return strlen($value)<=$this->max;
    }

    function getMessage()
    {
        return " length must be smaller than ".$this->max;
    }
}