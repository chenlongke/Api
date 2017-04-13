<?php

class IndexController extends BaseController
{
    public function indexAction() 
    {
    	$this->view->pick("Index/index");
    }

    public function TestAction()
    {
    	$this->view->setVar("data",[]);
    }   
}