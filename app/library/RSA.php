<?php
class RSA {
	/**
	 *  @RSA公钥加密方法
	 *  参数：$data=需要加密的数据，$public_key=RSA公钥（可以读配置文件。中间必须没有空格换行且没有注释）
	 *  返回：base64加密后的RSAData 失败返回false
	 */
	function encryptRSA($data, $public_key) { //public_key在调用时C('RSA_PUBLIC_KEY')传入
	    if (empty($data)) {
	        return false;
	    }
	    $public_key = $this->explainRSAKey($public_key); //初始化公钥
	    $public_key = openssl_pkey_get_public($public_key); //判断公钥是否是可用的
	    if ( $public_key == false) {
	        return false;
	    }
	    $rs = openssl_public_encrypt($data, $encrypted, $public_key);//公钥加密  
	    if ($rs) {
	        return base64_encode($encrypted);
	    }
	    return false;
	}

	/**
	 *  @RSA私钥解密方法
	 *  参数 $RSAData=需要RSA解密的数据，$private_key=RSA私钥（可读配置文件。中间必须没有空格换行且没有注释）
	 *  返回 解密后的data 失败返回false
	 */
	function decryptRSA($RSAData,$private_key) { //private_key在调用时C('RSA_PRIVATE_KEY')传入
	    if (empty($RSAData)) {
	        return false;
	    }	
	    $private_key = $this->explainRSAKey($private_key, 2); //初始化私钥
	    $private_key = openssl_pkey_get_private($private_key); //判断私钥是否是可用的
	    if ($private_key == false) {
	        return false;
	    }
	    $rs = openssl_private_decrypt(base64_decode($RSAData),$decrypted,$private_key);//私钥解密
	    if ($rs) {
	        return $decrypted;
	    }
	    return false;
	}

	/**
	 *  @RSA密钥初始化，将密钥转换成openssl方法能处理的格式
	 *  参数：$key=公钥或私钥（必须是没有空格、没有换行、没有开头和结束注释的密钥）$type=1表示公钥初始化，type=其他表示私钥初始化 
	 */
	function explainRSAKey( $key, $type=1) { //1表示公钥解析，其他表示私钥解析
	    if ($type == 1) {
	        $str_begin = '-----BEGIN PUBLIC KEY-----' . "\r\n";
	        $str_end = '-----END PUBLIC KEY-----';
	    } else {
	        $str_begin = '-----BEGIN RSA PRIVATE KEY-----' . "\r\n";
	        $str_end = '-----END RSA PRIVATE KEY-----';
	    }
	    $len = strlen($key);
	    $arr = array();
	    for ($i=0; $i < $len; $i+=64) { 
	        $arr[] = substr($key, $i, 64) . "\r\n";
	    }
	    $str_content = implode('', $arr);
	    $explainKey = $str_begin . $str_content . $str_end;
	    return $explainKey;
	}
}