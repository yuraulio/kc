<?php

namespace Apifon\Model;

    use Exception;

    class SmsRequest extends SubscribersViewRequest{

        public $reference_id;
        public $message;
        public $callback_url;
        public $tte;
        public $date;

        public function getReferenceId()
        {
            return $this->reference_id;
        }

        public function setReferenceId($reference_id)
        {
            $this->reference_id = $reference_id;
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

        public function setCallbackUrl($callback_url)
        {
            $this->callback_url = $callback_url;
        }

        public function getTte()
        {
            return $this->tte;
        }

        public function setTte($tte)
        {
            $this->tte = $tte;
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