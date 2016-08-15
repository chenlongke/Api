<?php
$loader = new \Phalcon\Loader();
$loader->registerDirs(
	array(
		$config->application->controllersDir,
		$config->application->componentsDir,
		 $config->application->modelsDir, 
		$config->application->viewsDir,
		/* $config->application->pluginsDir, */
		$config->application->libraryDir,
		$config->application->cacheDir,
		$config->application->logDir
	)
);
$loader->registerNamespaces(array(
	"library"=>$config->application->libraryDir
));
$loader->register();