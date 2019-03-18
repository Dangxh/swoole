<?php
/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/18
 * Time: 12:37
 */
class AysMysql{

    public $db = null;
    public $dbConfig = [];
    public function __construct()
    {
        $this->db = new Swoole\Mysql;

        $this->dbConfig = [
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'root',
            'password' => 'root',
            'database' => 'swoole',
            'charset' => 'utf8',
        ];
    }

    public function update(){

    }

    public function add(){

    }

    public function execute($id, $username){
        $this->db->connect($this->dbConfig, function ($db, $result){
            if(!$result){
                var_dump($db->connect_error);
            }

            $sql = "select * from test where id=1";
            $db->query($sql, function ($db, $result){
                var_dump($result);
                $db->close();
            });
        });
        return true;
    }
}

$obj = new AysMysql();
$obj->execute(1, 'singwa');