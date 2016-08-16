<?php
	abstract class BaseController extends Phalcon\Mvc\Controller{
		protected $log;
		protected $_isJsonpResponse = false;
    	protected $_isJsonResponse = false;
    	
		public function initialize(){
			$this->log=$this->logger;
		}


		public function setJsonResponse() {
	        $this->view->disable();
	        $this->_isJsonpResponse = true;
	        $this->response->setContentType('application/json', 'UTF-8');
	    }

	    //设置JSONP返回格式
	    public function setJsonpResponse() {
	        $this->view->disable();
	        $this->_isJsonpResponse = true;
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
			$this->logger->info($param);
		}
		public function test($method,$apiParams){
			$api = new Api($this->config->appkey,$this->config->secretKey);
			return $api->request_api($method,$apiParams);
		}
	}