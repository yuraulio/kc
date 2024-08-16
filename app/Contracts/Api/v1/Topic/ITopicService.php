<?php

namespace App\Contracts\Api\v1\Topic;

use App\Dto\Api\v1\Topic\TopicDto;
use App\Model\Topic;

interface ITopicService
{
    function create(TopicDto $dto);
    function update(Topic $topic, TopicDto $dto): Topic;
}
