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
		exit(json_encode([
			'code'=>-1,
			'message'=>'test'
		]));
	}
}