<?php
$client = new Swoole\Client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_SYNC);
if (!$client->connect('127.0.0.1', 9504, -1)) {
    exit("connect failed. Error: {$client->errCode}\n");
}
$d = "aaaaa";
$data = pack("N",strlen($d)).$d;
$client->send($data);
echo $client->recv();
$client->close();