<?php
declare(strict_type=1);


namespace Validation\Http;


class Request
{
    private array $_request;
    // for case upload file. but
    //TODO try in next version.
    private array $_post;

    private array $_cookie;
    static function init(){
        return new Request();
    }

    private function __construct(){
        if ($_SERVER['HTTP_CONTENT_TYPE'] == 'application/json'){
            $this->_request = json_decode( file_get_contents('php://input'), 1)??[];
        }else{
            $this->_request = $_REQUEST;
        }
        $this->_cookie = $_COOKIE;
    }

    /**
     * get a
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key){
        if (isset($this->_request[$key]))
            return $this->_request[$key];
        elseif (isset($this->_cookie[$key]))
            return $this->_cookie[$key];
        else
            return null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function isset(string $key) : bool{
        if (isset($this->_request[$key]))
            return true;
        elseif (isset($this->_cookie[$key]))
            return true;
        else
            return false;
    }
}