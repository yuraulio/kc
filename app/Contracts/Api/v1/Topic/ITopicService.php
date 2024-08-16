<?php

namespace App\Contracts\Api\v1\Topic;

use App\Dto\Api\v1\Topic\TopicDto;
use App\Model\Topic;

interface ITopicService
{
    public function create(TopicDto $dto);

    public function update(Topic $topic, TopicDto $dto): Topic;
}
