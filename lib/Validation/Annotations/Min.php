<?php
declare(strict_type=1);


namespace Validation\Annotations;


use Doctrine\Common\Annotations\Annotation;
use Validation\Interfaces\IValidator;

/**
 * Class MinAnnotation
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Min extends Annotation implements IValidator
{

    /**
     * @var int
     */
    public $min;

    function check($value)
    {
        return $value > $this->min;
    }

    function getMessage()
    {
        return " must be larger than ".$this->min;
    }
}
