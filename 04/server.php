<?php
class Worker{
    protected $socket = NULL;
    public $onConnect = NULL;
    public $onMessage = NULL;

    public function __construct($socket_address)
    {
        $this->socket = stream_socket_server($socket_address); //创建socket，绑定端口，监听端口
    }

    public function start()
    {
        while (true)
        {
            $clientSocket = stream_socket_accept($this->socket);
            if(!empty($clientSocket) && is_callable($this->onConnect))
            {
                call_user_func($this->onConnect, $clientSocket);
            }

            $buffer = fread($clientSocket, 65535);

            if(!empty($buffer) && is_callable($this->onMessage))
            {
                call_user_func($this->onMessage, $clientSocket, $buffer);
            }

            //fclose($clientSocket);
        }
    }
}

$server = new Worker('tcp://0.0.0.0:9501');

$server->onConnect = function($arg)
{
    echo "new connect: " . $arg . "\n";
};

$server->onMessage = function($conn, $message)
{
    $content = "<html><head><title>牛逼了</title></head><body><div style='color:red'>真的牛逼</div></body></html>";
    $http_response_header = "HTTP/1.1 200 OK\r\n";
    $http_response_header .= "Content-Type: text/html; charset=UTF-8\r\n";
    $http_response_header .= "Connection: keep-alive\r\n";
    $http_response_header .= "Server: EQS Cloud Server\r\n";
    $http_response_header .= "Content-length: " . strlen($content) . "\r\n\r\n";
    $http_response = $http_response_header . $content;
    fwrite($conn, $http_response);
};

$server->start();