<?php

namespace App\Services\QueryString\Components;

use DateTime;

abstract class Filter
{
    private string $column;

    private string $operator;

    private string|array $value;

    public function getColumn(): string
    {
        return $this->column;
    }

    public function setColumn(string $column): void
    {
        $this->column = $column;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function setOperator($operator): void
    {
        $this->operator = $operator;
    }

    public function getValue(): string|array
    {
        return $this->value;
    }

    public function setValue(string|array $value): void
    {
        $this->value = $value;
    }

    public function isDateValue(): bool
    {
        $dateTime = DateTime::createFromFormat('Y-m-d', $this->getValue());

        return $dateTime !== false && $dateTime::getLastErrors() === false;
    }

    public function isArrayValue(): bool
    {
        return is_array($this->getValue());
    }
}
