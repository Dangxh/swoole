<?php
/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/18
 * Time: 1:54
 */
$content = date("Y-m-d H:i:s");
swoole_async_writefile(__DIR__."/1.log", $content, function ($filename){
    echo "success".PHP_EOL;
},FILE_APPEND);

echo "start".PHP_EOL;