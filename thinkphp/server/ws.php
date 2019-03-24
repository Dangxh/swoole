<?php
use app\common\lib\common;
use app\common\lib\redis\Predis;

/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/22
 * Time: 1:21
 */
class Ws
{
    const HOST = "0.0.0.0";
    const PORT = 8811;

    public $ws = null;
    public function __construct()
    {
        $this->ws = new swoole_websocket_server(self::HOST, self::PORT);
        $this->ws->set([
            'enable_static_handler' => true,
            'document_root' => "/root/swoole/thinkphp/public/static",
            'worker_num' => 4,
            'task_worker_num' =>4,
        ]);
        $this->ws->on("open", [$this, 'onOpen']);
        $this->ws->on("message", [$this, 'onMessage']);
        $this->ws->on("workerstart", [$this, 'onWorkerStart']);
        $this->ws->on("request", [$this, 'onRequest']);
        $this->ws->on("task", [$this, 'onTask']);
        $this->ws->on("finish", [$this, 'onFinish']);
        $this->ws->on("close", [$this, 'onClose']);
        $this->ws->start();
    }

    public function onWorkerStart($server, $worker_id){
        // 定义应用目录
        define('APP_PATH', __DIR__ . '/../application/');
        // 加载框架引导文件
//        require __DIR__ . '/../thinkphp/base.php';
        require __DIR__ . '/../thinkphp/start.php';
    }

    public function onRequest($request, $response){
        $_SERVER = [];
        if(isset($request->server)){
            foreach ($request->server as $k => $v){
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        if(isset($request->header)){
            foreach ($request->header as $k => $v){
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        $_GET = [];
        if(isset($request->get)){
            foreach ($request->get as $k => $v){
                $_GET[$k] = $v;
            }
        }
        $_POST = [];
        if(isset($request->post)){
            foreach ($request->post as $k => $v){
                $_POST[$k] = $v;
            }
        }
        $_FILES = [];
        if(isset($request->files)){
            foreach ($request->files as $k => $v){
                $_FILES[$k] = $v;
            }
        }

        $_POST['http_server'] = $this->ws;

        ob_start();
        // 执行应用并响应
        try{
            think\Container::get('app', [APP_PATH])
                ->run()
                ->send();
        }catch (Exception $e){

        }

        $res = ob_get_contents();
        ob_end_clean();
        $response->end($res);
    }

    /**
     * 监听ws连接事件
     * @param $ws
     * @param $request
     */
    public function onOpen($ws, $request){
        //fd redis
        Predis::getInstance()->sAdd('live', $request->fd);
//        var_dump($request->fd);
    }

    /**
     * 监听ws消息事件
     * @param $ws
     * @param $frame
     */
    public function onMessage($ws, $frame){
        echo "ser-push-message:{$frame->data}\n";
        $ws->push($frame->fd, "server-push:".date("Y-m-d H:i:s"));
    }

    /**
     * 监听关闭事件
     * @param $ws
     * @param $fd
     */
    public function onClose($ws, $fd){
        Predis::getInstance()->sRem('live', $fd);
        echo "clientid:{$fd}\n";
    }

    public function onTask($serv, $taskId, $workerId, $data){
        //task任务分发，不同的任务走不同的逻辑
        $obj = new app\common\lib\task\Task();
        $method = $data['method'];
        $flag = $obj->$method($data['data']);
        return $flag;
    }

    public function onFinish($serv, $taskId, $data){
        echo "task:{$taskId}\n";
        echo "finish-data-success{$data}\n";
    }
}

new Ws();