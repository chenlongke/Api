<?php
class Api{
	private $appkey;
	private $secretKey;
	private $v = "A";
	private $sdkVersion = "1471311044149";
	private $gatewayUrl = "http://api.odamiao.com/router";

	

	public function __construct($appkey,$secretKey){
		$this->appkey=$appkey;
		$this->secretKey=$secretKey;
	}
	public function request_api($method,$apiParams=array()){
		$sysParams["app_key"] = $this->appkey;
		$sysParams["v"] = $this->v;
		$sysParams["sign_method"] = "md5";
		$sysParams["method"] = $method;
		$sysParams["timestamp"] = date("Y-m-d H:i:s");
		
		$requestUrl = $this->gatewayUrl."?";
		$sysParams["partner_id"] = $this->sdkVersion;		
		$sysParams["sign"] = $this->generateSign(array_merge($apiParams, $sysParams));//签名
		foreach ($sysParams as $sysParamKey => $sysParamValue){
			$requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
		}
		$requestUrl = substr($requestUrl, 0, -1);
		$result = $this->curl($requestUrl,$apiParams);
	}
	private function curl($url,$postFields){
		echo "$url ------- ".json_encode($postFields);
	}
	private function generateSign($params){
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