<?php

namespace Apifon\Model;

    use Exception;

    class ImRequest extends SubscribersViewRequest{

        public $reference_id;
        public $message;
        public $callback_url;
        public $reply_url;
        public $im_channels;
        public $date;

        function __construct() {
        }

        public function getReference()
        {
            return $this->reference_id;
        }

        public function setReference($reference)
        {
            $this->reference_id = $reference;
        }

        public function getMessage()
        {
            return $this->message;
        }

        public function setMessage($message)
        {
            $this->message = $message;
        }

        public function getCallbackUrl()
        {
            return $this->callback_url;
        }

        public function setCallbackUrl($callback)
        {
            $this->callback_url = $callback;
        }

        public function getReplyUrl()
        {
            return $this->reply_url;
        }

        public function setReplyUrl($reply_url)
        {
            $this->reply_url = $reply_url;
        }

        public function getImChannels()
        {
            return $this->im_channels;
        }

        public function setImChannels($imChannels)
        {
            $this->im_channels = $imChannels;
        }

        /**
         * adds an imChannel to the existing List.
         * @param $imChannel
         */
        public function addImChannel($imChannel){
            if(is_null($this->im_channels)){
                $this->im_channels = array();
            }
            $this->im_channels[] = $imChannel;
        }

        public function getDate()
        {
            return $this->date;
        }

        public function setDate($date)
        {
            $this->date = $date;
        }

        /**
         * Returns the body of the Request.
         * @return string
         */
        public function getBody()
        {
            return preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', json_encode(get_object_vars($this)));
        }

        public function getCreateBody()
        {
            throw new Exception('Not implemented');
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