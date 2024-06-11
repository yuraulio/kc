<?php

declare(strict_types=1);

namespace App\Services\QueryString\Parameter;

final class RelationFilterParameter extends Filter
{
    private string $relation;

    public function getRelation(): string
    {
        return $this->relation;
    }

    public function setRelation(string $relation): void
    {
        $this->relation = $relation;
    }
}
