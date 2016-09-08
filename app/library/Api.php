<?php
class Api{
	private $appkey;
	private $secretKey;
	private $v = "A";
	private $sdkVersion = "1471311044149";
	private $gatewayUrl = "http://api.odamiao.com/router";
	private $readTimeout = 30;

	

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
	private function curl($url, $postFields = null){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if ($this->readTimeout) {
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->readTimeout);
		}
		if ($this->connectTimeout) {
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
		}
		curl_setopt ( $ch, CURLOPT_USERAGENT, "test" );
		//https 请求
		if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}

		if (is_array($postFields) && 0 < count($postFields)){
			$postBodyString = "";
			$postMultipart = false;
			foreach ($postFields as $k => $v){
				if(!is_string($v)){
					continue ;
				}

				if("@" != substr($v, 0, 1)){//判断是不是文件上传				
					$postBodyString .= "$k=" . urlencode($v) . "&"; 
				}else{//文件上传用multipart/form-data，否则用www-form-urlencoded				
					$postMultipart = true;
					if(class_exists('\CURLFile')){
						$postFields[$k] = new \CURLFile(substr($v, 1));
					}
				}
			}
			unset($k, $v);
			curl_setopt($ch, CURLOPT_POST, true);
			if ($postMultipart){
				if (class_exists('\CURLFile')) {
				    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
				} else {
				    if (defined('CURLOPT_SAFE_UPLOAD')) {
				        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
				    }
				}
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			}else{
				$header = array("content-type: application/x-www-form-urlencoded; charset=UTF-8");
				curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
				curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
			}
		}
		$reponse = curl_exec($ch);
		
		if (curl_errno($ch)){
			throw new Exception(curl_error($ch),0);
		}else{
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode){
				throw new Exception($reponse,$httpStatusCode);
			}
		}
		curl_close($ch);
		return $reponse;
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