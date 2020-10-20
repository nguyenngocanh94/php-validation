<?php
declare(strict_type=1);


namespace Validation\Annotations;


use Doctrine\Common\Annotations\Annotation;
use Validation\Interfaces\IValidator;

/**
 * Class Match
 * @Annotation
 * @Target({"PROPERTY"})
 * @package Validation\Annotations
 */
class Match extends Annotation implements IValidator
{
    public $message;
    public $regex;

    function check($value)
    {
        return preg_match($this->regex, $value)==1;
    }

    function getMessage()
    {
        return $this->message;
    }
}