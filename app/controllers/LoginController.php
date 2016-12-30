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
			'message'=>'test'
		];
	}
}