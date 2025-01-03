<?php

namespace App\Http\Requests\Api\v1\Lesson;

use App\Contracts\Api\v1\Dto\IDtoRequest;
use App\Dto\Api\v1\Lesson\LessonDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateLessonRequest extends FormRequest implements IDtoRequest
{
    public function rules(): array
    {
        return [
            'title'                    => ['required', 'string', 'min:3'],
            'status'                   => ['required', 'boolean'],
            'subtitle'                 => ['required', 'string'],
            'vimeo_video'              => ['nullable', 'string'],
            'vimeo_duration'           => ['nullable', 'string'],
            'classroom_courses'        => ['nullable', 'array'],
            'classroom_courses.*'      => ['nullable', 'numeric'],
            'video_courses'            => ['nullable', 'array'],
            'video_courses.*'          => ['nullable', 'numeric'],
            'live_streaming_courses'   => ['nullable', 'array'],
            'live_streaming_courses.*' => ['nullable', 'numeric'],
            'category_id'              => ['nullable', 'exists:lesson_categories,id'],
            'created_at'               => ['nullable', 'date'],
        ];
    }

    public function toDto(): LessonDto
    {
        return new LessonDto(
            $this->validated(),
            Auth::id(),
            Auth::id(),
        );
    }
}
