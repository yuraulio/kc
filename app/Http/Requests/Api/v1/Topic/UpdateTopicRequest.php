<?php

namespace App\Http\Requests\Api\v1\Topic;

use App\Contracts\Api\v1\Dto\IDtoRequest;
use App\Dto\Api\v1\Topic\TopicDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateTopicRequest extends CreateTopicRequest implements IDtoRequest
{
    public function rules(): array
    {
        return [
            'status' => ['nullable', 'numeric'],
            'title' => ['nullable', 'min:3'],
            'short_title' => ['nullable', 'string'],
            'subtitle' => ['nullable', 'string'],
            'header' => ['nullable', 'string'],
            'summary' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'email_template' => ['nullable', 'string'],
        ];
    }
}
