<?php
/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/18
 * Time: 16:25
 */
$redisClient = new swoole_redis;
$redisClient->connect('127.0.0.1',6379,function ($redisClient, $result){
    echo 'connect'.PHP_EOL;
    var_dump($result);
    $redisClient->set('name', 'dangxh', function ($redisClient, $result){
        var_dump($result);
        $redisClient->close();
    });
});
echo "start".PHP_EOL;