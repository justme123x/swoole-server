<?php

namespace Lib\Handles;

class Handle
{
    protected $messageId = null;
    protected $data = null;

    /**
     * 设置消息id
     * @param $messageId
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * 设置消息
     * @param $data
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * 发送消息
     * @return string
     * @author chongyue<chongyue.peng@iaround.com>
     */
    public function send()
    {
        return messageFormat($this->messageId, $this->data);
    }
}