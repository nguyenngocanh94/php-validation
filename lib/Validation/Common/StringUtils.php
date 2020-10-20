<?php
declare(strict_type=1);


namespace Validation\Common;


/**
 * Class StringUtils
 * collection of string function
 * upgrade to PHP 8 to use build-in function
 * https://wiki.php.net/rfc/add_str_starts_with_and_ends_with_functions
 */
class StringUtils
{
    public static function contain($string, $needed){
        return strpos($string, $needed) !== false;
    }

    public static function endWith($haystack, $needle){
        $length = strlen( $needle );
        if( !$length ) {
            return true;
        }

        return substr( $haystack, -$length ) === $needle;
    }

    public static function startWith($haystack, $needle){
        $length = strlen($needle);
        return substr($haystack, 0, $length) === $needle;
    }

    public static function upCaseAtFirst($string){
        return ucfirst($string);
    }

    public static function subString($string , $cutString){
        return substr($string, 0, strlen($string)-strlen($cutString));
    }

    /**
     * Translates a camel case string into a string with
     * underscores (e.g. firstName -> first_name)
     *
     * @param string $camel String in camel case format
     * @return string $str Translated into underscore format
     */
    public static function camelToSnake(string $camel){
        $camel[0] = strtolower($camel[0]);
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');
        return preg_replace_callback('/([A-Z])/', $func, $camel);
    }

    /**
     * Translates a string with underscores
     * into camel case (e.g. first_name -> firstName)
     *
     * @param string $snake String in underscore format
     * @param bool $capitalise_first_char If true, capitalise the first char in $str
     * @return string $str translated into camel caps
     */
    public static function snakeToCamel(string $snake,bool $capitalise_first_char = false){
        if($capitalise_first_char) {
            $str[0] = strtoupper($snake[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }

    /**
     *  
     * @param $string
     * @return string
     */
    public static function trimAll($string){
        $string = str_replace(' ','',$string);
        return trim($string);
    }
}