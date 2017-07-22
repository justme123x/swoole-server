<?php

namespace Lib\Services;

/**
 * 礼物长连接
 * Class GiftClientService
 * @package Lib\Services
 * @author chongyue<chongyue.peng@iaround.com>
 */
class GiftClientService
{
    public $client = null;
    private $ip = null;
    private $port = null;

    /**
     * 创建连接
     * ClientSwoole constructor.
     */
    public function __construct($ip, $port)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->connect();
    }

    /**
     * 连接服务
     * @return $this
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function connect()
    {
        if ($this->client instanceof \swoole_client) {
            if ($this->client->isConnected()) {
                return $this;
            } else {
                writeLog('client doHandShake');
            }

        }
        $this->client = new \swoole_client(SWOOLE_SOCK_TCP | SWOOLE_KEEP);
        if (!$this->client->connect($this->ip, $this->port, -1)) {
            writeLog('connect java tcp error:' . $this->ip . ':' . $this->port);
        } else {
            writeLog('connect java tcp success');
        }
        $this->send($this->loginServer());

        swoole_timer_tick(3000, function ($client) {
            writeLog('send java server phpBeatHeart');
            $beatHeartBin = messageFormat(61004, ['message' => ['tag' => 'php server']]);
            $this->send($beatHeartBin);
        }, $this);

        writeLog("login repeat server {$this->ip}:{$this->port} success");
        return $this;
    }

    /**
     * 登录repeat服务
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function loginServer()
    {
        $data = [
            'type' => 1,
            'serverID' => 221888,
        ];
        return messageFormat($this->port, $data);
    }

    /**
     * 发送信息
     * @param string $message
     * @return $this
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function send($message)
    {
        $len = $this->client->send($message);
        if ($len === false) {
            writeLog('send error code:'.$this->client->errCode);
        } else {
            writeLog('send success');
        }
        return $this;
    }

    /**
     * 接受消息
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function receive()
    {
        $message = $this->client->recv(2048, false);
        writeLog('client receive message:' . $message);
    }
}