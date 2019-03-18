<?php
/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/17
 * Time: 21:29
 */
/**
 * 读取文件
 */
$result = swoole_async_readfile(__DIR__."/test.txt", function ($filename, $fileContent){
    echo "filename".$filename.PHP_EOL;
    echo "content:".$fileContent.PHP_EOL;
});
var_dump($result);
echo "start".PHP_EOL;