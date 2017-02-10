<?php
/**
 * 后端日志跟踪公共方法
 * User: seaman
 * Date: 3/3/15
 * Time: 11:32 AM
 * Version: 1.0
 */

class TrackingHelper {

    public static function track($pid,$nick,$event){
        //url: http://mcs.zzgdapp.com/1.gif?t=1425354149758&p=AD20140730133000&n=%E4%BA%9A%E6%A5%A06646718&e=indexpvip
        $nick = urlencode($nick);
        $timestamp = time();
        $url = "http://mcs.zzgdapp.com/1.gif?t={$timestamp}&p={$pid}&n={$nick}&e={$event}";
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 2000 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 2000 );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
        $return_content = curl_exec($ch);
        curl_close($ch);
    }
}