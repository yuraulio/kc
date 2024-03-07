<?php

namespace App\Services\QueryString\Components;

final class RelationFilter extends Filter
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
