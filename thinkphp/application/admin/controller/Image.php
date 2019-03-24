<?php
/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/22
 * Time: 22:51
 */

namespace app\admin\controller;


use app\common\lib\common;

class Image
{
    public function index(){
        $file = request()->file('file');
        $info = $file->move('../public/static/upload');
        if($info){
            $data = [
                'image' => 'www.dangxh.com:8811/upload/'.$info->getSaveName(),
            ];
            return common::show(1,'ok',$data);
        }else{
            return common::show(0,'error');
        }
    }
}