<?php

namespace Apifon\Resource {

    use Apifon\Response\BalanceResponse;

    class BalanceResource extends AbstractResource {

        public function __construct() {
            parent::__construct($endpoint = "/services/balance");
        }

        /**
         * Sends a Balance request.
         * @return BalanceResponse
         */
        public function send(){

            $this->setCurMethod("POST");
            $this->setCurEndpoint($this->getEndpoint());
            $this->setCurBody("");
            $return = json_decode(parent::dispatch(), true);
            $result = new BalanceResponse($return);
            return $result;
        }
    }
}