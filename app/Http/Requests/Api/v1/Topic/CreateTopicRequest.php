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
            'short_title' => ['required', 'string'],
            'subtitle' => ['required', 'string'],
            'header' => ['required', 'string'],
            'summary' => ['required', 'string'],
            'body' => ['required', 'string'],
            'email_template' => ['nullable', 'string'],
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
