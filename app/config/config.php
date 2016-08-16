<?php
return new \Phalcon\Config(array(
	"db_host"=>array(
		'host' => "127.0.0.1",
		'username' => "root",
		'password' => "",
		'dbname' => "vacn_db",
	),
	"application" => array(
		"controllersDir" => "../app/controllers/",
		"componentsDir" =>  "../app/components/",
		 "modelsDir" => "../app/models/", 
		"viewsDir" => "../app/views/",
		/* "pluginsDir" => "../app/plugins/", */
		"libraryDir" => "../app/library/",
		"cacheDir" => "../app/cache/",
		"logDir" =>  "../app/runtime/"
	),
	"appkey"=>"201608131611",
	"secretKey"=>"jsdjfsd23sd44sd6frewsd6f44we61s6",
	"session"=>"dd1am0uu5q60kkqgbzj5gi0jzzgq0sxhszkzthvws875hary5wmvgpmz9mag",
	"gatewayUrl"=>"http://api.odamiao.com/rest",
	"sdkVersion"=>"clk-test-0528",
	"v"=>"0813"
));