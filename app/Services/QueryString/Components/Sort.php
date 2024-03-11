<?php

namespace App\Services\QueryString\Components;

use App\Services\QueryString\Enums\Direction;

final class Sort
{
    private string $column;

    private string $direction;

    public function getColumn(): string
    {
        return $this->column;
    }

    public function setColumn(string $column): void
    {
        $this->column = $column;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function setDirection(Direction $direction): void
    {
        $this->direction = $direction->value;
    }
}
