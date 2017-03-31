<?php

class IndexController extends Phalcon\Mvc\Controller {

    public function indexAction() {

    	//$data = $this->db->query("SELECT * FROM `ofo_password` LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);

    	//$this->log_info(json_encode($data));

    	//$rsa = new RSA();

    	//this->log_info($rsa->encryptRSA("陈龙科",$this->config->RSA_PUBLIC_KEY));
        //$this->log_info($this->redis->get('clk'));

    	$this->view->region = json_decode($this->getLocationAction(),true);
    	$this->view->title = "登录";
    	$this->view->index = $this->getip();
        $this->view->pick("index/login");
    }

    private function getip(){
        if (getenv("HTTP_CLIENT_IP")){
            $ip = getenv("HTTP_CLIENT_IP");
        }else if(getenv("HTTP_X_FORWARDED_FOR")){
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        }else if(getenv("REMOTE_ADDR")){
            $ip = getenv("REMOTE_ADDR");
        }else {
            $ip = "Unknow";
        }
        return $ip == "127.0.0.1" ? "27.115.94.242" : $ip;
    }

    private function getLocationAction(){
        $ip = $this->getip();
        $ip == "127.0.0.1" ? $ip = "27.115.94.242" : '';        
        return NetworkUtils::http("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip={$ip}",null);
    }
    public function NotFoundAction(){
    	die("找不到页面");
    }
}