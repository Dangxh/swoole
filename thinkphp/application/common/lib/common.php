<?php

/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/21
 * Time: 19:56
 */
namespace app\common\lib;
class common
{
    public static function show($status, $message = '', $data = []){
        $result = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
        return json_encode($result);
    }
}