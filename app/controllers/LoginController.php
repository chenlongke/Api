<?php
/**
* 
*/
class LoginController extends BaseController
{
	function indexAction(){
		$this->view->render('login','index');
	}

	function checkAccountAction($account='',$password=''){
		$this->setJsonpResponse();
		return [
			'code'=>-1,
			'message'=>'项目测试中。本项目<a target="_blank" href="https://github.com/clk528/demo">github仓库</a>'
		];
	}
}