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
            'htmlTitle' => ['nullable', 'string', 'min:3'],
            'title' => ['nullable', 'string', 'min:3'],
            'subtitle' => ['nullable', 'string'],
            'header' => ['nullable', 'string'],
            'summary' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'vimeo_video' => ['nullable', 'string'],
            'vimeo_duration' => ['nullable', 'string'],
        ];
    }
}
