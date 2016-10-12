<?php
include "vendor/autoload.php";
$test = new \Ritoyan\Pipe\Pipe();
$test->setCmd("tail -f /root/t.txt")->setCallback(function($item){
        echo "获取内容：" . $item . "\n";
})->setDelimiter("\n")->run();
