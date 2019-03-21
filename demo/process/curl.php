<?php
/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/20
 * Time: 13:03
 */
echo "process-start-time:".date("Y-m-d H:i:s");
$workers = [];
$urls = [
    'http:://baidu.com',
    'http:://sina.com.cn',
    'http:://qq.com',
    'http:://www.baidu.com?search=singwa',
    'http:://www.baidu.com?search=singwa2',
    'http:://www.baidu.com?search=imooc',
];
//传统方法
//foreach ($urls as $url){
//    $content[] = file_get_contents($url);
//}
for ($i = 0; $i < 6; $i++){
    //子进程
    $process = new swoole_process(function ($worker) use($i, $urls){
        //curl
        $content = curlData($urls[$i]);
        echo $content.PHP_EOL;
    }, true);
    $pid = $process->start();
    $workers[$pid] = $process;
}

foreach ($workers as $process){
    echo $process->read();
}

function curlData($url){
    sleep(1);
    return $url . "success".PHP_EOL;
}
echo "process-end-time:".date("Y-m-d H:i:s");