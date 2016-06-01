<?php
/**
 * Created by PhpStorm.
 * User: captain
 * Date: 16/5/29
 * Time: 下午4:07
 */
use ZPHP\ZPHP;
$zphp = null;
$mimes = null;
$http = new swoole_http_server('0.0.0.0',9502);
$http->set([
    'worker_num'=>2,//设置为16哥进程
    //'daemonize'=>1,//设置后台运行
]);

$http->on('request',function(swoole_http_request $request, swoole_http_response $response){
//    print_r($request);
//    $response->status(404);
//
//    $response->end('hello world');

    $pathinfo = $request->server['path_info'];
    $filename = __DIR__.$pathinfo;
//    echo $pathinfo;
    //echo $filename;
    if(is_file($filename)){
        $ext = pathinfo($request->server['path_info'],PATHINFO_EXTENSION);
        if('php' == $ext){
            $response->status(404);
            $response->end('404 not found');
        }else{
            global $mimes;
            //$mimes = include "mimes.php";
            $response->header("Content-Type",$mimes[$ext]);
            $content = file_get_contents($filename);
            $response->end($content);
        }
    }else{
        global $zphp;
        ob_start();
        $zphp->run();
        $result = ob_get_clean();
        ob_end_clean();
        $response->end($result);
    }
});
$http->on('WorkerStart',function($serv,$worker_id){
    require __DIR__.DIRECTORY_SEPARATOR.'zphp'.DIRECTORY_SEPARATOR.'ZPHP'.DIRECTORY_SEPARATOR.'ZPHP.php';
    global $zphp;
    $zphp = ZPHP::run(__DIR__,false,'default');
    global $mimes;
    $mimes = require 'mimes.php';
});
$http->start();
