<?php

namespace Apifon\Model;


use Exception;

class OtpRequest implements IRequest {

    public $reference_id;
    public $subscriber;
    public $code_length;
    public $code_type;
    public $callback_url;
    public $expire; //in seconds
    public $message;

    public function getReferenceId()
    {
        return $this->reference_id;
    }

    public function setReferenceId($reference_id)
    {
        $this->reference_id = $reference_id;
    }

    public function getSubscriber()
    {
        return $this->subscriber;
    }

    public function setSubscriber($subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function getCodeLength()
    {
        return $this->code_length;
    }

    public function setCodeLength($code_length)
    {
        $this->code_length = $code_length;
    }

    public function getCodeType()
    {
        return $this->code_type;
    }

    public function setCodeType($code_type)
    {
        $this->code_type = $code_type;
    }

    public function getCallbackUrl()
    {
        return $this->callback_url;
    }

    public function setCallbackUrl($callback_url)
    {
        $this->callback_url = $callback_url;
    }

    public function getExpire()
    {
        return $this->expire;
    }

    public function setExpire($expire)
    {
        $this->expire = $expire;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getBody()
    {
        throw new Exception('Not implemented');
    }

    public function getCreateBody()
    {
        return preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', json_encode(get_object_vars($this)));
    }

    public function getReadBody()
    {
        throw new Exception('Not implemented');
    }

    public function getUpdateBody()
    {
        throw new Exception('Not implemented');
    }

    public function getDeleteBody()
    {
        throw new Exception('Not implemented');
    }
}