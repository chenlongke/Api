# demo
### 测试练手项目
### Test Exercises demo
# 欢迎研究使用clk的phalcon框架

----
注：本框架为clk自行研究使用，不用做商业用处，仅仅用来学习。
##什么是phalcon
Phalcon 是开源、全功能栈、使用 C 扩展编写、针对高性能优化的 PHP 5 框架。 开发者不需要学习和使用 C 语言的功能， 因为所有的功能都以 PHP 类的方式暴露出来，可以直接使用。 Phalcon 也是松耦合的，可以根据项目的需要任意使用其他对象。

Phalcon 不只是为了卓越的性能, 我们的目标是让它更加健壮，拥有更加丰富的功能以及更加简单易于使用！
``` php
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

```
2017年2月10日 09:33:17