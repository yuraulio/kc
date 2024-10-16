<?php

declare(strict_types=1);

namespace App\Services\v1;

use App\Model\Skill;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SkillService
{
    public function filter(array $data): LengthAwarePaginator
    {
        return Skill::query()
            ->with($this->getRelations())
            ->when(array_key_exists('query', $data), function ($q) use ($data) {
                $q->where(function ($q) use ($data) {
                    $q->where('skills.name', 'like', '%' . $data['query'] . '%');
                });
            })
            ->orderBy($data['order_by'] ?? 'id', $data['order_type'] ?? 'desc')
            ->paginate($data['per_page'] ?? 25);
    }

    public function store(array $data): Skill
    {
        $skill = new Skill($data);
        $skill->save();

        $skill->load($this->getRelations());

        return $skill;
    }

    public function update(Skill $skill, array $data): Skill
    {
        $skill->fill($data);
        $skill->save();

        $skill->load($this->getRelations());

        return $skill;
    }

    public function getRelations(): array
    {
        return [
        ];
    }
}
