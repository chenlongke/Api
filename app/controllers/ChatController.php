<?php
	
class ChatController  extends BaseController
{
	function indexAction(){
		//$this->view->();
	}
	
	function bindRequestAction(){
		$this->log_info("我是绑卡通知回调||".json_encode($_REQUEST));		
		die('SUCCESS');
	}
	
	function zhimaAction()
	{
		$this->log_info("我是芝麻回调||".json_encode($_REQUEST));
		die('SUCCESS');
	}	
}
