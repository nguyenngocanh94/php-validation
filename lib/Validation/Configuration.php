<?php
declare(strict_type=1);


namespace Validation;


use JsonMapper;

class Configuration
{
    public static string $dateFormat;
    public static bool $greaterPHP74Version;
    public static JsonMapper $jsonMapper;

    /**
     * setting array
     * @param array $configuration
     */
    static function setting(array $configuration){
        self::$dateFormat = $configuration['date_format'];
        self::$greaterPHP74Version = version_compare(PHP_VERSION, '7.4.0', '>=');
        if (isset($configuration['json_mapper'])){
            self::$jsonMapper =$configuration['json_mapper'];
        }else{
            self::$jsonMapper = new JsonMapper();
            self::$jsonMapper->bStrictNullTypes = false;
        }

    }
}