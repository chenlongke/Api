<?php
/**
* 
*/
use Phalcon\Mvc\Model\Resultset;
class LoginController extends BaseController
{
	function indexAction(){
		$this->view->render('login','index');
	}

	function checkAccountAction(){
		$this->setJsonpResponse();
		$account = isset($_POST['account']) ? $_POST['account']  : '';
		$password = isset($_POST['password']) ? $_POST['password']  : '';
		if(empty($account) || empty($password)){
			return [
				'code'=>-1,
				'message'=>'用户名不存在'
			];
		}
		$data = Account::findFirst(
				    [
				        "nick = ?1",
				        "bind" => [1 => $account],
				        "hydration" => Resultset::HYDRATE_OBJECTS
				    ]
				);
		if($data){
			$this->log_info($data->id);
			$this->log_info($data->password);
			$rsa = new RSA();
			$password = $rsa->decryptFromRSA($password, $this->config->RSA_PRIVATE_KEY);//RSA解密
			if($data->password === md5($password)){
				return [
					'code'=>-1,
					'message'=>'项目测试中。本项目<a target="_blank" href="https://github.com/clk528/demo">github仓库</a>',
					'data' => $data
				];
			}
			return [
				'code'=>-1,
				'message'=>"密码错误",
				'data' => $data				
			];
		} else {
			return [
				'code'=>-1,
				'message'=>"${account}不存在",
				'data' => $data
			];
		}		
	}
}