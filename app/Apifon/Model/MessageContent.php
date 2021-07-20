<?php

namespace Apifon\Model {

    class MessageContent {

        public $text;
        public $dc;
        public $sender_id;

        function __construct() {
        }

        public function getText()
        {
            return $this->text;
        }

        public function setText($text)
        {
            $this->text = $text;
            return $this;
        }

        public function getDc()
        {
            return $this->dc;
        }

        public function setDc($dc)
        {
            $this->dc = $dc;
            return $this;
        }

        public function getSenderId()
        {
            return $this->sender_id;
        }

        public function setSenderId($sender_id)
        {
            $this->sender_id = $sender_id;
            return $this;
        }


    }

}
?>