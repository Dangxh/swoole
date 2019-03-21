<?php
/**
 * Created by PhpStorm.
 * User: DXH
 * Date: 2019/3/20
 * Time: 14:10
 */

$table = new swoole_table(1024);

$table->column('id', $table::TYPE_INT, 4);
$table->column('name', $table::TYPE_STRING, 64);
$table->column('age', $table::TYPE_INT, 3);
$table->create();

$table->set('singwa_imooc', ['id' => 1, 'name' => 'singwa', 'age' => 30]);
//print_r($table->get('singwa_imooc'));
$table->incr('singwa_imooc', 'age', 2);
print_r($table['singwa_imooc']);