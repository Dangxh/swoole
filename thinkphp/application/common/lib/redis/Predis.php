<?php
namespace app\common\lib\redis;
/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/22
 * Time: 0:07
 */
class Predis
{
    public $redis = "";
    private static $instance = null;

    public static function getInstance(){
        if(empty(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1', 6379);
    }

    public function set($key, $value, $time = 0){
        if(!$key){
            return '';
        }
        if(is_array($value)){
            $value = json_encode($value);
        }
        if(!$time){
            return $this->redis->set($key, $value);
        }
        return $this->redis->setex($key, $time, $value);
    }

    public function get($key){
        if(!$key){
            return '';
        }
        return $this->redis->get($key);
    }

//    public function sAdd($key, $value){
//        return $this->redis->sAdd($key, $value);
//    }
//
//    public function sRem($key, $value){
//        return $this->redis->sRem($key, $value);
//    }

    public function __call($name, $arguments)
    {
//        echo $name.PHP_EOL;
//        print_r($arguments);
        $this->redis->$name($arguments[0], $arguments[1]);
    }

    public function sMembers($key){
        return $this->redis->sMembers($key);
    }
}