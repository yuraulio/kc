<?php

namespace Apifon\Response {

    class ResultDetail {

        public $message_id;
        public $custom_id;
        public $length;
        public $short_code;
        public $short_url;

        public function __construct($arrayValues) {
            $this->message_id = $arrayValues["message_id"];
            $this->custom_id = $arrayValues["custom_id"];
            $this->length = $arrayValues["length"];
            $this->short_code = $arrayValues["short_code"];
            $this->short_url = $arrayValues["short_url"];
        }
    }

}
?>