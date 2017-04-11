<?php

/**
* 登录，就只管登录
*/

use Phalcon\Mvc\Model\Resultset;
class LoginController extends Phalcon\Mvc\Controller
{
	private $_isJsonpResponse = false;
    private $_isJsonResponse = false;

	public function initialize()
	{
		if($this->session->get("userAuth")){			
	        $this->response->redirect( '/index' );
		}
		$this->log = $this->di->get('logger');
	}

	function indexAction()
	{
		$this->view->setVars([
			"region" => json_decode($this->getLocationAction(),true),
			"title" => "登录",
			"index" => $this->getip()
		]);
		$this->view->pick('Login/index');
	}
	/**
	* Check pass nick isexist
	* Check the account password is correct
	*/
	function checkAccountAction()
	{
		$this->setJsonpResponse();//jsonp返回
		$account = isset($_POST['account']) ? $_POST['account']  : '';
		$password = isset($_POST['password']) ? $_POST['password']  : '';
		if(empty($account) || empty($password)){
			return [
				'code'=>-1,
				'message'=>'用户名不存在'
			];
		}
		$data = Account::findFirst([
		        "nick = ?1",
		        "bind" => [1 => $account],
		        "hydration" => Resultset::HYDRATE_OBJECTS
			]
		);

		if($data){
			$rsa = new RSA();
			$password = $rsa->decryptRSA($password, $this->config->RSA_PRIVATE_KEY);
			$DBpassword = $rsa->decryptRSA($data->password, $this->config->RSA_PRIVATE_KEY);
			$this->log_info($password);
			unset($data->password);
			if($DBpassword === $password){
				$this->session->set("userAuth",json_encode($data));
				/*return [
					'code'=>-1,
					'message'=>'项目测试中。本项目<a target="_blank" href="https://github.com/clk528/demo">github仓库</a>',
					'data' => $data
				];*/

				return [
					'code'=>1,
					'message'=>'登录成功',
					'url' => "/Chat"
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

	public function logout()
	{
		$this->session->destroy();
	}

    private function getip()
    {
        if (getenv("HTTP_CLIENT_IP")){
            $ip = getenv("HTTP_CLIENT_IP");
        }else if(getenv("HTTP_X_FORWARDED_FOR")){
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        }else if(getenv("REMOTE_ADDR")){
            $ip = getenv("REMOTE_ADDR");
        }else {
            $ip = "Unknow";
        }
        return $ip == "127.0.0.1" ? "27.115.94.242" : $ip;
    }

    private function getLocationAction()
    {
        $ip = $this->getip();
        $ip == "127.0.0.1" ? $ip = "27.115.94.242" : '';        
        return NetworkUtils::http("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip={$ip}",null);
    }

    //设置JSONP返回格式
    private function setJsonpResponse() {
        $this->view->disable();
        isset($_REQUEST["callback"]) ? $this->_isJsonpResponse = true : $this->_isJsonResponse = true;
    }

    private function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher) 
    {
        $data = $dispatcher->getReturnedValue();
        if ($this->_isJsonpResponse) {
            $jsonp = $_REQUEST["callback"];
            $this->response->setContent($jsonp . "(" . json_encode($data) . ")");
            return $this->response->send();
        }else if ($this->_isJsonResponse) {
            $this->response->setJsonContent($data);
            return $this->response->send();
        }
    }

    private function log_info($param) 
    {
		$this->log->info($param);
	}

	public function ajaxAction()
	{
		$data = [
			'name' => 'clk',
			'age' => 24
		];
		exit(json_encode($data));
	}
}