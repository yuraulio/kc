<?php

declare(strict_types=1);

namespace App\Services\QueryString\Parameter;

final class SearchParameter
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
