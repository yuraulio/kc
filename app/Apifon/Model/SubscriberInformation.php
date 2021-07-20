<?php

namespace Apifon\Model {

    class SubscriberInformation {

        public $number;
        public $custom_id;
        public $params = null;

        public function getNumber()
        {
            return $this->number;
        }

        public function setNumber($number)
        {
            $this->number = $number;
            return $this;
        }

        public function getCustomId()
        {
            return $this->custom_id;
        }

        public function setCustomId($custom_id)
        {
            $this->custom_id = $custom_id;
            return $this;
        }

        public function getParams()
        {
            return $this->params;
        }

        public function setParams($params)
        {
            $this->params = $params;
            return $this;
        }

        /**
         * Adds data to the key-value set.
         * @param $key
         * @param $value
         */
        public function addParams($key, $value){
            if(is_null($this->params)){
                $this->params = array();
            }
            $this->params[$key] = $value;
        }

        /**
         * Sends a landing page property to subscriber's Params.
         * @param $lp
         */
        public function setLandingPage($lp){
            if(is_null($this->params)){
                $this->params = array();
            }
            $this->params["apifon_lp"] = $lp;
        }


    }

}
?>