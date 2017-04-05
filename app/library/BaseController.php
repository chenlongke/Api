<?php

use Phalcon\Mvc\Controller;

class BaseController extends Controller{
	protected $log;

	protected $_isJsonpResponse = false;
    protected $_isJsonResponse = false;


	public function initialize(){
		if(!$this->session->get("userAuth")){			
	        $this->response->redirect( '/login' );
		}
		$this->log = $this->di->get('logger');
	}

	//设置JSONP返回格式
    public function setJsonpResponse() {
        $this->view->disable();
        isset($_REQUEST["callback"]) ? $this->_isJsonpResponse = true : $this->_isJsonResponse = true;
    }

    public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher) {
        $data = $dispatcher->getReturnedValue();
        if ($this->_isJsonpResponse) {
            $jsonp = $_REQUEST["callback"];
            $this->response->setContent($jsonp . "(" . json_encode($data) . ")");
            return $this->response->send();
        }else if ($this->_isJsonResponse) {
            $this->response->setJsonContent($data);
            return $this->response->send();
        }
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
		$ip=="127.0.0.1" ? $ip="27.115.94.242" : '';		
		return NetworkUtils::http("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip={$ip}",null);
	}
}
