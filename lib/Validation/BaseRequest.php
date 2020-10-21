<?php
declare(strict_type=1);


namespace Validation;


use Validation\Advice\ValidationAdvice;

class BaseRequest
{
    /**
     * @var string[]
     */
    public array $__fieldList;
    public function __construct()
    {
        $validate = new ValidationAdvice();
        $validate->advice($this);
    }

    public function toArray() : array{
        $res = [];
        foreach ($this->__fieldList as $property){
            $res[$property]=$this->$property;
        }

        return $res;
    }
}