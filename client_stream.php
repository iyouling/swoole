<?php
//使用 stream_socket_client 打开 tcp 连接
$fp = stream_socket_client("tcp://127.0.0.1:9504");
$msg = "fsockopen send message";
//向句柄中写入数据
fwrite($fp,$msg);
$ret = "";
//循环遍历获取句柄中的数据，其中 feof() 判断文件指针是否指到文件末尾
while (!feof($fp)){
    stream_set_timeout($fp, 2);
    $ret .= fgets($fp, 128);
}
//关闭句柄
fclose($fp);
echo $ret;
