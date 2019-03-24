<?php

/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/22
 * Time: 2:25
 */
namespace app\common\lib\task;

use app\common\lib\ali\Sms;
use app\common\lib\redis\Predis;

class Task
{
    /**
     * @name 异步发送验证码
     * @param $data
     * @return bool
     */
    public function sendSms($data){
        try{
            $response = Sms::sendSms($data['phone'],$data['code']);
        }catch (\Exception $e){
            echo $e->getMessage();
//            return common::show(0,'短信发送异常');
        }
        //如果发送成功,把验证码记录到redis
        if($response->Message == 'OK'){
            Predis::getInstance()->set("sms_".$data['phone'], $data['code'], 120);
        }else{
            return false;
        }
        return true;
    }

    public function pushLive($data, $serv){
        $clients = Predis::getInstance()->sMembers('live');
        foreach ($clients as $fd){
            $serv->push($fd, json_encode($data));
        }
    }
}