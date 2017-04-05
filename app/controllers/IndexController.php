<?php

class IndexController extends BaseController
{
    public function indexAction() {
    	echo "欢迎来到这里";
    }
    public function NotFoundAction(){
    	die("找不到页面");
    }
}