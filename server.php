<?php
/*$server = new \Swoole\Server("127.0.0.1", 9503);
$server->on('connect', function ($server, $fd){
    echo "connection open: {$fd}\n";
});
$server->on('receive', function ($server, $fd, $reactor_id, $data) {
    $server->send($fd, "Swoole: {$data}");
    $server->close($fd);
});
$server->on('close', function ($server, $fd) {
    echo "connection close: {$fd}\n";
});
$server->start();*/

$server1 = new \Swoole\Server("0.0.0.0", 9504);
$server1->set(
    array(
        'worker_num'=>4, //工作进程数量
        'heartbeat_idle_time'=>10, //心跳超时时间
        'heartbeat_check_interval'=>3, //心跳检测间隔
        'open_length_check'=>1, //是否开启包长检测
        'package_length_type'=>'N', //包头长度
        'package_length_offset'=>0, //包长从哪位开始计算
        'package_body_offset'=>4, //包体从哪位开始计算
        'package_max_length'=>1024*1024*2, //包长限制
        'buffer_output_size'=>1024*1024*2, //输出缓冲区大小
    )
);
$server1->on('connect', function($server, $fd){
   echo "\n--------{$fd}--------\n";
});

$server1->on('receive', function($server, $fd, $rid, $data) {
    $arr = unpack('N', $data);
    $server->send($fd, "你好，");
    $server->send($fd, "这是你的包长：$arr[1]\n");
    $server->send($fd, "这是你的包体：");
    $server->send($fd, substr($data, 4, $arr[1]));
    $server->send($fd, "\n");
    var_dump(unpack('N', $data));
    $server->close($fd);
});

$server1->on('close', function($server, $fd){
   //var_dump($server);
   echo "close:".$fd;
});
$server1->start();