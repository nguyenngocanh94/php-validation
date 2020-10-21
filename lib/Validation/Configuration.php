<?php
declare(strict_type=1);


namespace Validation;



class Configuration
{
    public static string $dateFormat;
    public static bool $greaterPHP74Version;

    /**
     * setting array
     * @param array $configuration
     */
    static function setting(array $configuration){
        self::$dateFormat = $configuration['date_format'];
        self::$greaterPHP74Version = version_compare(PHP_VERSION, '7.4.0', '>=');
    }
}