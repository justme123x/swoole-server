<?php
if (!function_exists('loadConfig')) {
    /**
     * 加载配置文件
     * @param string $file 文件名
     * @param string|null $key 配置项名称
     * @param string|null $defaultValue 默认值
     * @return mixed|null
     * @author chongyue<chongyue.peng@iaround.com>
     */
    function loadConfig($file, $key = null, $defaultValue = null)
    {
        if (defined('DEV') && DEV === true) {
            $filePath = CONFIG_PATH . $file . '.test.php';
        } else {
            $filePath = CONFIG_PATH . $file . '.php';
        }

        static $configList = [];
        if (!isset($configList[$filePath])) {
            $data = null;
            if (is_file($filePath)) {
                $data = include $filePath;
            }
            $configList[$filePath] = $data;
        }

        if (!is_null($key)) {
            if (isset($configList[$filePath][$key])) {
                return $configList[$filePath][$key];
            }
            return $defaultValue;

        }
        return $configList[$filePath];
    }
}

if (!function_exists('int2Bytes')) {
    /**
     * 数子转换为字节码
     * @param $int
     * @return string
     * @author chongyue<chongyue.peng@iaround.com>
     */
    function int2Bytes($int)
    {
        $a0 = ($int >> 24 & 0xFF);
        $a1 = ($int >> 16 & 0xFF);
        $a2 = ($int >> 8 & 0xFF);
        $a3 = ($int & 0xFF);
        return chr($a0) . chr($a1) . chr($a2) . chr($a3);
    }
}

if (!function_exists('messageFormat')) {
    /**
     * 格式化消息为二进制
     * @param $messageId
     * @param $data
     * @return string
     * @author chongyue<chongyue.peng@iaround.com>
     */
    function messageFormat($messageId, $data)
    {
        $bin = '';
        isset($data['message']) && $data['message'] = json_encode($data['message']);
        $message = json_encode($data);
        $len = strlen($message);
        $bin .= int2Bytes($messageId);
        $bin .= int2Bytes($len);
        for ($i = 0; $i < $len; $i++) {
            $bin .= $message{$i};
        }
        return $bin;
    }
}

if (!function_exists('writeLog')) {
    /**
     * 正常日志
     * @author chongyue<chongyue.peng@iaround.com>
     */
    function writeLog()
    {
        static $log = null;
        if (is_null($log)) $log = new \Lib\Utils\LogUtils();
        $log->writeLog(func_get_args());
    }
}

if (!function_exists('writeErrorLog')) {
    /**
     * 错误日志
     * @author chongyue<chongyue.peng@iaround.com>
     */
    function writeErrorLog()
    {
        static $log = null;
        if (is_null($log)) $log = new \Lib\Utils\LogUtils(null, 'error');
        $log->writeLog(func_get_args());
    }
}