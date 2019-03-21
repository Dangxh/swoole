<?php
/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/18
 * Time: 22:34
 */
$process = new swoole_process(function ($pre){
    //todo
    $pre->exec("/home/work/study/soft/php/bin/php", [__DIR__.'/../server/http_server.php']);
}, false);
$pid = $process->start();
echo $pid.PHP_EOL;
swoole_process::wait();