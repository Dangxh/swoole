<?php
namespace app\index\controller;

use app\common\lib\common;

class Index
{
    public function index()
    {
        return '';
    }

    public function index2(){
//        $phone = request()->get('phone_num', 0, 'intval');
        $phone = $_GET['phone_num'];
        if(empty($phone)){
            return common::show(0, 'error');
        }
        $code = rand(1000,9999);
        $taskData = [
            'method' =>'sendSms',
            'data' => [
                'phone' => $phone,
                'code' => $code,
            ],
        ];
        $_POST['http_server']->task($taskData);
        return common::show(1, '验证码发送成功');
//        if($response->Message == 'OK'){
//            $redis = new \Swoole\Coroutine\Redis();
//            $redis->connect('127.0.0.1',6379);
//            $redis->set("sms_".$phone, $code, 120);
//            return common::show(1, '验证码发送成功');
//        }else{
//            return common::show(0, '验证码发送失败');
//        }
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
