<?php

namespace Lib\Utils;
/**
 * 日志工具类
 * Class LogUtils
 * @package Lib\Utils
 * @author chongyue<chongyue.peng@iaround.com>
 */
class LogUtils
{
    public $dir = 'log';
    public $logName = 'log';
    public $format = 'Y-m-d';

    public function __construct($dir = null, $logName = null, $format = 'Y-m-d')
    {
        if (!empty($dir)) $this->dir = $dir;
        if (!empty($logName)) $this->logName = $logName;
        if (!empty($dir)) $this->format = $format;
    }

    /**
     * 写日志
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function writeLog()
    {
        $message = $this->getMessageStr(func_get_args());
        $path = $this->logPath();
        $message = "[" . date('Y-m-d H:i:s.u') . '] Log: ' . $message;
        error_log($message, 3, $path);
    }

    /**
     * 获取日志路径
     * @return string
     */
    public function logPath()
    {
        $log_dir_path = ROOT_PATH . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . $this->dir;
        if (!is_dir($log_dir_path)) {
            mkdir($log_dir_path, 0777, true);
        }

        $date = date($this->format);

        $log_path = $log_dir_path . '/' . $this->logName . '-' . $date . '.log';
        if (!is_file($log_path)) {
            file_put_contents($log_path, '');
        }

        return $log_path;
    }

    /**
     * 获取写入日志字符串
     * @param array $behavior
     * @return string
     */
    public function getMessageStr(array $behavior)
    {
        return json_encode($behavior, JSON_UNESCAPED_UNICODE) . PHP_EOL;
    }
}