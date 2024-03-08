<?php

namespace App\Services\QueryString\Components;

class Search
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
