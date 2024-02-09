<?php

namespace Apifon\Response {
    class ResultInfo
    {
        public $status_code;
        public $description;

        public function __construct($arrayValues)
        {
            $this->status_code = $arrayValues['status_code'];
            $this->description = $arrayValues['description'];
        }
    }
}
