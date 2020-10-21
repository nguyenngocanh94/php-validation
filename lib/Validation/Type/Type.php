<?php
declare(strict_type=1);


namespace Validation\Type;


use ReflectionClass;
use Validation\Exceptions\ValidatedClassNeedNonConstructorException;

class Type
{
    protected string $type;
    protected bool $nullable;
    protected bool $buildIn;
    protected bool $isArray;
    /**
     * @var mixed
     */
    protected $default = null;


    function __construct(string $type, bool $buildIn = false, bool $isArray = false, bool $nullable = false){
        $this->type = $type;
        $this->nullable = $nullable;
        $this->buildIn = $buildIn;
        $this->isArray = $isArray;
        $this->setDefault();
    }


    protected function setDefault(){
        if ($this->buildIn && !$this->nullable){
            if ($this->type == 'int' || $this->type == 'float' || $this->type == 'double'){
                $this->default = 0;
                return;
            }
            if ($this->type == 'bool' || $this->type == 'boolean'){
                $this->default = false;
                return;
            }
            if ($this->type == 'string'){
                $this->default = '';
                return;
            }
            if ($this->type=='array'){
                $this->default = [];
                return;
            }
        }
        if ($this->nullable){
            $this->default = null;
        }
    }


    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }


    /**
     * @return bool
     */
    public function isBuildIn(): bool
    {
        return $this->buildIn;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function isArray(): bool{
        return $this->type == 'array';
    }

    /**
     * @return object
     * @throws ValidatedClassNeedNonConstructorException
     * @throws \ReflectionException
     */
    public function getInstanceOfType() : object{
        $clazz = $this->type;
        $reflection = new ReflectionClass($clazz);
        $constructor = $reflection->getConstructor();
        if ($constructor != null && count($constructor->getParameters())>0){
            throw new ValidatedClassNeedNonConstructorException();
        }else{
            return new $clazz();
        }
    }

    public function isCustomClass() : bool{
        if ($this->isBuildIn()){
            return false;
        }
        if ($this->type != 'Datetime'){
            return true;
        }
        return false;
    }
}