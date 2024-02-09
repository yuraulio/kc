<?php

namespace Apifon\Model;

class IMAction
{
    public $title;
    public $target_url;

    public function __construct()
    {
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTargetUrl()
    {
        return $this->target_url;
    }

    public function setTargetUrl($target_url)
    {
        $this->target_url = $target_url;

        return $this;
    }
}
