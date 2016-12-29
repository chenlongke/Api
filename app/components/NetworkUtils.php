<?php
/**
 * Created by PhpStorm.
 * User: seaman
 * Date: 11/6/13
 * Time: 3:57 PM
 */

class NetworkUtils {

    public static function http($url,$post_fields, $post = false, $referer = NULL, $cookies = null, $transfercode = true){
//        $cookie_path = Yii::app()->params['tmp_dir'] . "/cookie.txt";

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 200000 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 200000 );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9) AppleWebKit/537.71 (KHTML, like Gecko) Version/7.0 Safari/537.71");
//        curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie_path); //保存
//        curl_setopt( $ch, CURLOPT_COOKIEFILE, $cookie_path); //读取

//        curl_setopt($ch, CURLOPT_PROXY, "223.4.33.166:3128");



        if(!empty($referer)){
            curl_setopt( $ch, CURLOPT_REFERER, $referer);
        }
        if($post){
            curl_setopt( $ch, CURLOPT_POST, 1);
        }
        if(!empty($post_fields)){
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_fields);
        }
        if(!empty($cookies)){
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
                'Cookie: ' . $cookies
            ));
        }
        $return_content = curl_exec($ch);
        if($return_content === false){
            throw new NetworkExcetpion(curl_error($ch),500);
        }
        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($return_code != 200){
            curl_close($ch);

            throw new ErrorException($return_content,$return_code);
        }
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        preg_match("/(.*?)(;charset=(.*)?)/is", $content_type, $matches );
        if ( isset( $matches[3] ) ){
            $encode = $matches[3];
            if($encode==="utf-8" || $encode==="utf8"){

            }else{
                $return_content = mb_convert_encoding($return_content,"UTF-8",$encode);
            }
        }


        if($transfercode){
            $result = preg_match_all("/<meta[\\W]+charset=['|\"](?'charset'.*?)['|\"]/is", $return_content, $matches, PREG_SET_ORDER);
            if($result){
                $encode = strtolower(trim($matches[0]['charset']));
                if($encode==="utf-8" || $encode==="utf8"){

                }else{
                    $return_content = mb_convert_encoding($return_content,"UTF-8",$encode);
                }
            }else{
                $result = preg_match_all("/text\\/html;[\\W]+charset=(?'charset'.*?)['|\"]/is", $return_content, $matches, PREG_SET_ORDER);

                if($result){
                    $encode = strtolower(trim($matches[0]['charset']));
                    if($encode==="utf-8" || $encode==="utf8"){

                    }else{
                        $return_content = mb_convert_encoding($return_content,"UTF-8",$encode);
                    }
                }
            }
        }

        return $return_content;
    }

    public static function curl_download($remote_url, $local_file, $referer = NULL, $cookies = null) {
//        $cookie_path = Yii::app()->params['tmp_dir'] . "/cookie.txt";

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 2000 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 2000 );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt( $ch, CURLOPT_URL, $remote_url);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9) AppleWebKit/537.71 (KHTML, like Gecko) Version/7.0 Safari/537.71");
//        curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie_path); //保存
//        curl_setopt( $ch, CURLOPT_COOKIEFILE, $cookie_path); //读取

        if(!empty($referer)){
            curl_setopt( $ch, CURLOPT_REFERER, $referer);
        }
        if(!empty($cookies)){
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
                'Cookie: ' . $cookies
            ));
        }

        $fp = fopen($local_file, "w");
        curl_setopt( $ch, CURLOPT_FILE, $fp);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

    public static function convertUrlQuery($query) {
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }

        return $params;
    }

} 