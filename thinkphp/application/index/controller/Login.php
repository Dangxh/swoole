<?php
/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/21
 * Time: 23:23
 */

namespace app\index\controller;


use app\common\lib\common;
use app\common\lib\redis\Predis;

class Login
{
    public function index(){
        $phone = intval($_GET['phone_num']);
        $code = intval($_GET['code']);
        $redisCode = Predis::getInstance()->get('sms_'.$phone);
        if($redisCode == $code){
            $data = [
                'user' => $phone,
                'time' => date('Y-m-d H:i:s')
            ];
            Predis::getInstance()->set(18780225975,$data);
            return common::show(1, 'ok', $data);
        }else{
            return common::show(0, '失败');
        }
    }
}