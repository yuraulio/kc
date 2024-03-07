<?php

namespace App\Services\QueryString\Components;

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
        return is_scalar($this->getValue()) && strtotime($this->getValue());
    }

    public function isArrayValue(): bool
    {
        return is_array($this->getValue());
    }
}
