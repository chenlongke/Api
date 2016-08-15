<?php
class IndexController extends BaseController{
	private $user_nick = null;
	private $gatewayUrl = "http://baidu.com";
	private $sdkVersion = "test-clk-xf9";
	private $secretKey = "sdfsdfsdfdsf46464";
	public function initialize(){
		$this->user_nick="chen923897754";		
	}
	public function indexAction(){


		/*$this->test();
		die();*/


		/*request_api();



		exit;*/
		$appkey = "21085840";
		/*$appsign = "sdfsdfsdfdsf46464";*/
		$method = "xh9.trade.getOrder";
		$session = "sdf464646546sf6ds4f6d4f";
		$sign = "no";
		/*$timestamp*/
		$v = "v1";

		//组装系统参数
		$sysParams["app_key"] = $appkey;
		$sysParams["v"] = $v;
		$sysParams["sign_method"] = "md5";
		$sysParams["method"] = $method;
		$sysParams["timestamp"] = date("Y-m-d H:i:s");
		if (null != $session){
			$sysParams["session"] = $session;
		}
		$apiParams = array(
			"fields"=>"tid,pay_time",
			"start_time"=>"2016-06-06 12:12:00",
			"end_time"=>"2016-08-06 12:12:00",
		);

		//系统参数放入GET请求串
		$requestUrl = $this->gatewayUrl."?";
		$sysParams["partner_id"] = $this->sdkVersion;
		
		//签名
		$sysParams["sign"] = $this->generateSign(array_merge($apiParams, $sysParams));

		foreach ($sysParams as $sysParamKey => $sysParamValue){
			// if(strcmp($sysParamKey,"timestamp") != 0)
			/*$requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";*/
			$requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
		}

		// $requestUrl .= "timestamp=" . urlencode($sysParams["timestamp"]) . "&";
		/*$requestUrl = substr($requestUrl, 0, -1);
		echo "<pre>";
		echo $requestUrl;*/
	}
	protected function generateSign($params){
		ksort($params);
		$stringToBeSigned = $this->secretKey;
		foreach ($params as $k => $v){
			if(is_string($v) && "@" != substr($v, 0, 1)){
				$stringToBeSigned .= "$k$v";
			}
		}
		unset($k, $v);
		$stringToBeSigned .= $this->secretKey;
		return strtoupper(md5($stringToBeSigned));
	}
}