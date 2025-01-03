<?php

declare(strict_types=1);

namespace App\Services\v1;

use App\Model\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TagService
{
    public function filter(array $data): LengthAwarePaginator
    {
        return Tag::query()
            ->with($this->getRelations())
            ->when(array_key_exists('query', $data), function ($q) use ($data) {
                $q->where(function ($q) use ($data) {
                    $q->where('tags.name', 'like', '%' . $data['query'] . '%');
                });
            })
            ->when(array_key_exists('event_id', $data), function ($q) use ($data) {
                $q->whereHas('events', function ($q) use ($data) {
                    $q->where('events.id', $data['event_id']);
                });
            })
            ->when(array_key_exists('user_id', $data), function ($q) use ($data) {
                $q->whereHas('users', function ($q) use ($data) {
                    $q->where('users.id', $data['user_id']);
                });
            })
            ->orderBy($data['order_by'] ?? 'id', $data['order_type'] ?? 'desc')
            ->paginate($data['per_page'] ?? 25);
    }

    public function store(array $data): Tag
    {
        $tag = new Tag($data);
        $tag->save();

        $tag->load($this->getRelations());

        return $tag;
    }

    public function update(Tag $tag, array $data): Tag
    {
        $tag->fill($data);
        $tag->save();

        $tag->load($this->getRelations());

        return $tag;
    }

    public function getRelations(): array
    {
        return [
        ];
    }
}
