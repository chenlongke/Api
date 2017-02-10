<?php

ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', ".{$_SERVER['SERVER_NAME']}");
ini_set('session.gc_maxlifetime', '86400');

/**
 * An error is displayed on the page
 */
ini_set( 'display_errors', 'on' );
/**
 * The error is output to the log
 */
ini_set( 'log_errors', 'on' );

/**
 * The error log is output to the php_error.log file
 */
ini_set( 'error_log', '../Runtime/php_error.log');

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
