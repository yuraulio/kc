<?php

namespace App\Contracts\Api\v1\Dto;

interface IDtoRequest
{
    public function toDto(): IDto;
}
