<?php

declare(strict_types=1);

namespace App\Services\v1;

use App\Model\Review;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ReviewService
{
    public function filterQuery(array $data): Builder
    {
        return Review::query()
            ->when(array_key_exists('event_id', $data), function ($q) use ($data) {
                $q->whereHas('event', function ($q) use ($data) {
                    $q->where('events.id', $data['event_id']);
                });
            })->when(array_key_exists('user_id', $data), function ($q) use ($data) {
                $q->whereHas('user', function ($q) use ($data) {
                    $q->where('users.id', $data['user_id']);
                });
            })->when(array_key_exists('tags', $data), function ($q) use ($data) {
                $q->whereHas('tags', function ($q) use ($data) {
                    $q->whereIn('tags.id', array_map('intval', $data['tags']));
                });
            })->when(array_key_exists('date_from', $data), function ($q) use ($data) {
                $q->where('reviews.created_at', '>=', Carbon::parse($data['date_from']));
            })->when(array_key_exists('date_to', $data), function ($q) use ($data) {
                $q->where('reviews.created_at', '<=', Carbon::parse($data['date_to']));
            })->when(array_key_exists('query', $data), function ($q) use ($data) {
                $q->where(function ($q) use ($data) {
                    $q->where('reviews.title', 'like', '%' . $data['query'] . '%')
                        ->orWhere('reviews.content', 'like', '%' . $data['query'] . '%');
                });
            })->with($this->getRelations())->orderBy($data['order_by'] ?? 'id', $data['order_type'] ?? 'desc');
    }

    public function store(array $data): Review
    {
        $review = new Review($data);
        $review->user()->associate($data['user_id']);
        $review->event()->associate($data['event_id']);
        $review->save();

        $review->visibility = ['Classroom', 'E-learning'];
        $review->save();

        if (array_key_exists('tags', $data)) {
            $review->tags()->sync($data['tags']);
        }

        $review->load($this->getRelations());

        return $review;
    }

    public function update(Review $review, array $data): Review
    {
        $review->fill($data);
        $review->save();

        $review->load($this->getRelations());

        return $review;
    }

    public function getRelations(): array
    {
        return [
            'user',
            'event',
        ];
    }
}
