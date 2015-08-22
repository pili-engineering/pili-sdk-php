<?php
namespace Pili;

define('DEFAULT_API_HOST', 'pili.qiniuapi.com');
define('DEFAULT_API_VERSION', 'v1');
define('SDK_VERSION', '1.4.0');
define('SDK_USER_AGENT', 'pili-sdk-php');

final class Conf 
{
	public $API_HOST    = DEFAULT_API_HOST;
	public $API_VERSION = DEFAULT_API_VERSION;
	public $USE_HTTPS   = false;

    private static $_instance = NULL;

    public static function getInstance()
    {  
        if (!(self::$_instance instanceof self)) {  
            self::$_instance = new self();  
        }  
        return self::$_instance;  
    }

    public function __get($property)
    {
        if (property_exists(self::getInstance(), $property)) 
        {
            return self::getInstance()->$property;
        }
        else
        {
            return NULL;
        }
    }

    public function __set($property, $value)
    {
        if (property_exists(self::getInstance(), $property)) 
        {
            self::getInstance()->$property = $value;
        }
        return self::getInstance();
    }
}

?>