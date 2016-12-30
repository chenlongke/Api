<?php

class IndexController extends BaseController {

    public function indexAction() {
    	$this->view->setVar('region',json_decode($this->getLocationAction(),true));
    	$this->view->setVar("title","登录");    	
    	$this->view->setVar("index",$this->getip());
    	$this->view->render('index','index');
    }

    public function NotFoundAction(){
    	echo "not found";die();
    }
}