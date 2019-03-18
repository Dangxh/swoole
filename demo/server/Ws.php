<?php

/**
 * ws 优化基础类库
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/17
 * Time: 17:20
 */
class Ws
{
    const HOST = "0.0.0.0";
    const PORT = 8812;

    public $ws = null;
    public function __construct()
    {
        $this->ws = new Swoole\WebSocket\Server("0.0.0.0", 8812);
        $this->ws->set([
            'worker_num' => 2,
            'task_worker_num' =>2,
        ]);
        $this->ws->on("open", [$this, 'onOpen']);
        $this->ws->on("message", [$this, 'onMessage']);
        $this->ws->on("task", [$this, 'onTask']);
        $this->ws->on("finish", [$this, 'onFinish']);
        $this->ws->on("close", [$this, 'onClose']);
        $this->ws->start();
    }

    /**
     * 监听ws连接事件
     * @param $ws
     * @param $request
     */
    public function onOpen($ws, $request){
        var_dump($request->fd);
        swoole_timer_tick(2000, function ($timer_id){
            echo "2s: timeId:{$timer_id}\n";
        });
    }

    /**
     * 监听ws消息事件
     * @param $ws
     * @param $frame
     */
    public function onMessage($ws, $frame){
        echo "ser-push-message:{$frame->data}\n";
        //todo 10s
        $data = [
            'task' => 1,
            'fd' => $frame->fd,
        ];
//        $ws->task($data);
        swoole_timer_after(5000, function () use($ws, $frame){
            echo "5s-after\n";
            $ws->push($frame->fd, "server-time-after:");
        });
        $ws->push($frame->fd, "server-push:".date("Y-m-d H:i:s"));
    }

    /**
     * 监听关闭事件
     * @param $ws
     * @param $fd
     */
    public function onClose($ws, $fd){
        echo "clientid:{$fd}\n";
    }

    public function onTask($serv, $taskId, $workerId, $data){
        print_r($data);
        //耗时场景
        sleep(10);
        return "on task finish";
    }

    public function onFinish($serv, $taskId, $data){
        echo "task:{$taskId}\n";
        echo "finish-data-success{$data}\n";
    }
}

$obj = new Ws();