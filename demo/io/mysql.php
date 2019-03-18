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
        $this->db->connect($this->dbConfig, function ($db, $result) use($id, $username){
            if(!$result){
                var_dump($db->connect_error);
            }

//            $sql = "select * from test where id=1";
            $sql = "update test set `username` = '".$username."' where id=".$id;
            var_dump($sql);
            $db->query($sql, function ($db, $result){
                if($result === false){
                    var_dump($db->error);
                }elseif ($result === true){
                    var_dump($db->affected_rows);
                }else{
                    var_dump($result);
                }
                $db->close();
            });
        });
        return true;
    }
}

$obj = new AysMysql();
$flag = $obj->execute(1, 'singwa_1111');
var_dump($flag).PHP_EOL;
echo "start".PHP_EOL;