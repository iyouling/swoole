<?php
//使用 fsockopen 打开tcp连接句柄
$fp = fsockopen("tcp://127.0.0.1",9504);
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
