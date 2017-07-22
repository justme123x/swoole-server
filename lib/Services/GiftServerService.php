<?php

namespace Lib\Services;

/**
 * 对外服务类
 * Class GiftServerService
 * @package Lib\Services
 * @author chongyue<chongyue.peng@iaround.com>
 */
class GiftServerService
{
    public $server = null;
    private $ip = null;
    private $port = null;
    private $handleInstanceList = [];

    public function __construct()
    {
        $this->ip = loadConfig('giftServer', 'ip', '0.0.0.0');
        $this->port = loadConfig('giftServer', 'port', 1788);
        $this->server = new \swoole_server($this->ip, $this->port);
        writeLog('server starting...');
        $this->set();
        $this->registerCallBack();
    }

    /**
     * 启动服务
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function start()
    {
        writeLog('server is running ' . $this->ip . ':' . $this->port);
        $this->server->start();
    }

    /**
     * 挂载事件
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function registerCallBack()
    {
        $this->onConnect();
        $this->onReceive();
        $this->onTask();
        $this->onFinish();
        $this->onClose();
    }

    /**
     * 链接事件
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function onConnect()
    {
        $this->server->on('connect', function ($serv, $fd) {
            writeLog("client {$fd} connect");
        });
    }

    /**
     * 收到数据事件
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function onReceive()
    {
        $this->server->on('receive', function ($serv, $fd, $from_id, $data) {
            $this->server->task($data);
            $serv->close($fd);
        });
    }

    /**
     * 发送到客户端
     * @param $data
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function sendClient($data)
    {
        $encodeData = json_decode($data, true);
        $messageId = isset($encodeData['messageId']) ? (int)$encodeData['messageId'] : null;
        if (!is_null($messageId)) {
            $handle = loadConfig('handleMap', $messageId, null);
            if (!is_null($handle)) {
                $this->getHandleInstance($handle, $messageId, $encodeData)->send();
                return;
            } else {
                writeErrorLog('not found handle . message:' . $data);
            }
        } else {
            writeErrorLog('not found messageID . message:' . $data);
        }
        writeLog("server send error:{$data}");
        return;
    }

    /**
     * 任务事件
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function onTask()
    {
        $this->server->on('Task', function ($server, $taskId, $srcWorkId, $data) {
            writeLog("taskId:{$taskId} workId:{$srcWorkId} data:" . $data);
            $this->sendClient($data);
            return 'send:ok';
        });

    }

    /**
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function onFinish()
    {
        $this->server->on('Finish', function ($server, $taskId, $data) {
            writeLog("Task {$taskId} Finish: {$data}");
        });
    }

    /**
     * 获取链接实例
     * @param $handle
     * @param $messageId
     * @param $data
     * @return object
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function getHandleInstance($handle, $messageId, $data)
    {
        if (!isset($this->handleInstanceList[$handle])) {
            $handleInstance = new $handle();
            $this->handleInstanceList[$handle] = &$handleInstance;
        }
        $this->handleInstanceList[$handle]->setMessageId($messageId);
        $this->handleInstanceList[$handle]->setData($data);
        return $this->handleInstanceList[$handle];
    }

    /**
     * 链接断开事件
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function onClose()
    {
        $this->server->on('close', function ($serv, $fd) {
            writeLog("client {$fd} close");
        });
    }


    /**
     * 设置服务
     * @author chongyue<chongyue.peng@iaround.com>
     */
    private function set()
    {
        $config = [
            'task_max_request' => 1000,
            'reactor_num' => 2,
            'worker_num' => 2,
            'task_worker_num' => 4,
            'daemonize' => true,
//            'open_eof_check' => true,
//            'package_eof' => "\r\n",
//            'worker_num' => 4,
//            'daemonize' => false,
        ];
        $this->server->set($config);
        writeLog('config server', $config);
    }

}