<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;

use Phalcon\Dispatcher;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

use \Exception as Exception;
use \Redis as Redis;

/**
 * Shared configuration service
 */
$di->setShared('config', function () use($config) {
    return $config;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () use($config) {

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () use($config) {

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.phtml' => function ($view) {
            $config = $this->getConfig();

            $phtml = new VoltEngine($view, $this);

            $phtml->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_',
                'compileAlways'=>true
            ]);

            return $phtml;
        },
        '.volt' => PhpEngine::class
    ]);

    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () use($config) {

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    return new $class($params);    
});


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Flash([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

/*设置日志服务*/
$di->set('logger', function() use ($config)  {
    $logger = new Phalcon\Logger\Adapter\File($config->application->logDir . date('Ymd').'-log.log');
    $formatter = new Phalcon\Logger\Formatter\Line("php %date% [%type%] %message%");
    $formatter->setDateFormat('H:i:s');
    $logger->setFormatter($formatter);
    return $logger;
});

/*设置路由*/
$di->set('router',function() use($config){
    require APP_PATH.'/config/routers.php';
    return $router;
});

$di->setShared("dispatcher",function () {
        // 创建一个事件管理
        $eventsManager = new EventsManager();

        // 附上一个侦听者
        $eventsManager->attach(
            "dispatch:beforeException",
            function (Event $event, $dispatcher, Exception $exception) {
                // 处理404异常
                if ($exception instanceof DispatchException) {
                    $dispatcher->forward(
                        [
                            "controller" => "Login",
                            "action"     => "NotFound",
                        ]
                    );

                    return false;
                }

                // 代替控制器或者动作不存在时的路径
                switch ($exception->getCode()) {
                    case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                    case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                        $dispatcher->forward(
                            [
                                "controller" => "Index",
                                "action"     => "Index",
                            ]
                        );
                        return false;
                }
            }
        );

        $dispatcher = new MvcDispatcher();

        // 将EventsManager绑定到调度器
        $dispatcher->setEventsManager($eventsManager);

        return $dispatcher;
    }
);

$di->set('redis' , function() use($config){
    $redis = new Redis();
    $redis->connect($config->redis->host,$config->redis->port);
    return $redis;
});