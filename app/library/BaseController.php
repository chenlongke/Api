<?php

use Phalcon\Mvc\Controller;

class BaseController extends Controller{
	protected $log;
	public function initialize(){
		$this->log = $this->di->get('logger');
	}

	public function log_info($param) {
		$this->log->info($param);
	}

	public function getip(){
		if (getenv("HTTP_CLIENT_IP")){
			$ip = getenv("HTTP_CLIENT_IP");
		}else if(getenv("HTTP_X_FORWARDED_FOR")){
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}else if(getenv("REMOTE_ADDR")){
			$ip = getenv("REMOTE_ADDR");
		}else {
			$ip = "Unknow";
		}
		return $ip;
	}

	public function getLocationAction(){		
		$ip = $this->getip();
		if($ip=="127.0.0.1"){
			$ip="27.115.94.242";
		}		
		return NetworkUtils::http("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip={$ip}",null);
	}
}
