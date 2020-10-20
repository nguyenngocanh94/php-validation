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
class MinLength extends Annotation implements IValidator
{
    /**
     * @var int
     */
    public $min;

    function check($value)
    {
        return strlen($value) >= $this->min;
    }

    function getMessage()
    {
        return " length must be larger than ".$this->min;
    }
}
