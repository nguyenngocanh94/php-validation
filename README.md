# PHP validation
a validation form like other language c#, java with annotations
in some other language like c# and java we have `Attribute` a.k.a `Annotation`
```java
import javax.validation.constraints.AssertTrue;
import javax.validation.constraints.Max;
import javax.validation.constraints.Min;
import javax.validation.constraints.NotNull;
import javax.validation.constraints.Size;
import javax.validation.constraints.Email;
 
public class User {
 
    @NotNull(message = "Name cannot be null")
    private String name;
 
    @AssertTrue
    private boolean working;
 
    @Size(min = 10, max = 200, message 
      = "About Me must be between 10 and 200 characters")
    private String aboutMe;
 
    @Min(value = 18, message = "Age should not be less than 18")
    @Max(value = 150, message = "Age should not be greater than 150")
    private int age;
 
    @Email(message = "Email should be valid")
    private String email;
```
And it is very elegant, short and beautiful. i do a version of it on PHP.
### Prerequisites
Your web application should use a DI library to manage all object. it will native support without adding any code, 
just define the setting datetime for it.

If you don't use DI library, at the block code that resolve controller instance. you must resolve it like below.
- i assume your controller is `HelloController`
```php
class HelloController{
    function sayHi(HiRequest $hiRequest){
        
    }
}
```
- your `HiRequest`
```php
class HiRequest extends \Validation\BaseRequest{
    /**
    * @\Validation\Annotations\NotNull
    * @\Validation\Annotations\MaxLength(max=256)
    */
    public string $name;
    ...
}
```
- let say it is your code that resolve the controller and method that handle the request `hello/sayHi`.
```php
$controller = new HelloController();
$controller->sayHi(new HiRequest);
```
- Add the function that get parameters of `sayHi` function:
```php
    public function getParameterOfActionMethod($className, $methodName){
        $class = new ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $parameters = [];
        $actionParameters= $method->getParameters();
        foreach ($actionParameters as $parameter){
            $parameterType = (string)$parameter->getType();
            if (class_exists($parameterType)){
                $parameterInstance = new $parameterType();
                array_push($parameters, $parameterInstance);
            }else{
                // must be scalar type
                $value = $_REQUEST($parameter->name);
                if ($value==null){
                    $value = $_REQUEST(StringUtils::camelToSnake($parameter->name));
                }
                array_push($parameters, $value);
            }
        }
        return $parameters;
    }
// at controller handle code:
$controller = new HelloController();
$controller->sayHi(...$this->getParameterOfActionMethod(HelloController::class, "sayHi"));
```
- Enjoy it!!
```php
class HelloController{
    function sayHi(HiRequest $hiRequest){
        $hiRequest->name ... // validated, and do your logic.
    }
}
```
## USAGE
### step 1: setting the datetime format.
```php
 \Validation\Configuration::setting([
            'date_format'=>'Y-m-d',
        ]);
```
### step 2: define form class.
```php
class HiRequest extends \Validation\BaseRequest{
    /**
    * @\Validation\Annotations\NotNull
    * @\Validation\Annotations\MaxLength(max=256)
    */
    public string $name;

    /**
    * @\Validation\Annotations\Range(min=18, max=50)
    */
    public int $age;
}
```
That is OK!!
### We support some validation:
- NotNull
- Min
- Max
- Email
- Match
- MinLength
- MaxLength
- Range
- Length
You can create your own constraint annotation by implement `IValidator` interface
```php
Class YourCustomConstraint extends \Doctrine\Common\Annotations\Annotation implements \Validation\Interfaces\IValidator {     
    
    public function check($value) : bool{
        // predicate code
        return true;
    }
    
    public function getMessage() : string{
       return "what message you want!!!";
    }
}
```
* [How to contribute - any contribution is welcome!!]()