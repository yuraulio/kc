<?php

namespace Apifon\Model {

    class IMChannel {

        public $id;
        public $sender_id;
        public $text;
        public $images;
        public $actions;
        public $ttl;
        public $expiry_text;

        function __construct() {
        }

        public function getId()
        {
            return $this->id;
        }

        public function setId($id)
        {
            $this->id = $id;
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

        public function getText()
        {
            return $this->text;
        }

        public function setText($text)
        {
            $this->text = $text;
            return $this;
        }

        public function getImages()
        {
            return $this->images;
        }

        public function setImages($images)
        {
            $this->images = $images;
            return $this;
        }

        /**
         * Adds an image to the existing list.
         * @param $image
         */
        public function addImage($image){
            if(is_null($this->images)){
                $this->images = array();
            }
            $this->images[] = $image;
        }

        public function getActions()
        {
            return $this->actions;
        }

        public function setActions($actions)
        {
            $this->actions = $actions;
            return $this;
        }

        /**
         * Adds an action to the existing list.
         * @param $action
         */
        public function addAction($action){
            if(is_null($this->actions)){
                $this->actions= array();
            }
            $this->actions[] = $action;
        }

        public function getTtl()
        {
            return $this->ttl;
        }

        public function setTtl($ttl)
        {
            $this->ttl = $ttl;
            return $this;
        }

        public function getExpiryText()
        {
            return $this->expiry_text;
        }

        public function setExpiryText($expiry_text)
        {
            $this->expiry_text = $expiry_text;
            return $this;
        }


    }

}
?>