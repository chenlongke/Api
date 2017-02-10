<?php
/**
 * Created by PhpStorm.
 * User: seaman
 * Date: 6/15/14
 * Time: 6:51 PM
 */

class RedisHelper {

    public static function getredis4parm($ip,$port){
        $redis = new Redis();
        $redis->pconnect( $ip , $port );
        return $redis;
    }
}