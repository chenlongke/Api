<?php

class RedisHelper {

    public static function getredis4parm($ip,$port){
        $redis = new \Redis();
        $redis->pconnect( $ip , $port );
        return $redis;
    }
}