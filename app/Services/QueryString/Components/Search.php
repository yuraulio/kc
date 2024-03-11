<?php

namespace App\Services\QueryString\Components;

final class Search
{
    private string $term;

    public function getTerm(): string
    {
        return $this->term;
    }

    public function setTerm(string $term): void
    {
        $this->term = $term;
    }
}
