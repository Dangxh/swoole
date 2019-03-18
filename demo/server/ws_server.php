<?php
/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/17
 * Time: 16:08
 */
$server = new Swoole\WebSocket\Server("0.0.0.0", 8812);

$server->set([
    'enable_static_handler' => true,
    'document_root' => "/root/swoole/data/",
]);

//监听websocket打开连接事件
$server->on('open', 'onOpen');
function onOpen($server, $request){
    print_r($request->fd);
}

//监听ws消息事件

$server->on('message', function (Swoole\WebSocket\Server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "this is server");
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();