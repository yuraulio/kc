<?php

namespace App\Http\Requests\Api\v1\Topic;

use App\Contracts\Api\v1\Dto\IDtoRequest;
use App\Dto\Api\v1\Topic\TopicDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateTopicRequest extends FormRequest implements IDtoRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'numeric'],
            'title' => ['required', 'min:3'],
            'subtitle' => ['required', 'string'],
            'email_template' => ['nullable', 'string'],
            'exams' => ['nullable', 'array'],
            'exams.*' => ['nullable', 'numeric'],
            'exams_assigned' => ['required', 'boolean'],
            'classroom_courses' => ['nullable', 'array'],
            'classroom_courses.*' => ['nullable', 'numeric'],
            'video_courses' => ['nullable', 'array'],
            'video_courses.*' => ['nullable', 'numeric'],
            'live_streaming_courses' => ['nullable', 'array'],
            'live_streaming_courses.*' => ['nullable', 'numeric'],
            'messages' => ['nullable', 'array'],
            'messages.*' => ['nullable', 'numeric'],
            'messages_rules' => ['nullable', 'string'],
        ];
    }

    public function toDto(): TopicDto
    {
        return new TopicDto(
            $this->validated(),
            Auth::id(),
            Auth::id(),
        );
    }
}
