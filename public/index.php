<?php

ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', ".{$_SERVER['SERVER_NAME']}");
ini_set('session.gc_maxlifetime', '86400');

ini_set( 'display_errors', 'on' );//页面上显示错误
ini_set( 'log_errors', 'on' );//错误输出到日志
ini_set( 'error_log', '../Runtime/php_error.log');//PHP错误日志输出到文件中

use Phalcon\Di\FactoryDefault;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();    
    /**
     * Get config service for use in inline setup below
     */
    $config = include "../app/config/config.php";
    /**
     * Read services
     */
    include APP_PATH . "/config/services.php";
    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
