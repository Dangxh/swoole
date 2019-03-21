<?php

/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/22
 * Time: 1:21
 */
class Http
{
    const HOST = "0.0.0.0";
    const PORT = 8811;

    public $http = null;
    public function __construct()
    {
        $this->http = new swoole_http_server("0.0.0.0", 8811);
        $this->http->set([
            'enable_static_handler' => true,
            'document_root' => "/root/swoole/thinkphp/public/static",
            'worker_num' => 4,
            'task_worker_num' =>4,
        ]);
        $this->http->on("workerstart", [$this, 'onWorkerStart']);
        $this->http->on("request", [$this, 'onRequest']);
        $this->http->on("task", [$this, 'onTask']);
        $this->http->on("finish", [$this, 'onFinish']);
        $this->http->on("close", [$this, 'onClose']);
        $this->http->start();
    }

    public function onWorkerStart($server, $worker_id){
        // 定义应用目录
        define('APP_PATH', __DIR__ . '/../application/');
        // 加载框架引导文件
        require __DIR__ . '/../thinkphp/base.php';
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

new Http();