<?php
declare(strict_type=1);


namespace Validation;


use Validation\Advice\ValidationAdvice;

class BaseRequest
{
    /**
     * @var string[]
     */
    public $fieldList;
    public function __construct()
    {
        $validate = new ValidationAdvice();
        $validate->advice($this);
    }

    public function toArray(){
        $res = [];
        foreach ($this->fieldList as $property){
            $res[$property]=$this->$property;
        }

        return $res;
    }
}