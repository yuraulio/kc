<?php

declare(strict_types=1);

namespace App\Services\QueryString\Parameter;

use App\Services\QueryString\Enums\Direction;

final class SortParameter
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
