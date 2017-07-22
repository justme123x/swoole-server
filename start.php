<?php

$title = defined('PHP_BINARY') ? PHP_BINARY : '';
$title .= ' PHPGiftSever';
if (cli_set_process_title($title) === false) {
    exit('process title set fail!');
}

require 'vendor/autoload.php';

date_default_timezone_set('PRC');

// true 测试环境 false 线上环境
define('DEV', true);
//根目录
define('ROOT_PATH', __DIR__);
//配置文件目录
define('CONFIG_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR);

//启动礼物服务
$server = new \Lib\Services\GiftServerService();
$server->start();