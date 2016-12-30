<?php

class IndexController extends BaseController {

    public function indexAction() {

    	//$data = $this->db->query("SELECT * FROM `ofo_password` LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);

    	//$this->log_info(json_encode($data));

    	//$rsa = new RSA();

    	//$this->log_info($rsa->encryptToRSA("陈龙科",$this->config->RSA_PUBLIC_KEY));

    	$this->view->setVar('region',json_decode($this->getLocationAction(),true));
    	$this->view->setVar("title","登录");    	
    	$this->view->setVar("index",$this->getip());
    	$this->view->render('index','index');
    }

    public function NotFoundAction(){
    	die("找不到页面");
    }
}