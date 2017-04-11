<?php

class IndexController extends BaseController
{
    public function indexAction() {
    	
    }

    public function TestAction()
    {
    	$this->view->setVar("data",[]);
    }

    public function NotFoundAction(){
    	die("找不到页面");
    }
}