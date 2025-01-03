<?php

namespace App\Http\Requests\Api\v1\Lesson;

use App\Contracts\Api\v1\Dto\IDtoRequest;
use App\Dto\Api\v1\Lesson\LessonDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateLessonRequest extends CreateLessonRequest implements IDtoRequest
{
    public function rules(): array
    {
        return [
            'title'                    => ['nullable', 'string', 'min:3'],
            'status'                   => ['required', 'boolean'],
            'subtitle'                 => ['nullable', 'string'],
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
}
