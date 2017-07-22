<?php

namespace Lib\Handles;

use Lib\Services\GiftClientService;

class GiftHandle extends Handle
{
    private $ip = null;
    private $port = null;
    private $client = null;

    public function __construct()
    {
        $this->ip = loadConfig('giftHandle', 'ip', '192.168.100.202');
        $this->port = loadConfig('giftHandle', 'port', 61001);
        $this->client = (new GiftClientService($this->ip, $this->port));
    }

    /**
     * 发送数据
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function send()
    {
        if (!isset($this->data['group_id']) || empty($this->data['group_id'])) {
            writeErrorLog('not defined group_id! messageId:' . $this->messageId . ' data:', $this->data);
            return false;
        }
        $bin = parent::send();
        $this->client->connect()->send($bin);
        return true;
    }
}