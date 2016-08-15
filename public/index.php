<?php
	@header("Content-Type: text/html; charset=UTF-8");
	ini_set("session.cookie_domain",".p.p");
	define('REALPATH',realpath("../"));
    define('BASEPATH',__DIR__);
    /* 页面上不显示错误 */
    ini_set( 'display_errors', 'on' );
    /*错误输出到日志 */
    ini_set( 'log_errors', 'on' );
    /* PHP错误日志输出到文件中 */
    ini_set( 'error_log', REALPATH.'/app/runtime/php_error.log');
	try {
		$config = include "../app/config/config.php";
		include "../app/config/loader.php";	
	    $di = new Phalcon\Di\FactoryDefault();
	    include "../app/config/service.php";
	    $application = new Phalcon\Mvc\Application($di);
	    echo $application->handle()->getContent();
	} catch (\Exception $e) {
	    echo "Exception: ", $e->getMessage();
	}