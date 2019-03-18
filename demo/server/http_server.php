<?php
/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/17
 * Time: 14:57
 */
//$http = new swoole_http_server('0.0.0.0', 8811);
$http = new Swoole\Http\Server('0.0.0.0', 8811);
$http->set([
    'enable_static_handler' => true,
    'document_root' => "/root/swoole/data/",
]);
$http->on('request', function ($request, $response){
    $content = [
        'date:' => date("Y-m-d H:i:s"),
        'get:' => $request->get,
        'post:' => $request->post,
        'header:' => $request->header,
    ];
    swoole_async_writefile(__DIR__."/access.log", json_encode($content).PHP_EOL,function ($filename){
        echo "success";
    },FILE_APPEND);
    $response->end("sss".json_encode($request->get));
});
$http->start();