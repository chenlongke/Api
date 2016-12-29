<?php
/**
* 
*/
class LoginController extends BaseController
{
	function indexAction(){
		$this->view->render('login','index');
	}	
}