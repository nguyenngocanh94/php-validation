<?php
declare(strict_type=1);


namespace Validation\Annotations;


use Doctrine\Common\Annotations\Annotation;
use Validation\Interfaces\IValidator;

/**
 * Class EmailAnnotation
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Email extends Annotation implements IValidator
{

    function check($value)
    {
        $find1 = strpos($value, '@');
        $find2 = strpos($value, '.');
        return ($find1 !== false && $find2 !== false && $find2 > $find1);
    }

    function getMessage()
    {
        return "is not a valid email";
    }
}
