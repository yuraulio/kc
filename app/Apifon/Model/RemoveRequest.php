<?php

namespace Apifon\Model;

use Exception;

class RemoveRequest implements IRequest
{
    public $id;
    public $field;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getField()
    {
        return $this->field;
    }

    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * Returns the body of the Request.
     * @return string
     */
    public function getBody()
    {
        return json_encode(get_object_vars($this));
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
