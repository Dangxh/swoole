<?php
/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/22
 * Time: 23:40
 */

namespace app\admin\controller;


use app\common\lib\common;
use app\common\lib\redis\Predis;

class Live
{
    public function push(){
        if(empty($_GET)){
            return common::show(1, 'error');
        }
        $teams = [
            1 => [
                'name' => '马刺',
                'logo' => '/live/imgs/team1.png'
            ],
            4 => [
                'name' => '火箭',
                'logo' => '/live/imgs/team2.png'
            ],
        ];
        $data = [
            'type' => intval($_GET['type']),
            'title' => !empty($teams[$_GET['team_id']]['name'])?$teams[$_GET['team_id']]['name']:'直播员',
            'logo' => !empty($teams[$_GET['team_id']]['logo'])?$teams[$_GET['team_id']]['logo']:'',
            'content' => !empty($_GET['content'])?$_GET['content']:'',
            'image' => !empty($_GET['image'])?$_GET['image']:'',
        ];
        //1、赛况的基本信息入库 2、数据组织好 push到直播页面
        $clients = Predis::getInstance()->sMembers('live');
        foreach ($clients as $fd){
            $_POST['http_server']->push($fd, json_encode($data));
        }
//        $taskData = [
//            'method' =>'pushLive',
//            'data' => $data
//        ];
//        $_POST['http_server']->task($taskData);
//        return common::show(1,'ok');
    }
}