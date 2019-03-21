<?php
/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/20
 * Time: 19:58
 */
//$redis = new Swoole\Coroutine\Redis();
//$redis->connect('127.0.0.1', 6379);
//$redis->get('key');
$http = new swoole_http_server('0.0.0.0', 8001);
$http->on('request', function ($request,$response){
    //获取redis里边的key的内容
    $redis = new Swoole\Coroutine\Redis();
    $redis->connect('127.0.0.1', 6379);
    $value = $redis->get($request->get['a']);

    $response->header('Content-Type', "text/plain");
    $response->end($value);
});
$http->start();

