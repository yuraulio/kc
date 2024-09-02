<?php

namespace App\Services\Topic;

use App\Contracts\Api\v1\Topic\ITopicService;
use App\Dto\Api\v1\Topic\TopicDto;
use App\Model\Topic;

class TopicService implements ITopicService
{
    public function create(TopicDto $dto)
    {
        return Topic::create($dto->getData());
    }

    public function update(Topic $topic, TopicDto $dto): Topic
    {
        $topic->update($dto->getData());

        return $topic;
    }

    public function getAllTopicWithEvents()
    {
        return Topic::with(['events' => function ($q) {
            $q->where('status', 1);
        }])->whereHas('events', function ($q) {
            $q->where('status', 1);
        })->where('status', 1)->groupBy(['id'])->limit(10)->get();
    }
}
