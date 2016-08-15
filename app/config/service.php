<?php
	use Phalcon\Mvc\View;
	use Phalcon\Mvc\Url as UrlResolver;
	use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
	use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
	use Phalcon\Logger\Formatter\Line as LineFormatter;
	use Phalcon\Logger\Adapter\File as Logger;
	use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
	use Phalcon\Session\Adapter\Files as SessionAdapter;

	$di->set('view', function () use ($config) {
		$view = new View();
		$view->setViewsDir($config->application->viewsDir);
		$view->registerEngines(array(
			'.volt' => function ($view, $di) use ($config) {
			$volt = new VoltEngine($view, $di);
			$volt->setOptions(array(
				'compiledPath' => $config->application->cacheDir,
				'compiledSeparator' => '_'
			));
			return $volt;
			},
			'.phtml' => 'Phalcon\Mvc\View\Engine\Php'
		));
		return $view;
	}, true);

	$di->set('db', function () use ($config) {
		return new DbAdapter(array(
			'host' => $config->db_host->host,
			'username' => $config->db_host->username,
			'password' => $config->db_host->password,
			'dbname' => $config->db_host->dbname,
			'charset' => 'utf8'
		));
	});

	$di->set('modelsMetadata', function () {
		return new MetaDataAdapter();
	});

	$di->set('session', function () {
		$session = new SessionAdapter();
		$session->start();
		return $session;
	});

	$di->set('logger', function() use ($config)  {
		$logger = new Logger($config->application->logDir .  'logger.log');
		$formatter = new LineFormatter("pepole %date% [%type%] %message%");
		$formatter->setDateFormat('H:i:s');
		$logger->setFormatter($formatter);
		return $logger;
	});

	$di->set('url', function () use ($config) {
		$url = new UrlResolver();
		$url->setBaseUri($config->application->baseUri);
		return $url;
	}, true);
	/**
	 * 设置配置文件
	 */
	$di->set('config', function() use ($config){
		return $config;
	});