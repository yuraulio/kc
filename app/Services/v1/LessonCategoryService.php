<?php

declare(strict_types=1);

namespace App\Services\v1;

use App\Model\LessonCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LessonCategoryService
{
    public function filter(array $data): LengthAwarePaginator
    {
        return LessonCategory::query()
            ->with($this->getRelations())
            ->when(array_key_exists('query', $data), function ($q) use ($data) {
                $q->where(function ($q) use ($data) {
                    $q->where('lesson_categories.name', 'like', '%' . $data['query'] . '%');
                });
            })
            ->orderBy($data['order_by'] ?? 'id', $data['order_type'] ?? 'desc')
            ->paginate($data['per_page'] ?? 25);
    }

    public function store(array $data): LessonCategory
    {
        $category = new LessonCategory($data);
        $category->save();

        $category->load($this->getRelations());

        return $category;
    }

    public function update(LessonCategory $category, array $data): LessonCategory
    {
        $category->fill($data);
        $category->save();

        $category->load($this->getRelations());

        return $category;
    }

    public function getRelations(): array
    {
        return [
        ];
    }
}
