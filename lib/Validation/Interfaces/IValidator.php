<?php
declare(strict_type=1);


namespace Validation\Interfaces;


interface IValidator
{
    function check($value);
    function getMessage();
}
