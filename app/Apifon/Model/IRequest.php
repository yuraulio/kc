<?php

namespace Apifon\Model;

interface IRequest
{
    public function getBody();

    public function getCreateBody();

    public function getReadBody();

    public function getUpdateBody();

    public function getDeleteBody();
}
