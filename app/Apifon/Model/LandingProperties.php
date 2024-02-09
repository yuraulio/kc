<?php

namespace Apifon\Model;

class LandingProperties
{
    public $url;
    public $data;
    public $redirect;
    public $method;
    public $prevent_sys_params;

    public function __construct()
    {
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Adds data to the key-value set.
     * @param $key
     * @param $value
     */
    public function addData($key, $value)
    {
        if (is_null($this->data)) {
            $this->data = [];
        }
        $this->data[$key] = $value;
    }

    public function getRedirect()
    {
        return $this->redirect;
    }

    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getPreventSysParams()
    {
        return $this->prevent_sys_params;
    }

    public function setPreventSysParams($prevent_sys_params)
    {
        $this->prevent_sys_params = $prevent_sys_params;
    }
}
